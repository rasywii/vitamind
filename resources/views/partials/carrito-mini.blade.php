@if (count($carrito) === 0)
    <p class="mc-vacio">Tu carrito esta vac&iacute;o.</p>
@else
    <div class="mc-items">
        @foreach ($carrito as $item)
            <div class="mc-item">
                <div class="mc-img">
                    @if (!empty($item['imagen']))
                        <img src="{{ asset('img/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}">
                    @endif
                </div>
                <div class="mc-info">
                    <div class="mc-nombre">{{ $item['nombre'] }}</div>
                    <div class="mc-precio">Bs {{ number_format($item['precio'], 2) }}</div>
                    @if (!empty($item['variante_nombre']))
                        <div class="mc-variante">Capacidad: {{ $item['variante_nombre'] }}</div>
                    @endif
                    <div class="mc-cant">Cantidad: {{ $item['cantidad'] }}</div>
                </div>
                <div class="mc-subtotal">Bs {{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>
            </div>
        @endforeach
    </div>

    <div class="mc-total">
        <span>Total estimado</span>
        <span>Bs {{ number_format($total, 2) }}</span>
    </div>
    <p class="mc-nota">Los impuestos y costos de env&iacute;o se calculan en la p&aacute;gina de pago.</p>
@endif
