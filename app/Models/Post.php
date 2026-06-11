<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'titulo', 'slug', 'autor', 'imagen', 'extracto',
        'contenido', 'tiempo_lectura', 'vistas', 'likes', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
