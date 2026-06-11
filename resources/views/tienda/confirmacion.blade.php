@extends('layouts.app')

@section('titulo', 'VitaMind - Compra confirmada')

@section('styles')
<style>
    .confirmacion-main { max-width: 640px; margin: 0 auto; padding: 40px 24px 70px; }

    .exito-box {
        background: #e3f0e8; border: 1px solid #b8d8c4; border-radius: 12px;
        padding: 24px; text-align: center; margin-bottom: 22px;
    }
    .exito-box h1 { font-size: 26px; color: #1f6b3b; margin-bottom: 6px; }
    .exito-box p { color: #3a5444; font-size: 15px; }

    .bloque {
        background: #fff; border: 1px solid #e7e0d3; border-radius: 12px; padding: 18px; margin-bottom: 16px;
    }
    .bloque h2 { font-size: 16px; margin-bottom: 10px; color: #1f6b3b; }
    .bloque .dato { font-size: 14px; padding: 4px 0; }
    .bloque .dato strong { display: inline-block; min-width: 120px; color: #3a5444; }

    .fila { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; border-bottom: 1px solid #f0ece2; }
    .total { font-weight: bold; font-size: 18px; padding-top: 12px; }

    .volver {
        display: inline-block; background: #9ccc3c; color: #1f3d2b;
        padding: 12px 24px; border-radius: 8px; font-weight: bold; margin-top: 6px;
    }
</style>
@endsection

@section('content')

    <div class="confirmacion-main">
        <div class="exito-box">
            <h1>Gracias por tu compra!</h1>
            <p>Tu pedido #{{ $pedido->id }} fue registrado correctamente.</p>
        </div>

        <div class="bloque">
            <h2>Detalle del pedido</h2>
            <div class="dato"><strong>Cliente:</strong> {{ $pedido->cliente->nombre }}</div>
            <div class="dato"><strong>Email:</strong> {{ $pedido->cliente->email }}</div>
            <div class="dato"><strong>Estado:</strong> {{ ucfirst($pedido->estado) }}</div>
            <div class="dato"><strong>Metodo de pago:</strong> {{ ucfirst($pedido->metodo_pago) }}</div>
        </div>

        <div class="bloque">
            <h2>Productos</h2>
            @foreach ($pedido->items as $item)
                <div class="fila">
                    <span>{{ $item->producto->nombre }} x {{ $item->cantidad }}</span>
                    <span>Bs {{ number_format($item->precio_unitario * $item->cantidad, 2) }}</span>
                </div>
            @endforeach
            <div class="fila total">
                <span>Total</span>
                <span>Bs {{ number_format($pedido->total, 2) }}</span>
            </div>
        </div>

        <a href="{{ route('tienda.index') }}#productos" class="volver">Seguir comprando</a>
    </div>

@endsection
