@extends('layouts.app')

@section('titulo', $producto->nombre . ' - VitaMind')

@section('content')

    <section class="producto-detalle">

        {{-- IMAGEN --}}
        <div class="pd-imagen">
            @if ($producto->imagen)
                <img src="{{ asset('img/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
            @endif
        </div>

        {{-- INFO --}}
        <div class="pd-info">
            <a href="{{ route('tienda.index') }}#productos" class="pd-volver">&larr; Volver a la tienda</a>

            <h1>{{ $producto->nombre }}</h1>
            <div class="pd-precio" id="pd-precio" data-base="{{ $producto->precio }}">
                Bs {{ number_format($producto->precio, 2) }}
            </div>

            <form action="{{ route('carrito.agregar', $producto) }}" method="POST" class="pd-form">
                @csrf

                {{-- Variantes (ej: Capacidad 400 ml / 600 ml) --}}
                @if ($producto->variantes->count())
                    <div class="pd-campo">
                        <label class="pd-label">Capacidad <span>*</span></label>
                        <div class="pd-variantes">
                            @foreach ($producto->variantes as $i => $v)
                                <label class="pd-variante">
                                    <input type="radio" name="variante_id" value="{{ $v->id }}"
                                           data-extra="{{ $v->precio_extra }}" {{ $i === 0 ? 'checked' : '' }}>
                                    <span>{{ $v->nombre }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Cantidad --}}
                <div class="pd-campo">
                    <label class="pd-label">Cantidad <span>*</span></label>
                    <div class="pd-cantidad">
                        <button type="button" onclick="cambiarCant(-1)" aria-label="Restar">&minus;</button>
                        <input type="number" name="cantidad" id="pd-cant" value="1" min="1">
                        <button type="button" onclick="cambiarCant(1)" aria-label="Sumar">+</button>
                    </div>
                </div>

                <button type="button" class="pd-btn-agregar" onclick="return agregarAlCarrito(this.form)">Agregar al carrito</button>
                <button type="submit" name="comprar" value="1" class="pd-btn-comprar">Realizar compra</button>
            </form>

            @if ($producto->descripcion)
                <p class="pd-desc">{{ $producto->descripcion }}</p>
            @endif

            {{-- Compartir --}}
            <div class="pd-share">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" aria-label="Compartir en Facebook">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.7l-.4 2.9h-2.3v7A10 10 0 0 0 22 12z"/></svg>
                </a>
                <a href="https://wa.me/?text={{ urlencode($producto->nombre . ' - ' . url()->current()) }}" target="_blank" rel="noopener" aria-label="Compartir en WhatsApp">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M.06 24l1.68-6.16a11.9 11.9 0 1 1 10.34 5.97A11.9 11.9 0 0 1 6.1 22.3L.06 24zM6.4 20.13l.37.22a9.9 9.9 0 0 0 5.04 1.38 9.88 9.88 0 1 0-8.37-4.6l.24.38-1 3.65 3.72-1.03zM17.5 14.3c-.07-.12-.26-.2-.55-.34s-1.7-.84-1.96-.94-.46-.14-.65.14-.74.94-.9 1.13-.34.2-.62.07a8.1 8.1 0 0 1-2.39-1.47 9 9 0 0 1-1.65-2.06c-.17-.3 0-.46.13-.6l.42-.48c.14-.17.18-.29.28-.48s.05-.36-.02-.5-.65-1.57-.9-2.15c-.23-.56-.47-.48-.65-.49l-.55-.01a1.06 1.06 0 0 0-.77.36 3.23 3.23 0 0 0-1 2.4 5.6 5.6 0 0 0 1.17 2.98c.14.19 2 3.05 4.85 4.28a16.3 16.3 0 0 0 1.62.6 3.9 3.9 0 0 0 1.79.11c.55-.08 1.7-.69 1.93-1.36s.24-1.24.17-1.36z"/></svg>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($producto->nombre) }}" target="_blank" rel="noopener" aria-label="Compartir en X">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.65l-5.21-6.82-5.96 6.82H1.69l7.73-8.84L1.27 2.25h6.82l4.71 6.23 5.44-6.23zm-1.16 17.52h1.83L7.01 4.13H5.04l12.04 15.64z"/></svg>
                </a>
            </div>
        </div>

    </section>

@endsection

@section('scripts')
<script>
    // Selector de cantidad (no baja de 1)
    function cambiarCant(delta) {
        var input = document.getElementById('pd-cant');
        var valor = parseInt(input.value || '1', 10) + delta;
        input.value = valor < 1 ? 1 : valor;
    }

    // Actualiza el precio mostrado segun la variante elegida
    (function () {
        var precioEl = document.getElementById('pd-precio');
        var base = parseFloat(precioEl.dataset.base || '0');
        var radios = document.querySelectorAll('input[name=variante_id]');

        function actualizar() {
            var extra = 0;
            radios.forEach(function (r) { if (r.checked) extra = parseFloat(r.dataset.extra || '0'); });
            precioEl.textContent = 'Bs ' + (base + extra).toFixed(2);
        }

        radios.forEach(function (r) { r.addEventListener('change', actualizar); });
        if (radios.length) actualizar();
    })();
</script>
@endsection
