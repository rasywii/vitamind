@extends('layouts.app')

@section('titulo', 'VitaMind - Tienda')

@php
    $descripciones = [
        'Snacks Saludables'   => 'Comidas energeticas y balanceadas para mantener tu energia todo el dia.',
        'Bebidas Funcionales' => 'Infusiones y bebidas disenadas para apoyar tu salud mental y fisica.',
        'Suplementos'         => 'Vitaminas y suplementos para potenciar tu bienestar.',
        'Accesorios'          => 'Productos fitness para acompanar tu rutina saludable.',
        'Recetas Digitales'   => 'Guias paso a paso para cocinar con intencion y bienestar.',
    ];
@endphp

@section('content')

    {{-- HERO (tarjeta con borde redondeado) --}}
    <section class="hero">
        <div class="hero-text">
            <h1>Transforma tu vida con VitaMind</h1>
            <p>Alcanza tu maximo potencial con productos saludables, suplementos y guias disenadas para elevar tu energia y bienestar diario.</p>
            <div class="hero-actions">
                <a href="#productos" class="btn">Ver productos</a>
                <a href="{{ route('asistente.index') }}" class="btn-secundario">Probar asistente</a>
            </div>
        </div>
        <div class="hero-img">
            <picture>
                <source srcset="{{ asset('img/hero-tienda.webp') }}" type="image/webp">
                <img src="{{ asset('img/hero-tienda.png') }}" alt="VitaMind - Tienda" loading="eager" fetchpriority="high">
            </picture>
        </div>
    </section>

    {{-- CATEGORIAS --}}
    <section class="seccion">
        <h2>Nuestras categorias</h2>
        <p class="seccion-sub">Explora soluciones disenadas para tu bienestar integral y nutricion consciente.</p>
        <div class="grid-cat">
            @foreach ($categorias as $cat)
                <div class="cat-card">
                    <div class="cat-img">
                        @if ($cat->imagen)
                            <img src="{{ asset('img/' . $cat->imagen) }}" alt="{{ $cat->nombre }}">
                        @endif
                    </div>
                    <h3>{{ $cat->nombre }}</h3>
                    <p>{{ $descripciones[$cat->nombre] ?? 'Productos seleccionados para tu bienestar.' }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- PRODUCTOS EN CARRUSEL --}}
    <section class="seccion seccion-productos" id="productos">
        <h2>Servicios de Nutrici&oacute;n Vitamind</h2>
        <p class="seccion-sub">Soluciones personalizadas para potenciar tu energ&iacute;a, productividad y bienestar a trav&eacute;s de una alimentaci&oacute;n consciente y equilibrada.</p>
        <div class="carousel-wrap">
            <button class="car-btn" onclick="scrollCar(-1)" aria-label="Anterior">&lsaquo;</button>
            <div class="carousel" id="carousel">
                @foreach ($productos as $producto)
                    <div class="card">
                        <a href="{{ route('tienda.producto', $producto) }}" class="card-img">
                            @if ($producto->imagen)
                                <img src="{{ asset('img/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
                            @endif
                        </a>
                        <h3><a href="{{ route('tienda.producto', $producto) }}">{{ $producto->nombre }}</a></h3>
                        <div class="precio">Bs {{ number_format($producto->precio, 2) }}</div>
                        <form action="{{ route('carrito.agregar', $producto) }}" method="POST">
                            @csrf
                            <button type="button" class="btn-reservar" onclick="return agregarAlCarrito(this.form)">Reservar Ahora</button>
                        </form>
                    </div>
                @endforeach
            </div>
            <button class="car-btn" onclick="scrollCar(1)" aria-label="Siguiente">&rsaquo;</button>
        </div>
    </section>

    {{-- PILARES (4 objetivos con titulos de colores) --}}
    <section class="pilares">
        <div class="grid-pilares">
            <div class="pilar energia">
                <h3>Energ&iacute;a</h3>
                <p>Optimiza tu vitalidad diaria con alimentos que nutren de verdad.</p>
            </div>
            <div class="pilar foco">
                <h3>Foco</h3>
                <p>Mejora tu productividad con una nutrici&oacute;n dise&ntilde;ada para el cerebro.</p>
            </div>
            <div class="pilar consciencia">
                <h3>Consciencia</h3>
                <p>Aprende a escuchar a tu cuerpo y comer con plena intenci&oacute;n.</p>
            </div>
            <div class="pilar fitness">
                <h3>Fitness</h3>
                <p>Alcanza tus objetivos f&iacute;sicos con el combustible adecuado.</p>
            </div>
        </div>
    </section>

    {{-- PROMO ASISTENTE IA (texto + imagen) --}}
    <section class="asistente-promo" id="acerca">
        <div class="ap-text">
            <h2>Tu Asistente de Nutrici&oacute;n con IA</h2>
            <p>Conta tu objetivo y nuestra inteligencia artificial analiza todo el catalogo para recomendarte los productos ideales para vos. Energia, foco, fitness o bienestar: una guia personalizada en segundos.</p>
            <a href="{{ route('asistente.index') }}" class="btn btn-promo">Probar el asistente</a>
        </div>
        <div class="ap-img">
            <img src="{{ asset('img/imagen-asistente.png') }}" alt="Asistente de nutricion VitaMind">
        </div>
    </section>

@endsection

@section('scripts')
<script>
    var carousel = document.getElementById('carousel');
    // Paso = ancho de una tarjeta + el gap entre tarjetas
    var paso = 286;

    function scrollCar(dir) {
        carousel.scrollBy({ left: dir * paso, behavior: 'smooth' });
    }

    // Carrusel automatico: avanza solo y vuelve al inicio al llegar al final
    (function () {
        if (!carousel) return;
        var enPausa = false;

        // Se pausa cuando el mouse esta encima (para que puedas mirar tranquilo)
        carousel.addEventListener('mouseenter', function () { enPausa = true; });
        carousel.addEventListener('mouseleave', function () { enPausa = false; });

        setInterval(function () {
            if (enPausa) return;
            var finReal = carousel.scrollWidth - carousel.clientWidth;
            if (carousel.scrollLeft >= finReal - 5) {
                carousel.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                carousel.scrollBy({ left: paso, behavior: 'smooth' });
            }
        }, 2800);
    })();
</script>
@endsection

