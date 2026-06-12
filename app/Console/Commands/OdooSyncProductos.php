<?php

namespace App\Console\Commands;

use App\Models\Producto;
use App\Services\OdooService;
use Illuminate\Console\Command;

class OdooSyncProductos extends Command
{
    protected $signature = 'odoo:sync-productos';

    protected $description = 'Empuja todos los productos de la tienda a Odoo (crea los que falten con su stock inicial)';

    public function handle(OdooService $odoo): int
    {
        if (! $odoo->configurado()) {
            $this->error('Odoo no esta configurado. Revisa las variables ODOO_* en tu .env.');
            return self::FAILURE;
        }

        $productos = Producto::all();
        $this->info("Sincronizando {$productos->count()} productos con Odoo...");

        $ok = 0;
        foreach ($productos as $producto) {
            $id = $odoo->sincronizarProducto($producto);

            if ($id) {
                $ok++;
                $this->line("  ✓ {$producto->nombre}  ->  Odoo id {$id}");
            } else {
                $this->warn("  ✗ {$producto->nombre}  (no se pudo sincronizar, revisa el log)");
            }
        }

        $this->info("Listo: {$ok}/{$productos->count()} productos sincronizados.");

        return self::SUCCESS;
    }
}