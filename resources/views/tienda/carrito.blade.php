@extends('layouts.app')

@section('titulo', 'VitaMind - Carrito')

@section('styles')
<style>
    .cart-page { max-width: 1150px; margin: 0 auto; padding: 40px 5% 70px; }
    .cart-cols { display: grid; grid-template-columns: 1fr 360px; gap: 50px; align-items: start; }
    @media (max-width: 900px) { .cart-cols { grid-template-columns: 1fr; gap: 30px; } }

    .cart-items-col h1 { font-size: 30px; color: #1f3d2b; font-weight: 700; margin-bottom: 8px; }
    .cart-items-col .linea-top { border-bottom: 1px solid #e3ddcd; margin-bottom: 8px; }

    .aviso { background: #e3f0e8; color: #1f6b3b; padding: 12px 16px; border-radius: 8px; margin: 16px 0; font-size: 14px; }
    .vacio { color: #5a7a64; font-size: 16px; padding: 24px 0; }
    .vacio a { color: #1f6b3b; font-weight: bold; }

    .cart-item { display: flex; align-items: center; gap: 18px; padding: 24px 0; border-bottom: 1px solid #e3ddcd; }
    .ci-img { width: 110px; height: 110px; border-radius: 12px; background: #f3f1ea; overflow: hidden; flex-shrink: 0; }
    .ci-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .ci-info { flex: 1; min-width: 0; }
    .ci-nombre { font-size: 17px; color: #1f3d2b; font-weight: 600; margin-bottom: 4px; }
    .ci-nombre a:hover { color: #1f6b3b; }
    .ci-precio { color: #5a7a64; font-size: 14px; }
    .ci-variante { color: #8a9a8f; font-size: 14px; margin-top: 4px; }

    .ci-qty { display: inline-flex; align-items: center; border: 1px solid #cbd3c2; border-radius: 8px; overflow: hidden; flex-shrink: 0; }
    .ci-qty form { display: flex; margin: 0; }
    .ci-qty button { background: #fff; border: none; width: 36px; height: 40px; font-size: 17px; color: #1f3d2b; cursor: pointer; }
    .ci-qty button:hover { background: #f1f6ec; }
    .ci-qty .num { min-width: 40px; text-align: center; font-size: 15px; color: #1f3d2b; border-left: 1px solid #e3e8dd; border-right: 1px solid #e3e8dd; line-height: 40px; }

    .ci-subtotal { font-weight: 700; color: #1f3d2b; font-size: 16px; min-width: 80px; text-align: right; flex-shrink: 0; }
    .ci-del { background: none; border: none; cursor: pointer; color: #b6ac98; padding: 6px; flex-shrink: 0; transition: color .2s ease; }
    .ci-del:hover { color: #c0392b; }
    .ci-del svg { width: 20px; height: 20px; }

    /* Resumen del pedido */
    .cart-summary { position: sticky; top: 110px; }
    .cart-summary h2 { font-size: 26px; color: #1f3d2b; font-weight: 700; margin-bottom: 8px; }
    .cart-summary .linea-top { border-bottom: 1px solid #e3ddcd; margin-bottom: 22px; }
    .cs-row { display: flex; justify-content: space-between; align-items: center; font-size: 16px; color: #1f3d2b; margin-bottom: 14px; }
    .cs-row .gratis { color: #1f6b3b; font-weight: 700; }
    .cs-pais { color: #5a7a64; font-size: 14px; border-bottom: 1px solid #e3ddcd; padding-bottom: 22px; margin-bottom: 22px; }
    .cs-pais u { cursor: default; }
    .cs-total { display: flex; justify-content: space-between; align-items: center; font-size: 24px; font-weight: 800; color: #1f3d2b; margin-bottom: 22px; }
    .cs-btn { display: block; width: 100%; text-align: center; background: #1f4023; color: #fff; border-radius: 10px; padding: 17px; font-weight: 700; font-size: 16px; transition: background .2s ease; }
    .cs-btn:hover { background: #163018; }
    .cs-seguro { display: flex; align-items: center; justify-content: center; gap: 8px; color: #5a7a64; font-size: 14px; margin-top: 16px; }
    .cs-seguro svg { width: 16px; height: 16px; }
</style>
@endsection

@section('content')

    <div class="cart-page">

        @if (count($carrito) === 0)
            <div class="cart-items-col">
                <h1>Mi carrito</h1>
                <p class="vacio">Tu carrito esta vac&iacute;o. <a href="{{ route('tienda.productos') }}">Ver productos</a>.</p>
            </div>
        @else
            <div class="cart-cols">

                {{-- Items --}}
                <div class="cart-items-col">
                    <h1>Mi carrito</h1>
                    <div class="linea-top"></div>

                    @if (session('exito'))
                        <div class="aviso">{{ session('exito') }}</div>
                    @endif

                    @foreach ($carrito as $clave => $item)
                        <div class="cart-item">
                            <a href="{{ route('tienda.producto', $item['producto_id']) }}" class="ci-img">
                                @if (!empty($item['imagen']))
                                    <img src="{{ asset('img/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}">
                                @endif
                            </a>

                            <div class="ci-info">
                                <div class="ci-nombre"><a href="{{ route('tienda.producto', $item['producto_id']) }}">{{ $item['nombre'] }}</a></div>
                                <div class="ci-precio">Bs {{ number_format($item['precio'], 2) }}</div>
                                @if (!empty($item['variante_nombre']))
                                    <div class="ci-variante">Capacidad: {{ $item['variante_nombre'] }}</div>
                                @endif
                            </div>

                            <div class="ci-qty">
                                <form action="{{ route('carrito.item', $clave) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cantidad" value="{{ $item['cantidad'] - 1 }}">
                                    <button type="submit" name="accion" value="actualizar" aria-label="Restar">&minus;</button>
                                </form>
                                <span class="num">{{ $item['cantidad'] }}</span>
                                <form action="{{ route('carrito.item', $clave) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cantidad" value="{{ $item['cantidad'] + 1 }}">
                                    <button type="submit" name="accion" value="actualizar" aria-label="Sumar">+</button>
                                </form>
                            </div>

                            <div class="ci-subtotal">Bs {{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>

                            <form action="{{ route('carrito.item', $clave) }}" method="POST">
                                @csrf
                                <button type="submit" name="accion" value="quitar" class="ci-del" aria-label="Quitar">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                {{-- Resumen del pedido --}}
                <aside class="cart-summary">
                    <h2>Resumen del pedido</h2>
                    <div class="linea-top"></div>

                    <div class="cs-row"><span>Subtotal</span><span>Bs {{ number_format($total, 2) }}</span></div>
                    <div class="cs-row"><span>Env&iacute;o</span><span class="gratis">GRATIS</span></div>
                    <div class="cs-pais"><u>Bolivia</u></div>

                    <div class="cs-total"><span>Total</span><span>Bs {{ number_format($total, 2) }}</span></div>

                    <a href="{{ route('checkout.mostrar') }}" class="cs-btn">Finalizar compra</a>
                    <div class="cs-seguro">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Pago seguro
                    </div>
                </aside>

            </div>
        @endif
    </div>

@endsection
