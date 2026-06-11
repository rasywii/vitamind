<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AsistenteController extends Controller
{
    public function index(Request $request)
    {
        $consulta        = $request->input('consulta');
        $recomendaciones = collect();
        $mensajes        = [];
        $fuente          = null;

        if ($consulta) {
            // Primero intentamos con la IA (Gemini)
            $resultado = $this->recomendarConIA($consulta);

            if ($resultado) {
                $fuente = 'ia';
            } else {
                // Si la IA falla o no hay clave, usamos las reglas (red de seguridad)
                $resultado = $this->recomendarPorReglas($consulta);
                $fuente    = 'reglas';
            }

            $mensajes        = $resultado['mensajes'];
            $recomendaciones = $resultado['recomendaciones'];
        }

        return view('tienda.asistente', compact('consulta', 'recomendaciones', 'mensajes', 'fuente'));
    }

    // ---------- Recomendacion con IA (Gemini) ----------
    private function recomendarConIA(string $consulta): ?array
    {
        $apiKey = config('services.gemini.key');

        if (! $apiKey) {
            return null; // sin clave -> usamos reglas
        }

        $productos = Producto::where('activo', true)->with('categoria')->get();

        // Armamos el catalogo como texto para pasarselo a la IA
        $catalogo = $productos->map(function ($p) {
            return "ID {$p->id}: {$p->nombre} (categoria: {$p->categoria->nombre}, Bs {$p->precio}, tipo: {$p->tipo})";
        })->implode("\n");

        $prompt = "Sos el asistente de bienestar de VitaMind, una tienda de productos saludables en Bolivia. "
            . "Un cliente escribio su objetivo: \"{$consulta}\".\n\n"
            . "Productos disponibles:\n{$catalogo}\n\n"
            . "Recomenda entre 2 y 4 productos del catalogo que mejor ayuden a ese objetivo. "
            . "Responde en espanol con tono cercano (voseo). "
            . "Devolve SOLO un JSON con esta forma: "
            . '{"mensaje": "consejo breve de 2 o 3 frases", "ids": [lista de IDs recomendados]}. '
            . "Usa unicamente IDs que esten en la lista. No inventes productos ni IDs.";

        try {
            $modelo = 'gemini-2.5-flash';

            $respuesta = Http::timeout(20)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$modelo}:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]],
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                    ],
                ]
            );

            if (! $respuesta->successful()) {
                return null;
            }

            // Sacamos el texto que devolvio la IA
            $texto = $respuesta->json('candidates.0.content.parts.0.text');

            if (! $texto) {
                return null;
            }

            $datos = json_decode($texto, true);

            if (! is_array($datos) || empty($datos['ids'])) {
                return null;
            }

            // Buscamos en la base los productos que la IA recomendo (asi los precios son reales)
            $recomendaciones = Producto::where('activo', true)
                ->whereIn('id', $datos['ids'])
                ->with('categoria')
                ->get();

            if ($recomendaciones->isEmpty()) {
                return null;
            }

            return [
                'mensajes'        => [$datos['mensaje'] ?? 'Te recomendamos estos productos:'],
                'recomendaciones' => $recomendaciones,
            ];
        } catch (\Throwable $e) {
            return null; // cualquier error -> caemos en reglas
        }
    }

    // ---------- Recomendacion por reglas (respaldo) ----------
    private function recomendarPorReglas(string $consulta): array
    {
        $objetivos = [
            'energia' => [
                'palabras'   => ['energia', 'cansancio', 'cansado', 'animo', 'vitalidad', 'rendimiento'],
                'categorias' => ['Bebidas Funcionales', 'Suplementos'],
                'mensaje'    => 'Para subir tu energia, te recomendamos bebidas funcionales y suplementos.',
            ],
            'peso' => [
                'palabras'   => ['peso', 'adelgazar', 'bajar', 'dieta', 'grasa', 'figura'],
                'categorias' => ['Snacks Saludables', 'Recetas Digitales'],
                'mensaje'    => 'Para cuidar tu peso, snacks saludables y guias de cocina te ayudan a comer mejor.',
            ],
            'musculo' => [
                'palabras'   => ['musculo', 'masa', 'proteina', 'gimnasio', 'fuerza', 'entrenar'],
                'categorias' => ['Suplementos', 'Accesorios'],
                'mensaje'    => 'Para ganar masa muscular, los suplementos y accesorios fitness son ideales.',
            ],
            'cocina' => [
                'palabras'   => ['receta', 'recetas', 'cocinar', 'cocina', 'comida', 'menu', 'planificar'],
                'categorias' => ['Recetas Digitales'],
                'mensaje'    => 'Si queres cocinar mas sano, nuestras guias digitales son para vos.',
            ],
            'salud' => [
                'palabras'   => ['salud', 'inmune', 'defensas', 'bienestar', 'vitaminas', 'general'],
                'categorias' => ['Suplementos', 'Bebidas Funcionales'],
                'mensaje'    => 'Para tu bienestar general, suplementos y bebidas funcionales son una buena base.',
            ],
        ];

        $texto = mb_strtolower($consulta);
        $texto = strtr($texto, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n']);

        $categoriasMatch = [];
        $mensajes        = [];

        foreach ($objetivos as $obj) {
            foreach ($obj['palabras'] as $palabra) {
                if (str_contains($texto, $palabra)) {
                    $categoriasMatch = array_merge($categoriasMatch, $obj['categorias']);
                    $mensajes[]      = $obj['mensaje'];
                    break;
                }
            }
        }

        $categoriasMatch = array_unique($categoriasMatch);

        if (count($categoriasMatch) > 0) {
            $recomendaciones = Producto::where('activo', true)
                ->whereHas('categoria', fn ($q) => $q->whereIn('nombre', $categoriasMatch))
                ->with('categoria')
                ->limit(6)
                ->get();
        } else {
            $mensajes[]      = 'No identificamos un objetivo claro, pero aqui tienes algunas opciones populares.';
            $recomendaciones = Producto::where('activo', true)
                ->with('categoria')
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        return ['mensajes' => $mensajes, 'recomendaciones' => $recomendaciones];
    }
}
