<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Listado de todas las entradas del blog
    public function index()
    {
        $posts = Post::where('activo', true)
            ->orderByDesc('created_at')
            ->get();

        // Posts que este usuario ya marco con like en esta sesion
        $liked = session()->get('posts_liked', []);

        return view('blog.index', compact('posts', 'liked'));
    }

    // Detalle de una entrada
    public function show(Post $post)
    {
        // Sumamos una visita SOLO si no se vio antes en esta sesion (evita inflar al refrescar)
        $vistos = session()->get('posts_vistos', []);
        if (! in_array($post->id, $vistos)) {
            $post->increment('vistas');
            $vistos[] = $post->id;
            session()->put('posts_vistos', $vistos);
        }

        // Si este usuario ya le dio like en esta sesion
        $yaLike = in_array($post->id, session()->get('posts_liked', []));

        // Entradas recientes (otras, para el bloque del final)
        $recientes = Post::where('activo', true)
            ->where('id', '!=', $post->id)
            ->orderByDesc('created_at')
            ->limit(2)
            ->get();

        return view('blog.show', compact('post', 'recientes', 'yaLike'));
    }

    // Like / unlike de una entrada (una vez por sesion)
    public function like(Post $post)
    {
        $liked = session()->get('posts_liked', []);

        if (in_array($post->id, $liked)) {
            // Ya tenia like -> lo quitamos
            $post->decrement('likes');
            $liked  = array_values(array_diff($liked, [$post->id]));
            $estado = false;
        } else {
            // No tenia -> sumamos like
            $post->increment('likes');
            $liked[] = $post->id;
            $estado  = true;
        }

        session()->put('posts_liked', $liked);

        return response()->json([
            'likes' => $post->fresh()->likes,
            'liked' => $estado,
        ]);
    }
}
