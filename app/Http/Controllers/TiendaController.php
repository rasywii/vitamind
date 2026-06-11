<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;

class TiendaController extends Controller
{
    // Muestra el catalogo publico de la tienda
    public function index()
    {
        $productos = Producto::where('activo', true)
            ->with('categoria')
            ->orderBy('nombre')
            ->get();

        // Tambien traemos las categorias para la seccion "Nuestras categorias"
        $categorias = Categoria::orderBy('nombre')->get();

        return view('tienda.index', compact('productos', 'categorias'));
    }

    // Catalogo completo con filtros (categoria, precio) y ordenamiento
    public function productos(\Illuminate\Http\Request $request)
    {
        $categorias = Categoria::orderBy('nombre')->get();

        $query = Producto::where('activo', true)->with('categoria');

        // Filtro por categoria
        if ($request->filled('cat')) {
            $query->where('categoria_id', $request->input('cat'));
        }

        // Filtro por precio
        if ($request->filled('min')) {
            $query->where('precio', '>=', (float) $request->input('min'));
        }
        if ($request->filled('max')) {
            $query->where('precio', '<=', (float) $request->input('max'));
        }

        // Ordenamiento
        switch ($request->input('sort')) {
            case 'precio_asc':  $query->orderBy('precio', 'asc');  break;
            case 'precio_desc': $query->orderBy('precio', 'desc'); break;
            case 'nombre':      $query->orderBy('nombre', 'asc');  break;
            default:            $query->orderBy('id', 'asc');      break; // Recomendados
        }

        $productos = $query->get();

        // Rango real de precios para el filtro
        $precioMin = (int) floor(Producto::where('activo', true)->min('precio') ?? 0);
        $precioMax = (int) ceil(Producto::where('activo', true)->max('precio') ?? 0);

        return view('tienda.productos', compact('categorias', 'productos', 'precioMin', 'precioMax'));
    }

    // Muestra la pagina de detalle de un producto
    public function producto(Producto $producto)
    {
        $producto->load('categoria', 'variantes');

        return view('tienda.producto', compact('producto'));
    }
}
