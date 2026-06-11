@extends('layouts.app')

@section('titulo', 'VitaMind - Finalizar compra')

@section('styles')
<style>
    .checkout-main { max-width: 720px; margin: 0 auto; padding: 40px 24px 70px; }
    .checkout-main h1 { font-size: 30px; margin-bottom: 20px; color: #1f6b3b; }
    .checkout-main h2 { font-size: 18px; margin: 22px 0 12px; color: #1f3d2b; }

    .resumen {
        background: #fff; border: 1px solid #e7e0d3; border-radius: 12px; padding: 18px; margin-bottom: 10px;
    }
    .resumen .fila { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; }
    .resumen .total { border-top: 1px solid #e7e0d3; margin-top: 8px; padding-top: 10px; font-weight: bold; font-size: 18px; }

    .errores {
        background: #f7e0da; color: #8a2d1a; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px;
    }
    .errores ul { margin-left: 18px; }

    .checkout-main form .campo { margin-bottom: 14px; }
    .checkout-main form label { display: block; font-size: 13px; margin-bottom: 5px; color: #3a5444; }
    .checkout-main form input, .checkout-main form select {
        width: 100%; border: 1px solid #d8d0c0; border-radius: 8px; padding: 10px; font-size: 14px;
    }
    .checkout-main form button {
        background: #1f6b3b; color: #fff; border: none; border-radius: 8px;
        padding: 14px 24px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 6px;
    }
</style>
@endsection

@section('content')

    <div class="checkout-main">
        <h1>Finalizar compra</h1>

        <h2>Resumen del pedido</h2>
        <div class="resumen">
            @foreach ($carrito as $item)
                <div class="fila">
                    <span>{{ $item['nombre'] }}@if (!empty($item['variante_nombre'])) ({{ $item['variante_nombre'] }})@endif x {{ $item['cantidad'] }}</span>
                    <span>Bs {{ number_format($item['precio'] * $item['cantidad'], 2) }}</span>
                </div>
            @endforeach
            <div class="fila total">
                <span>Total</span>
                <span>Bs {{ number_format($total, 2) }}</span>
            </div>
        </div>

        <h2>Tus datos</h2>

        @if ($errors->any())
            <div class="errores">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.procesar') }}">
            @csrf

            <div class="campo">
                <label>Nombre completo</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}">
            </div>

            <div class="campo">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}">
            </div>

            <div class="campo">
                <label>Telefono (opcional)</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}">
            </div>

            <div class="campo">
                <label>Direccion (opcional)</label>
                <input type="text" name="direccion" value="{{ old('direccion') }}">
            </div>

            <div class="campo">
                <label>Metodo de pago</label>
                <select name="metodo_pago">
                    <option value="qr">QR</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="efectivo">Efectivo</option>
                </select>
            </div>

            <button type="submit">Confirmar compra</button>
        </form>
    </div>

@endsection
