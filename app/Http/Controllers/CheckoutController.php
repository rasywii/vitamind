<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Services\OdooService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    // Muestra el formulario de checkout con el resumen del pedido
    public function mostrar()
    {
        $carrito = session()->get('carrito', []);

        if (count($carrito) === 0) {
            return redirect()->route('carrito.ver');
        }

        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        return view('tienda.checkout', compact('carrito', 'total'));
    }

    // Procesa la compra: cliente + pedido + items + stock (todo junto)
    public function procesar(Request $request)
    {
        // Validamos los datos del formulario
        $datos = $request->validate([
            'nombre'      => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'telefono'    => 'nullable|string|max:30',
            'direccion'   => 'nullable|string|max:255',
            'metodo_pago' => 'required|in:qr,efectivo,transferencia',
        ]);

        $carrito = session()->get('carrito', []);

        if (count($carrito) === 0) {
            return redirect()->route('carrito.ver');
        }

        // Nota: el inventario lo gestiona Odoo. La tienda ya no valida ni
        // descuenta stock; eso ocurre al registrar la venta en Odoo (abajo).

        // Transaccion: o se completa todo, o no se guarda nada (evita pedidos a medias)
        $pedido = DB::transaction(function () use ($datos, $carrito) {

            // 1) CLIENTE -> lo buscamos por email; si no existe lo creamos (alimenta el CRM)
            $cliente = Cliente::firstOrCreate(
                ['email' => $datos['email']],
                [
                    'nombre'    => $datos['nombre'],
                    'telefono'  => $datos['telefono'] ?? null,
                    'direccion' => $datos['direccion'] ?? null,
                ]
            );

            // 2) Calculamos el total del pedido
            $total = 0;
            foreach ($carrito as $item) {
                $total += $item['precio'] * $item['cantidad'];
            }

            // 3) PEDIDO -> la cabecera
            $pedido = Pedido::create([
                'cliente_id'  => $cliente->id,
                'total'       => $total,
                'estado'      => 'pagado',
                'canal'       => 'tienda',
                'metodo_pago' => $datos['metodo_pago'],
            ]);

            // 4) ITEMS -> una linea por producto (el inventario lo lleva Odoo)
            foreach ($carrito as $item) {
                PedidoItem::create([
                    'pedido_id'       => $pedido->id,
                    'producto_id'     => $item['producto_id'],
                    'variante_id'     => $item['variante_id'] ?? null,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                ]);
            }

            return $pedido;
        });

        // 5) Vaciamos el carrito
        session()->forget('carrito');

        // 6) INTEGRACION CON ODOO -> cliente + pedido de venta + inventario.
        // Va fuera de la transaccion y protegido: si Odoo falla o aun no esta
        // configurado, la compra ya quedo guardada y el cliente no ve un error.
        try {
            app(OdooService::class)->registrarPedido($pedido);
        } catch (\Throwable $e) {
            Log::warning('No se pudo sincronizar el pedido con Odoo: ' . $e->getMessage());
        }

        return redirect()->route('checkout.confirmacion', $pedido);
    }

    // Pagina de confirmacion del pedido
    public function confirmacion(Pedido $pedido)
    {
        $pedido->load('cliente', 'items.producto');

        return view('tienda.confirmacion', compact('pedido'));
    }
}
