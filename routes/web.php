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