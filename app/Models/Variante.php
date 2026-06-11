<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Variante extends Model
{
    protected $fillable = ['producto_id', 'nombre', 'precio_extra', 'stock'];

    protected $casts = [
        'precio_extra' => 'decimal:2',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
