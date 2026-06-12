<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Integracion con Odoo (CRM + Ventas + Inventario) via API JSON-RPC.
 *
 * - Clientes  -> res.partner
 * - Productos -> product.template (almacenables: type=consu + is_storable)
 * - Ventas    -> sale.order confirmado
 * - Inventario-> Odoo es el dueño del stock; la venta valida la entrega y descuenta.
 *
 * Probado contra Odoo 19 (saas~19.3).
 */
class OdooService
{
    protected $url;
    protected $db;
    protected $username;
    protected $password;
    protected ?int $uid = null;

    public function __construct()
    {
        $this->url      = rtrim((string) config('services.odoo.url'), '/');
        $this->db       = (string) config('services.odoo.db');
        $this->username = (string) config('services.odoo.username');
        $this->password = (string) config('services.odoo.password');
    }

    // True si las 4 variables ODOO_* estan configuradas
    public function configurado(): bool
    {
        return $this->url !== '' && $this->db !== '' && $this->username !== '' && $this->password !== '';
    }

    // ------------------------------------------------------------------
    // API publica (cada metodo es "a prueba de fallos": si Odoo no
    // responde, registra el error y devuelve null sin romper la compra)
    // ------------------------------------------------------------------

    /** Crea/actualiza el cliente en Odoo. Devuelve el id del partner. */
    public function sincronizarCliente(string $nombre, string $email, ?string $telefono = null, ?string $direccion = null): ?int
    {
        if (! $this->configurado()) {
            return null;
        }

        try {
            return $this->asegurarCliente($nombre, $email, $telefono, $direccion);
        } catch (\Throwable $e) {
            Log::error('Odoo sincronizarCliente: ' . $e->getMessage());
            return null;
        }
    }

    /** Crea/actualiza un producto en Odoo. Devuelve el id de la variante. */
    public function sincronizarProducto(Producto $producto): ?int
    {
        if (! $this->configurado()) {
            return null;
        }

        try {
            return $this->asegurarProducto($producto);
        } catch (\Throwable $e) {
            Log::error("Odoo sincronizarProducto ({$producto->id}): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Registra un pedido completo en Odoo: cliente + pedido de venta
     * confirmado + descuento de inventario. Devuelve el id de la venta.
     */
    public function registrarPedido(Pedido $pedido): ?int
    {
        if (! $this->configurado()) {
            return null;
        }

        try {
            $pedido->loadMissing('cliente', 'items.producto');

            $partnerId = $this->asegurarCliente(
                $pedido->cliente->nombre,
                $pedido->cliente->email,
                $pedido->cliente->telefono,
                $pedido->cliente->direccion
            );

            // Una linea por item, enlazando el producto en Odoo
            $lineas = [];
            foreach ($pedido->items as $item) {
                if (! $item->producto) {
                    continue;
                }
                $variantId = $this->asegurarProducto($item->producto);
                $lineas[] = [0, 0, [
                    'product_id'      => $variantId,
                    'product_uom_qty' => $item->cantidad,
                    'price_unit'      => (float) $item->precio_unitario,
                ]];
            }

            if (empty($lineas)) {
                return null;
            }

            // Pedido de venta + confirmacion
            $soId = $this->exec('sale.order', 'create', [[
                'partner_id' => $partnerId,
                'order_line' => $lineas,
            ]]);
            $this->exec('sale.order', 'action_confirm', [[$soId]]);

            // Descuenta el inventario validando la(s) entrega(s)
            $this->validarEntregas($soId);

            return $soId;
        } catch (\Throwable $e) {
            Log::error('Odoo registrarPedido: ' . $e->getMessage());
            return null;
        }
    }

    // ------------------------------------------------------------------
    // Helpers internos (lanzan excepcion; los publicos las capturan)
    // ------------------------------------------------------------------

    protected function asegurarCliente(string $nombre, string $email, ?string $telefono, ?string $direccion): int
    {
        $datos = array_filter([
            'name'   => $nombre,
            'email'  => $email,
            'phone'  => $telefono,
            'street' => $direccion,
        ], fn ($v) => $v !== null && $v !== '');

        $existentes = $this->exec('res.partner', 'search', [[['email', '=', $email]]], ['limit' => 1]);

        if (! empty($existentes)) {
            $id = (int) $existentes[0];
            $this->exec('res.partner', 'write', [[$id], $datos]);
            return $id;
        }

        return (int) $this->exec('res.partner', 'create', [$datos + ['customer_rank' => 1]]);
    }

    protected function asegurarProducto(Producto $producto): int
    {
        $code = 'WEB-' . $producto->id;

        $existentes = $this->exec('product.template', 'search', [[['default_code', '=', $code]]], ['limit' => 1]);

        if (! empty($existentes)) {
            // Ya existe: actualizamos catalogo (nombre/precio) pero NO el stock,
            // porque el inventario lo gestiona Odoo a partir de ahora.
            $tmplId = (int) $existentes[0];
            $this->exec('product.template', 'write', [[$tmplId], [
                'name'       => $producto->nombre,
                'list_price' => (float) $producto->precio,
            ]]);
        } else {
            // No existe: lo creamos como producto almacenable y cargamos
            // su stock inicial desde el valor actual de la tienda.
            $tmplId = (int) $this->exec('product.template', 'create', [[
                'name'        => $producto->nombre,
                'list_price'  => (float) $producto->precio,
                'default_code' => $code,
                'type'        => 'consu',
                'is_storable' => true,
            ]]);

            $variantNuevo = $this->variantId($tmplId);
            $this->ponerStockInicial($variantNuevo, max((int) $producto->stock, 0));

            $this->guardarOdooId($producto, $variantNuevo);
            return $variantNuevo;
        }

        $variantId = $this->variantId($tmplId);
        $this->guardarOdooId($producto, $variantId);
        return $variantId;
    }

    // Devuelve el id de product.product (variante) de una plantilla
    protected function variantId(int $tmplId): int
    {
        $tmpl = $this->exec('product.template', 'read', [[$tmplId], ['product_variant_id']]);
        return (int) $tmpl[0]['product_variant_id'][0];
    }

    // Carga stock inicial mediante un ajuste de inventario
    protected function ponerStockInicial(int $variantId, int $cantidad): void
    {
        if ($cantidad <= 0) {
            return;
        }

        $loc = $this->exec('stock.location', 'search', [[['usage', '=', 'internal']]], ['limit' => 1]);
        if (empty($loc)) {
            return;
        }

        $quantId = $this->exec('stock.quant', 'create', [[
            'product_id'         => $variantId,
            'location_id'        => (int) $loc[0],
            'inventory_quantity' => $cantidad,
        ]]);
        $this->exec('stock.quant', 'action_apply_inventory', [[$quantId]]);
    }

    // Valida las entregas del pedido para descontar el inventario en Odoo
    protected function validarEntregas(int $soId): void
    {
        $so = $this->exec('sale.order', 'read', [[$soId], ['picking_ids']]);

        foreach ($so[0]['picking_ids'] ?? [] as $pid) {
            $pick = $this->exec('stock.picking', 'read', [[$pid], ['move_ids', 'state']])[0];

            if ($pick['state'] === 'done') {
                continue;
            }

            // Marca cada movimiento con su cantidad como entregada
            foreach ($pick['move_ids'] as $mid) {
                $mv = $this->exec('stock.move', 'read', [[$mid], ['product_uom_qty']])[0];
                $this->exec('stock.move', 'write', [[$mid], [
                    'quantity' => $mv['product_uom_qty'],
                    'picked'   => true,
                ]]);
            }

            // Valida saltando el wizard de SMS y la creacion de backorders
            $this->exec('stock.picking', 'button_validate', [[$pid]], [
                'context' => ['skip_sms' => true, 'skip_backorder' => true],
            ]);
        }
    }

    protected function guardarOdooId(Producto $producto, int $variantId): void
    {
        if ((int) $producto->odoo_id !== $variantId) {
            $producto->forceFill(['odoo_id' => $variantId])->saveQuietly();
        }
    }

    // ------------------------------------------------------------------
    // Capa JSON-RPC
    // ------------------------------------------------------------------

    // Inicia sesion (memoizado) y devuelve el uid
    protected function uid(): int
    {
        if ($this->uid !== null) {
            return $this->uid;
        }

        $uid = $this->jsonRpc([
            'service' => 'common',
            'method'  => 'authenticate',
            'args'    => [$this->db, $this->username, $this->password, new \stdClass()],
        ]);

        if (! is_int($uid) || $uid <= 0) {
            throw new \RuntimeException('Odoo rechazo las credenciales (uid invalido).');
        }

        return $this->uid = $uid;
    }

    // Ejecuta un metodo sobre un modelo (execute_kw)
    protected function exec(string $model, string $method, array $args, array $kwargs = [])
    {
        return $this->jsonRpc([
            'service' => 'object',
            'method'  => 'execute_kw',
            'args'    => [$this->db, $this->uid(), $this->password, $model, $method, $args, (object) $kwargs],
        ]);
    }

    // Llamada cruda al endpoint /jsonrpc
    protected function jsonRpc(array $params)
    {
        $respuesta = Http::timeout(20)->post($this->url . '/jsonrpc', [
            'jsonrpc' => '2.0',
            'method'  => 'call',
            'params'  => $params,
            'id'      => rand(1, 100000),
        ]);

        if ($respuesta->failed()) {
            throw new \RuntimeException('No se pudo contactar con Odoo (HTTP ' . $respuesta->status() . ').');
        }

        $data = $respuesta->json();

        if (isset($data['error'])) {
            $msg = $data['error']['data']['message'] ?? $data['error']['message'] ?? json_encode($data['error']);
            throw new \RuntimeException('Odoo: ' . $msg);
        }

        return $data['result'] ?? null;
    }
}