@extends('layouts.app')

@section('titulo', 'VitaMind - Acerca de')

@section('styles')
<style>
    .acerca-page { padding: 0 0 70px; }

    /* Animacion de aparicion al hacer scroll */
    .reveal { opacity: 0; transform: translateY(34px); transition: opacity .7s ease, transform .7s ease; }
    .reveal.visible { opacity: 1; transform: none; }

    /* HERO */
    .acerca-hero {
        display: grid; grid-template-columns: 1fr 1fr; gap: 56px; align-items: center;
        padding: 60px 5%; max-width: 1200px; margin: 0 auto;
    }
    .ah-eyebrow { color: #2fa15a; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; font-size: 14px; }
    .acerca-hero h1 { font-size: 56px; color: #1f4023; line-height: 1.1; margin: 12px 0 22px; font-weight: 800; }
    .acerca-hero p { color: #5a7a64; font-size: 18px; line-height: 1.65; }
    .ah-img { border-radius: 20px; overflow: hidden; aspect-ratio: 4/3; background: #eef3e9; }
    .ah-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .ah-img .ph { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #aebba6; }
    @media (max-width: 850px) { .acerca-hero { grid-template-columns: 1fr; gap: 28px; } .acerca-hero h1 { font-size: 40px; } }

    /* STATS con contador animado */
    .acerca-stats {
        background: #1f6b3b; color: #fff; padding: 50px 5%;
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; text-align: center;
    }
    .acerca-stats .stat .num { font-size: 50px; font-weight: 800; line-height: 1; display: block; }
    .acerca-stats .stat p { color: #d6e8dc; margin-top: 8px; font-size: 15px; }
    @media (max-width: 700px) { .acerca-stats { grid-template-columns: repeat(2, 1fr); row-gap: 34px; } }

    /* MISION / VISION / VALORES */
    .acerca-mv { max-width: 1200px; margin: 0 auto; padding: 70px 5%; }
    .acerca-mv .titulo { text-align: center; margin-bottom: 44px; }
    .acerca-mv .titulo h2 { font-size: 40px; color: #1f4023; font-weight: 800; margin-bottom: 10px; }
    .acerca-mv .titulo p { color: #5a7a64; font-size: 17px; }
    .mv-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
    @media (max-width: 850px) { .mv-grid { grid-template-columns: 1fr; } }
    .mv-card {
        background: #fffdf7; border: 1px solid #ece6d8; border-radius: 20px; padding: 34px 30px;
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .mv-card:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(31, 61, 43, 0.10); }
    .mv-icon {
        width: 60px; height: 60px; border-radius: 16px; background: #eaf4ea;
        display: flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 18px;
    }
    .mv-card h3 { font-size: 24px; color: #1f6b3b; margin-bottom: 12px; font-weight: 800; }
    .mv-card p { color: #5a7a64; font-size: 15px; line-height: 1.65; }

    /* VALORES (lista) */
    .acerca-valores { max-width: 1000px; margin: 0 auto; padding: 0 5% 20px; }
    .acerca-valores h2 { font-size: 34px; color: #1f4023; font-weight: 800; margin-bottom: 26px; text-align: center; }
    .valores-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
    @media (max-width: 700px) { .valores-grid { grid-template-columns: 1fr; } }
    .valor { display: flex; gap: 14px; align-items: flex-start; background: #f3f7ee; border-radius: 14px; padding: 20px 22px; }
    .valor .check { color: #2fa15a; font-size: 22px; line-height: 1; flex-shrink: 0; }
    .valor h4 { color: #1f3d2b; font-size: 17px; margin-bottom: 4px; }
    .valor p { color: #5a7a64; font-size: 14px; line-height: 1.55; }

    /* CTA final */
    .acerca-cta { text-align: center; padding: 64px 5% 20px; max-width: 760px; margin: 0 auto; }
    .acerca-cta h2 { font-size: 36px; color: #1f4023; font-weight: 800; margin-bottom: 14px; }
    .acerca-cta p { color: #5a7a64; font-size: 17px; line-height: 1.6; margin-bottom: 26px; }
</style>
@endsection

@section('content')

    <div class="acerca-page">

        {{-- HERO --}}
        <section class="acerca-hero">
            <div class="ah-text reveal">
                <span class="ah-eyebrow">Acerca de nosotros</span>
                <h1>Nutrici&oacute;n inteligente para tu mejor versi&oacute;n</h1>
                <p>En VitaMind creemos que comer bien no tiene que ser complicado. Combinamos productos saludables seleccionados con tecnolog&iacute;a de inteligencia artificial para ayudarte a alcanzar tus objetivos de energ&iacute;a, foco y bienestar, de forma simple y personalizada.</p>
            </div>
            <div class="ah-img reveal">
                @if (file_exists(public_path('img/acerca-vitamind.jpg')))
                    <img src="{{ asset('img/acerca-vitamind.jpg') }}" alt="Equipo VitaMind">
                @elseif (file_exists(public_path('img/imagen-asistente.png')))
                    <img src="{{ asset('img/imagen-asistente.png') }}" alt="VitaMind">
                @else
                    <div class="ph">Imagen (agrega img/acerca-vitamind.jpg)</div>
                @endif
            </div>
        </section>

        {{-- STATS animadas --}}
        <section class="acerca-stats">
            <div class="stat reveal"><span class="num contador" data-target="500">0</span><p>Clientes felices</p></div>
            <div class="stat reveal"><span class="num contador" data-target="50">0</span><p>Productos saludables</p></div>
            <div class="stat reveal"><span class="num contador" data-target="3">0</span><p>A&ntilde;os acompa&ntilde;&aacute;ndote</p></div>
            <div class="stat reveal"><span class="num contador" data-target="100" data-suffix="%">0</span><p>Natural</p></div>
        </section>

        {{-- MISION / VISION / VALORES --}}
        <section class="acerca-mv">
            <div class="titulo reveal">
                <h2>Lo que nos mueve</h2>
                <p>Nuestra raz&oacute;n de ser y hacia d&oacute;nde vamos.</p>
            </div>
            <div class="mv-grid">
                <div class="mv-card reveal">
                    <div class="mv-icon">&#127919;</div>
                    <h3>Misi&oacute;n</h3>
                    <p>Hacer accesible la alimentaci&oacute;n consciente, ofreciendo productos saludables y un asistente inteligente que gu&iacute;e a cada persona hacia su mejor versi&oacute;n.</p>
                </div>
                <div class="mv-card reveal">
                    <div class="mv-icon">&#127793;</div>
                    <h3>Visi&oacute;n</h3>
                    <p>Ser la plataforma de bienestar y nutrici&oacute;n inteligente de referencia en Bolivia, transformando la forma en que los j&oacute;venes adultos cuidan su salud.</p>
                </div>
                <div class="mv-card reveal">
                    <div class="mv-icon">&#128154;</div>
                    <h3>Valores</h3>
                    <p>Salud real, transparencia, innovaci&oacute;n y cercan&iacute;a. Cada decisi&oacute;n que tomamos pone tu bienestar en el centro.</p>
                </div>
            </div>
        </section>

        {{-- VALORES en detalle --}}
        <section class="acerca-valores">
            <h2 class="reveal">Nuestros valores</h2>
            <div class="valores-grid">
                <div class="valor reveal">
                    <span class="check">&#10003;</span>
                    <div><h4>Salud real</h4><p>Productos seleccionados con criterio, sin promesas vac&iacute;as.</p></div>
                </div>
                <div class="valor reveal">
                    <span class="check">&#10003;</span>
                    <div><h4>Transparencia</h4><p>Informaci&oacute;n clara sobre lo que consum&iacute;s y por qu&eacute;.</p></div>
                </div>
                <div class="valor reveal">
                    <span class="check">&#10003;</span>
                    <div><h4>Innovaci&oacute;n</h4><p>Usamos IA para darte recomendaciones realmente personalizadas.</p></div>
                </div>
                <div class="valor reveal">
                    <span class="check">&#10003;</span>
                    <div><h4>Cercan&iacute;a</h4><p>Te acompa&ntilde;amos en cada paso de tu camino hacia el bienestar.</p></div>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="acerca-cta reveal">
            <h2>&iquest;List@ para empezar?</h2>
            <p>Conta tu objetivo y dej&aacute; que nuestro asistente con IA te recomiende los productos ideales para vos.</p>
            <a href="{{ route('asistente.index') }}" class="btn">Probar el asistente</a>
        </section>

    </div>

@endsection

@section('scripts')
<script>
    (function () {
        // Aparicion al hacer scroll
        var reveals = document.querySelectorAll('.reveal');
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    io.unobserve(e.target);
                    // Si tiene contadores adentro, los animamos
                    e.target.querySelectorAll('.contador').forEach(animarContador);
                }
            });
        }, { threshold: 0.15 });
        reveals.forEach(function (r) { io.observe(r); });

        // Contador animado
        function animarContador(el) {
            var destino = parseInt(el.dataset.target, 10) || 0;
            var sufijo = el.dataset.suffix || '';
            var inicio = 0;
            var duracion = 1400;
            var t0 = null;
            function paso(ts) {
                if (!t0) t0 = ts;
                var p = Math.min((ts - t0) / duracion, 1);
                var valor = Math.floor(inicio + (destino - inicio) * (1 - Math.pow(1 - p, 3)));
                el.textContent = valor + sufijo;
                if (p < 1) requestAnimationFrame(paso);
                else el.textContent = destino + sufijo;
            }
            requestAnimationFrame(paso);
        }
    })();
</script>
@endsection
