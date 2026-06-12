<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OdooService
{
    protected $url;
    protected $db;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->url      = rtrim((string) config('services.odoo.url'), '/');
        $this->db       = config('services.odoo.db');
        $this->username = config('services.odoo.username');
        $this->password = config('services.odoo.password');
    }

    // Llamada generica a la API JSON-RPC de Odoo
    protected function jsonRpc(array $params)
    {
        $respuesta = Http::timeout(15)->post($this->url . '/jsonrpc', [
            'jsonrpc' => '2.0',
            'method'  => 'call',
            'params'  => $params,
            'id'      => rand(1, 100000),
        ]);

        $data = $respuesta->json();

        if (isset($data['error'])) {
            throw new \Exception('Odoo error: ' . json_encode($data['error']));
        }

        return $data['result'] ?? null;
    }

    // Inicia sesion en Odoo y devuelve el uid del usuario
    protected function autenticar()
    {
        return $this->jsonRpc([
            'service' => 'common',
            'method'  => 'authenticate',
            'args'    => [$this->db, $this->username, $this->password, new \stdClass()],
        ]);
    }

    // Ejecuta una accion sobre un modelo de Odoo (buscar, crear, etc.)
    protected function ejecutar($uid, $modelo, $metodo, $args, $kwargs = [])
    {
        return $this->jsonRpc([
            'service' => 'object',
            'method'  => 'execute_kw',
            'args'    => [$this->db, $uid, $this->password, $modelo, $metodo, $args, (object) $kwargs],
        ]);
    }

    /**
     * Crea (o reutiliza) el cliente en Odoo a partir de una compra.
     * Devuelve el id del contacto en Odoo, o null si no se pudo.
     */
    public function sincronizarCliente(string $nombre, string $email, ?string $telefono = null, ?string $direccion = null): ?int
    {
        // Si falta configuracion, no hacemos nada (no rompe la compra)
        if (! $this->url || ! $this->db || ! $this->username || ! $this->password) {
            return null;
        }

        try {
            $uid = $this->autenticar();

            if (! $uid) {
                Log::warning('Odoo: autenticacion fallida.');
                return null;
            }

            // 1) Buscamos si el cliente ya existe en Odoo por su email
            $existentes = $this->ejecutar($uid, 'res.partner', 'search',
                [[['email', '=', $email]]], ['limit' => 1]);

            if (! empty($existentes)) {
                return $existentes[0];
            }

            // 2) Si no existe, lo creamos como contacto/cliente (alimenta el CRM)
            $id = $this->ejecutar($uid, 'res.partner', 'create', [[
                'name'          => $nombre,
                'email'         => $email,
                'phone'         => $telefono,
                'street'        => $direccion,
                'customer_rank' => 1,
            ]]);

            return is_int($id) ? $id : null;

        } catch (\Throwable $e) {
            // Si Odoo no responde, lo registramos pero la compra continua normal
            Log::error('Odoo sincronizarCliente: ' . $e->getMessage());
            return null;
        }
    }
}
