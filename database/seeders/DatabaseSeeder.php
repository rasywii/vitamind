<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\Seguimiento;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------------
        // 1) CATEGORIAS
        // firstOrCreate evita duplicar si ya existe una con ese nombre
        // ---------------------------------------------------------------
        $snacks    = Categoria::firstOrCreate(['nombre' => 'Snacks Saludables']);
        $bebidas   = Categoria::firstOrCreate(['nombre' => 'Bebidas Funcionales']);
        $suplem    = Categoria::firstOrCreate(['nombre' => 'Suplementos']);
        $accesorios = Categoria::firstOrCreate(['nombre' => 'Accesorios']);
        $recetas   = Categoria::firstOrCreate(['nombre' => 'Recetas Digitales']);

        // ---------------------------------------------------------------
        // 2) PRODUCTOS (los reales del sitio + algunos para rellenar)
        // updateOrCreate: si ya existe uno con ese nombre, lo corrige
        // ---------------------------------------------------------------
        $productos = [
            // FISICOS  ->  controla_stock = true, sin archivo
            ['nombre' => 'Shaker Proteina Colorido',        'categoria_id' => $accesorios->id, 'tipo' => 'fisico', 'precio' => 18.00, 'stock' => 50],
            ['nombre' => 'Bolsa Comida Reutilizable',       'categoria_id' => $accesorios->id, 'tipo' => 'fisico', 'precio' => 22.00, 'stock' => 35],
            ['nombre' => 'Botella Agua Minimalista',        'categoria_id' => $accesorios->id, 'tipo' => 'fisico', 'precio' => 25.00, 'stock' => 40],
            ['nombre' => 'Smoothie Verde Revitalizante',    'categoria_id' => $bebidas->id,    'tipo' => 'fisico', 'precio' => 8.20,  'stock' => 80],
            ['nombre' => 'Omega 3 Puro',                    'categoria_id' => $suplem->id,     'tipo' => 'fisico', 'precio' => 30.00, 'stock' => 60],
            ['nombre' => 'Energia Metabolica Natural',      'categoria_id' => $suplem->id,     'tipo' => 'fisico', 'precio' => 40.00, 'stock' => 45],
            ['nombre' => 'Multivitaminico Esencial Diario', 'categoria_id' => $suplem->id,     'tipo' => 'fisico', 'precio' => 20.00, 'stock' => 70],
            ['nombre' => 'Mix de Frutos Secos',             'categoria_id' => $snacks->id,     'tipo' => 'fisico', 'precio' => 15.00, 'stock' => 90],
            ['nombre' => 'Barra Proteica Artesanal',        'categoria_id' => $snacks->id,     'tipo' => 'fisico', 'precio' => 12.00, 'stock' => 120],

            // DIGITALES -> controla_stock = false, stock 0, con archivo_url
            ['nombre' => 'Guia Cocina Consciente',          'categoria_id' => $recetas->id, 'tipo' => 'digital', 'precio' => 20.00, 'stock' => 0],
            ['nombre' => 'Recetario Saludable 30 Dias',     'categoria_id' => $recetas->id, 'tipo' => 'digital', 'precio' => 15.00, 'stock' => 0],
            ['nombre' => 'Guia de Meal Prep Semanal',       'categoria_id' => $recetas->id, 'tipo' => 'digital', 'precio' => 18.00, 'stock' => 0],
        ];

        foreach ($productos as $p) {
            $esDigital = $p['tipo'] === 'digital';

            Producto::updateOrCreate(
                ['nombre' => $p['nombre']],
                [
                    'categoria_id'   => $p['categoria_id'],
                    'descripcion'    => 'Producto de VitaMind: ' . $p['nombre'] . '.',
                    'tipo'           => $p['tipo'],
                    'precio'         => $p['precio'],
                    'stock'          => $p['stock'],
                    'controla_stock' => ! $esDigital,
                    'archivo_url'    => $esDigital ? 'archivos/' . str()->slug($p['nombre']) . '.pdf' : null,
                    'activo'         => true,
                ]
            );
        }

        // ---------------------------------------------------------------
        // 3) CLIENTES (40 con nombres bolivianos)
        // ---------------------------------------------------------------
        $nombres = ['Maria', 'Jose', 'Juan', 'Ana', 'Luis', 'Carla', 'Pedro', 'Lucia', 'Carlos', 'Daniela',
                    'Jorge', 'Gabriela', 'Fernando', 'Andrea', 'Marco', 'Valeria', 'Diego', 'Rosa', 'Roberto', 'Patricia',
                    'Sergio', 'Veronica', 'Alvaro', 'Monica', 'Pablo', 'Silvia'];
        $apellidos = ['Quispe', 'Mamani', 'Flores', 'Choque', 'Vargas', 'Condori', 'Rojas', 'Gutierrez', 'Torrez', 'Apaza',
                      'Villarroel', 'Camacho', 'Salazar', 'Mendoza', 'Fernandez', 'Cruz', 'Ticona', 'Aguilar', 'Cespedes', 'Calle'];
        $zonas = ['Av. America', 'Cala Cala', 'Sarco', 'Queru Queru', 'Sacaba', 'Tiquipaya', 'Quillacollo', 'Zona Central', 'Las Cuadras', 'Tupuraya'];

        $clientes = [];
        for ($i = 1; $i <= 40; $i++) {
            $nombre   = $nombres[array_rand($nombres)];
            $apellido = $apellidos[array_rand($apellidos)];

            $clientes[] = Cliente::create([
                'nombre'    => "$nombre $apellido",
                'email'     => strtolower($nombre . '.' . $apellido . $i) . '@gmail.com', // el $i lo hace unico
                'telefono'  => '+591 7' . rand(1000000, 9999999),
                'direccion' => $zonas[array_rand($zonas)] . ' #' . rand(100, 999) . ', Cochabamba',
            ]);
        }

        // ---------------------------------------------------------------
        // 4) PEDIDOS (~70) con sus items
        // ---------------------------------------------------------------
        $todosProductos = Producto::all();
        $estados   = ['pagado', 'pagado', 'entregado', 'entregado', 'entregado', 'enviado', 'pendiente', 'cancelado'];
        $metodos   = ['qr', 'efectivo', 'transferencia'];
        $canales   = ['tienda', 'tienda', 'tienda', 'admin'];

        for ($i = 0; $i < 70; $i++) {
            $cliente = $clientes[array_rand($clientes)];

            $pedido = Pedido::create([
                'cliente_id'  => $cliente->id,
                'total'       => 0, // lo calculamos despues de cargar los items
                'estado'      => $estados[array_rand($estados)],
                'canal'       => $canales[array_rand($canales)],
                'metodo_pago' => $metodos[array_rand($metodos)],
            ]);

            // Repartimos los pedidos en los ultimos 6 meses para que el dashboard tenga movimiento
            $pedido->created_at = now()->subDays(rand(0, 180))->subHours(rand(0, 23));
            $pedido->save();

            // Cada pedido lleva entre 1 y 3 productos distintos
            $cantidadItems = rand(1, 3);
            $total = 0;

            foreach ($todosProductos->random($cantidadItems) as $producto) {
                $cantidad = rand(1, 3);
                $precio   = $producto->precio;

                PedidoItem::create([
                    'pedido_id'       => $pedido->id,
                    'producto_id'     => $producto->id,
                    'variante_id'     => null,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precio,
                ]);

                $total += $cantidad * $precio;
            }

            $pedido->update(['total' => $total]);
        }

        // ---------------------------------------------------------------
        // 5) SEGUIMIENTOS (25) -> esto es el CRM
        // ---------------------------------------------------------------
        $tipos  = ['llamada', 'whatsapp', 'email', 'nota'];
        $notas  = [
            'Cliente consulto por nuevos productos.',
            'Se le envio recordatorio de reposicion.',
            'Quedo conforme con su ultima compra.',
            'Pidio informacion sobre planes de nutricion.',
            'Se agendo seguimiento post-venta.',
            'Cliente recomendo la tienda a un conocido.',
        ];
        $adminId = User::first()?->id; // el empleado que registra (tu usuario admin)

        for ($i = 0; $i < 25; $i++) {
            $cliente = $clientes[array_rand($clientes)];

            Seguimiento::create([
                'cliente_id' => $cliente->id,
                'usuario_id' => $adminId,
                'tipo'       => $tipos[array_rand($tipos)],
                'nota'       => $notas[array_rand($notas)],
            ]);
        }
    }
}
