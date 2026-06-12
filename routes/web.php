<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AsistenteController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PaginaController;

Route::get('/', function () {
    return redirect()->route('tienda.index');
});

// --- RUTA TEMPORAL DE DIAGNOSTICO DE CORREO (se quita despues) ---
Route::get('/diag-mail', function (\Illuminate\Http\Request $r) {
    if ($r->query('key') !== 'vitamind2026') {
        abort(404);
    }

    $info = [
        'mailer'       => config('mail.default'),
        'host'         => config('mail.mailers.smtp.host'),
        'port'         => config('mail.mailers.smtp.port'),
        'username'     => config('mail.mailers.smtp.username'),
        'password_set' => config('mail.mailers.smtp.password')
            ? 'si (len ' . strlen(config('mail.mailers.smtp.password')) . ')' : 'NO',
        'from'         => config('mail.from.address'),
    ];

    try {
        \Illuminate\Support\Facades\Mail::raw('Diagnostico de correo VitaMind (produccion).', function ($m) {
            $m->to('rafaelalbino100@gmail.com')->subject('VitaMind - Diagnostico');
        });
        $info['envio'] = 'OK: enviado sin excepcion';
    } catch (\Throwable $e) {
        $info['envio'] = 'ERROR: ' . get_class($e) . ' -> ' . $e->getMessage();
    }

    return response()->json($info, 200, [], JSON_PRETTY_PRINT);
});

Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda.index');
Route::get('/productos', [TiendaController::class, 'productos'])->name('tienda.productos');
Route::get('/producto/{producto}', [TiendaController::class, 'producto'])->name('tienda.producto');
Route::post('/carrito/agregar/{producto}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::get('/carrito', [CarritoController::class, 'ver'])->name('carrito.ver');
Route::post('/carrito/item/{clave}', [CarritoController::class, 'item'])->name('carrito.item');
Route::get('/checkout', [CheckoutController::class, 'mostrar'])->name('checkout.mostrar');
Route::post('/checkout', [CheckoutController::class, 'procesar'])->name('checkout.procesar');
Route::get('/checkout/confirmacion/{pedido}', [CheckoutController::class, 'confirmacion'])->name('checkout.confirmacion');
Route::get('/asistente', [AsistenteController::class, 'index'])->name('asistente.index');
Route::get('/acerca', [PaginaController::class, 'acerca'])->name('pagina.acerca');
Route::get('/contacto', [PaginaController::class, 'contacto'])->name('pagina.contacto');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{post:slug}/like', [BlogController::class, 'like'])->name('blog.like');