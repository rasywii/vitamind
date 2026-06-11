<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    // Agrega un producto al carrito (con la cantidad y variante elegidas)
    public function agregar(Request $request, Producto $producto)
    {
        $cantidad = max(1, (int) $request->input('cantidad', 1));

        // Variante opcional (ej: capacidad 400 ml / 600 ml)
        $precio         = $producto->precio;
        $varianteId     = null;
        $varianteNombre = null;

        if ($request->filled('variante_id')) {
            $variante = $producto->variantes()->find($request->input('variante_id'));
            if ($variante) {
                $precio        += $variante->precio_extra;
                $varianteId     = $variante->id;
                $varianteNombre = $variante->nombre;
            }
        }

        // Clave del carrito: producto + variante (asi cada variante es una linea aparte)
        $clave = $producto->id . ($varianteId ? '-' . $varianteId : '');

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$clave])) {
            // Si ya estaba, sumamos a la cantidad existente
            $carrito[$clave]['cantidad'] += $cantidad;
        } else {
            $carrito[$clave] = [
                'producto_id'     => $producto->id,
                'variante_id'     => $varianteId,
                'nombre'          => $producto->nombre,
                'variante_nombre' => $varianteNombre,
                'precio'          => $precio,
                'imagen'          => $producto->imagen,
                'cantidad'        => $cantidad,
            ];
        }

        session()->put('carrito', $carrito);

        // Respuesta para el drawer (vista previa) por AJAX
        if ($request->wantsJson()) {
            return $this->respuestaDrawer($carrito);
        }

        // "Realizar compra" lleva directo al checkout
        if ($request->filled('comprar')) {
            return redirect()->route('checkout.mostrar');
        }

        return redirect()->route('carrito.ver')->with('exito', 'Producto agregado al carrito.');
    }

    // Muestra el carrito con el total
    public function ver()
    {
        $carrito = $this->enriquecer(session()->get('carrito', []));
        $total   = $this->calcularTotal($carrito);

        return view('tienda.carrito', compact('carrito', 'total'));
    }

    // Sincroniza la imagen (y nombre) desde la base para todos los items.
    // Asi se corrigen tanto las imagenes faltantes como las guardadas en
    // sesion con un valor viejo/obsoleto que ya no existe.
    private function enriquecer(array $carrito): array
    {
        if (empty($carrito)) {
            return $carrito;
        }

        $ids = collect($carrito)->pluck('producto_id')->unique();
        $productos = Producto::whereIn('id', $ids)->get(['id', 'nombre', 'imagen'])->keyBy('id');

        $cambio = false;
        foreach ($carrito as $k => $i) {
            $prod = $productos->get($i['producto_id']);
            if (! $prod) {
                continue;
            }
            if (($i['imagen'] ?? null) !== $prod->imagen) {
                $carrito[$k]['imagen'] = $prod->imagen;
                $cambio = true;
            }
            if (empty($i['nombre']) && $prod->nombre) {
                $carrito[$k]['nombre'] = $prod->nombre;
                $cambio = true;
            }
        }

        if ($cambio) {
            session()->put('carrito', $carrito);
        }

        return $carrito;
    }

    // Respuesta JSON con el HTML del drawer
    private function respuestaDrawer(array $carrito)
    {
        $carrito = $this->enriquecer($carrito);
        $total   = $this->calcularTotal($carrito);

        return response()->json([
            'count' => count($carrito),
            'html'  => view('partials.carrito-mini', compact('carrito', 'total'))->render(),
        ]);
    }

    // Suma el total del carrito
    private function calcularTotal(array $carrito): float
    {
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        return $total;
    }

    // Actualiza la cantidad o quita una linea del carrito (segun el boton presionado)
    public function item(Request $request, string $clave)
    {
        $carrito = session()->get('carrito', []);
        $accion  = $request->input('accion');

        if ($accion === 'quitar') {
            unset($carrito[$clave]);
        } else {
            $cantidad = (int) $request->input('cantidad', 1);

            if ($cantidad > 0 && isset($carrito[$clave])) {
                $carrito[$clave]['cantidad'] = $cantidad;
            } else {
                // Cantidad 0 o menos = quitar
                unset($carrito[$clave]);
            }
        }

        session()->put('carrito', $carrito);

        // Respuesta para el drawer (steppers / papelera sin recargar)
        if ($request->wantsJson()) {
            return $this->respuestaDrawer($carrito);
        }

        return redirect()->route('carrito.ver');
    }
}
