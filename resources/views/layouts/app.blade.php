<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'VitaMind')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background: #fdf8f0; color: #1f3d2b; }
        a { text-decoration: none; color: inherit; }

        /* ---------- HEADER (items centrados) ---------- */
        .site-header {
            background: #D6EB5F; padding: 16px 5%;
            display: flex; align-items: center; justify-content: space-between; gap: 24px;
            position: sticky; top: 0; z-index: 10; border-bottom: 1px solid #c3d94f;
            transition: padding .28s ease, background .28s ease, box-shadow .28s ease;
        }
        /* Navbar dinamico: al hacer scroll se encoge y gana sombra */
        .site-header.scrolled {
            padding: 8px 5%; box-shadow: 0 4px 18px rgba(31, 61, 43, 0.12);
            background: #d0e858;
        }
        .site-header .logo { flex-shrink: 0; }
        .site-header .logo img { height: 56px; display: block; transition: height .28s ease; }
        .site-header.scrolled .logo img { height: 44px; }
        .site-header .logo .logo-text { font-size: 22px; font-weight: bold; color: #1f6b3b; }
        .site-header .nav-principal { display: flex; align-items: center; justify-content: center; gap: 36px; font-size: 16px; flex: 1; }
        .site-header .nav-acciones { display: flex; align-items: center; gap: 22px; flex-shrink: 0; }

        .nav-link { position: relative; color: #ffffff; padding-bottom: 5px; transition: color .25s ease; }
        .nav-link::after {
            content: ''; position: absolute; left: 0; bottom: 0; height: 2px; width: 100%;
            background: #1f6b3b; transform: scaleX(0); transform-origin: center; transition: transform .28s ease;
        }
        .nav-link:hover { color: #1f6b3b; }
        .nav-link:hover::after { transform: scaleX(1); }
        .nav-link.active { color: #1f6b3b; }
        .nav-link.active::after { transform: scaleX(1); }

        .btn-asistente {
            display: inline-flex; align-items: center; gap: 12px;
            background: linear-gradient(90deg, #ff9d00, #ffc400 55%, #ffe24d);
            color: #FFFF; padding: 6px 6px 6px 22px; border-radius: 30px; border: none;
            font-weight: bold; font-size: 15px; white-space: nowrap;
            box-shadow: 0 4px 14px rgba(255, 176, 0, 0.45);
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
        }
        .btn-asistente:hover, .btn-asistente.active {
            transform: translateY(-2px); filter: brightness(1.05);
            box-shadow: 0 7px 22px rgba(255, 176, 0, 0.55);
        }
        .btn-asistente .ba-icon {
            width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
            background: rgba(90, 55, 0, 0.28); color: #fff;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .btn-asistente .ba-icon svg {
            width: 18px; height: 18px;
            transition: transform .4s ease;
        }
        /* Animacion dinamica al pasar el mouse: las chispas giran y brillan */
        .btn-asistente:hover .ba-icon svg { transform: rotate(90deg) scale(1.12); }
        .btn-asistente:hover .ba-icon { animation: chispear .7s ease; }
        @keyframes chispear {
            0%   { transform: scale(1); }
            40%  { transform: scale(1.18); }
            70%  { transform: scale(0.96); }
            100% { transform: scale(1); }
        }
        .cart-ico {
            position: relative; display: inline-flex; align-items: center; justify-content: center;
            color: #1f3d2b; padding: 4px; transition: transform .2s ease, color .2s ease;
        }
        .cart-ico .cart-bag { height: 38px; width: auto; display: block; }
        .cart-ico .cart-count { fill: #fff; font-size: 42px; font-weight: 700; }
        .cart-ico:hover { transform: translateY(-2px); color: #1f6b3b; }
        .cart-ico.active { color: #1f6b3b; }

        /* ---------- BOTONES ---------- */
        .btn {
            display: inline-block; background: #D6EB5F; color: #1f3d2b; border: 1.5px solid #b9d23f;
            border-radius: 8px; padding: 12px 26px; font-size: 15px; font-weight: bold; cursor: pointer;
            transition: background .25s ease;
        }
        .btn:hover { background: #c8e04c; }
        .btn-secundario {
            display: inline-block; background: #dff0f7; color: #1f3d2b; border: 1.5px solid #c5e6f2;
            border-radius: 8px; padding: 12px 26px; font-size: 15px; font-weight: bold; transition: background .25s ease;
        }
        .btn-secundario:hover { background: #cfe9f2; }

        /* ---------- SECCIONES (ancho completo) ---------- */
        main { min-height: 50vh; }
        .seccion { padding: 56px 5%; }
        .seccion h2 { font-size: 50px; color: #1f6b3b; margin-bottom: 16px; }
        .seccion-sub { color: #5a7a64; margin-bottom: 28px; max-width: 640px; }

        /* ---------- HERO (tarjeta con borde redondeado) ---------- */
        .hero {
            margin: 30px 5%; padding: 54px 50px; border: 1px solid #e7e0d3; border-radius: 26px;
            background: #fffdf7; display: flex; gap: 44px; align-items: center; min-height: 70vh;
        }
        .hero-text { flex: 1; }
        .hero-text h1 { font-size: 92px; color: #1f6b3b; line-height: 1.1; margin-bottom: 18px; }
        .hero-text p { color: #5a7a64; margin-bottom: 26px; line-height: 1.55; font-size: 17px; max-width: 520px; }
        .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }
        .hero-img { flex: 1; align-self: stretch; min-height: 340px; max-height: 460px; background: #e3f0e8; border-radius: 18px; overflow: hidden; }
        .hero-img picture { display: block; width: 100%; height: 100%; }
        .hero-img img { width: 100%; height: 100%; object-fit: cover; display: block; }

        /* ---------- CATEGORIAS ---------- */
        .grid-cat { display: grid; grid-template-columns: repeat(3, 1fr); gap: 34px; }
        @media (max-width: 900px) { .grid-cat { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px) { .grid-cat { grid-template-columns: 1fr; } }
        .cat-card {
            background: #fbf9f0; border: none; border-radius: 26px; padding: 18px;
            box-shadow: 0 10px 30px rgba(31, 61, 43, 0.06);
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .cat-card:hover { transform: translateY(-6px); box-shadow: 0 18px 44px rgba(31, 61, 43, 0.13); }
        .cat-card .cat-img { height: 230px; background: #f1f6ec; border-radius: 18px; margin-bottom: 20px; overflow: hidden; }
        .cat-card .cat-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .cat-card h3 { font-size: 26px; color: #1f6b3b; margin-bottom: 12px; line-height: 1.15; font-weight: 800; padding: 0 6px; }
        .cat-card p { font-size: 15px; color: #5a7a64; line-height: 1.55; padding: 0 6px 6px; }

        /* ---------- SECCION PRODUCTOS (estilo referencia) ---------- */
        .seccion-productos { text-align: center; }
        .seccion-productos > h2 { font-size: 56px; margin-bottom: 22px; }
        .seccion-productos > .seccion-sub {
            margin: 0 auto 48px; max-width: 780px; font-size: 20px; line-height: 1.5; text-align: center;
        }

        /* ---------- CARRUSEL DE PRODUCTOS ---------- */
        .carousel-wrap { display: flex; align-items: center; gap: 12px; }
        .carousel {
            display: flex; gap: 26px; overflow-x: auto; scroll-snap-type: x mandatory;
            padding: 6px 2px 16px; scroll-behavior: smooth; flex: 1;
            scrollbar-width: none; -ms-overflow-style: none;
        }
        .carousel::-webkit-scrollbar { display: none; }
        .carousel .card {
            min-width: 260px; max-width: 260px; scroll-snap-align: start;
            background: transparent; border: none; border-radius: 0; padding: 0;
            display: flex; flex-direction: column; text-align: left;
        }
        .carousel .card .card-img { display: block; height: 250px; background: #f3f1ea; border-radius: 18px; margin-bottom: 16px; overflow: hidden; }
        .carousel .card .card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s ease; }
        .carousel .card .card-img:hover img { transform: scale(1.05); }
        .carousel .card h3 { font-size: 16px; font-weight: 600; color: #1f3d2b; margin-bottom: 4px; }
        .carousel .card h3 a { color: inherit; }
        .carousel .card h3 a:hover { color: #1f6b3b; }
        .carousel .card .precio { font-size: 15px; font-weight: 400; color: #5a7a64; margin: 0 0 16px; }
        .carousel .card form { margin-top: auto; }
        .carousel .card .btn-reservar {
            display: block; width: 100%; background: #cde85a; color: #1f3d2b; border: none;
            border-radius: 10px; padding: 14px; font-size: 15px; font-weight: 700; cursor: pointer;
            transition: background .2s ease;
        }
        .carousel .card .btn-reservar:hover { background: #bedd45; }
        .car-btn {
            background: #fff; border: 1px solid #d8d0c0; border-radius: 50%; width: 46px; height: 46px;
            font-size: 22px; color: #1f6b3b; cursor: pointer; flex-shrink: 0; transition: background .2s ease;
            box-shadow: 0 4px 12px rgba(31, 61, 43, 0.08);
        }
        .car-btn:hover { background: #f1f6ec; }

        /* ---------- PILARES (4 objetivos) ---------- */
        .pilares { padding: 30px 5% 10px; }
        .grid-pilares {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 40px;
            border-top: 1px solid #e3ddcd; padding-top: 50px;
        }
        .pilar h3 { font-size: 30px; font-weight: 700; margin-bottom: 14px; line-height: 1.1; }
        .pilar p { color: #5a7a64; font-size: 16px; line-height: 1.5; }
        .pilar.energia h3     { color: #ef7a90; }
        .pilar.foco h3        { color: #f0a13c; }
        .pilar.consciencia h3 { color: #a6cf2e; }
        .pilar.fitness h3     { color: #87cfe8; }
        @media (max-width: 900px) { .grid-pilares { grid-template-columns: repeat(2, 1fr); row-gap: 34px; } }
        @media (max-width: 560px) { .grid-pilares { grid-template-columns: 1fr; } }

        /* ---------- PROMO ASISTENTE IA (texto + imagen) ---------- */
        .asistente-promo {
            display: grid; grid-template-columns: 1fr 1fr; gap: 56px;
            align-items: center; padding: 50px 5% 80px;
        }
        .asistente-promo .ap-text h2 {
            font-size: 58px; color: #1f4023; line-height: 1.1; margin-bottom: 24px; font-weight: 800;
        }
        .asistente-promo .ap-text p {
            color: #5a7a64; font-size: 18px; line-height: 1.6; margin-bottom: 30px; max-width: 520px;
        }
        .btn-promo { border-radius: 26px; padding: 14px 30px; font-size: 16px; }
        .asistente-promo .ap-img {
            border-radius: 18px; overflow: hidden; aspect-ratio: 4 / 3; background: #f1f6ec;
        }
        .asistente-promo .ap-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
        @media (max-width: 850px) { .asistente-promo { grid-template-columns: 1fr; gap: 28px; padding-bottom: 56px; } }

        /* ---------- DETALLE DE PRODUCTO ---------- */
        .producto-detalle {
            display: grid; grid-template-columns: 1fr 1fr; gap: 50px;
            padding: 40px 5% 70px; align-items: start;
        }
        .pd-imagen { background: #f3f1ea; border-radius: 16px; overflow: hidden; aspect-ratio: 1 / 1; }
        .pd-imagen img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .pd-volver { display: inline-block; margin-bottom: 14px; color: #5a7a64; font-size: 14px; }
        .pd-volver:hover { color: #1f6b3b; }
        .pd-info h1 { font-size: 34px; color: #1f3d2b; margin-bottom: 10px; line-height: 1.15; }
        .pd-precio { font-size: 22px; color: #5a7a64; margin-bottom: 28px; }
        .pd-campo { margin-bottom: 22px; }
        .pd-label { display: block; font-size: 14px; color: #1f3d2b; margin-bottom: 8px; font-weight: 600; }
        .pd-label span { color: #c0392b; }
        .pd-variantes { display: flex; gap: 10px; flex-wrap: wrap; }
        .pd-variante input { position: absolute; opacity: 0; pointer-events: none; }
        .pd-variante span {
            display: inline-block; border: 1px solid #cbd3c2; border-radius: 8px;
            padding: 9px 18px; font-size: 14px; cursor: pointer; transition: all .2s ease;
        }
        .pd-variante input:checked + span { border-color: #1f6b3b; background: #1f6b3b; color: #fff; }
        .pd-cantidad { display: inline-flex; align-items: center; border: 1px solid #cbd3c2; border-radius: 8px; overflow: hidden; }
        .pd-cantidad button {
            background: #fff; border: none; width: 42px; height: 44px; font-size: 18px; cursor: pointer; color: #1f3d2b;
        }
        .pd-cantidad button:hover { background: #f1f6ec; }
        .pd-cantidad input {
            width: 56px; height: 44px; text-align: center; border: none;
            border-left: 1px solid #e3e8dd; border-right: 1px solid #e3e8dd; font-size: 15px;
        }
        .pd-btn-agregar, .pd-btn-comprar {
            display: block; width: 100%; border: none; border-radius: 10px; padding: 15px;
            font-size: 15px; font-weight: 700; cursor: pointer; margin-top: 12px; transition: background .2s ease;
        }
        .pd-btn-agregar { background: #cde85a; color: #1f3d2b; }
        .pd-btn-agregar:hover { background: #bedd45; }
        .pd-btn-comprar { background: #1f4023; color: #fff; }
        .pd-btn-comprar:hover { background: #163018; }
        .pd-desc { margin-top: 26px; color: #5a7a64; line-height: 1.6; font-size: 15px; max-width: 520px; }
        .pd-share { display: flex; gap: 12px; margin-top: 24px; }
        .pd-share a {
            width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;
            color: #1f3d2b; transition: color .2s ease, transform .2s ease;
        }
        .pd-share a:hover { color: #1f6b3b; transform: translateY(-2px); }
        .pd-share svg { width: 20px; height: 20px; }
        @media (max-width: 800px) { .producto-detalle { grid-template-columns: 1fr; gap: 28px; } }

        /* ---------- FOOTER ---------- */
        .site-footer { margin-top: 40px; background: #efeeea; }
        .footer-grid {
            display: grid; grid-template-columns: 1.6fr 1fr 1.4fr 1fr; gap: 40px;
            padding: 60px 5% 44px;
        }
        .f-col h4 {
            text-transform: uppercase; letter-spacing: .6px; color: #1f3d2b;
            font-size: 16px; margin-bottom: 18px; font-weight: 600;
        }
        .f-col a, .f-col p { display: block; font-size: 15px; color: #3a5444; line-height: 1.95; }
        .f-col a { transition: color .2s ease; }
        .f-col a:hover { color: #1f6b3b; }
        .f-brand .f-logo { height: 56px; display: block; margin-bottom: 18px; }
        .f-brand .logo-text { font-size: 26px; font-weight: bold; color: #1f6b3b; display: block; margin-bottom: 14px; }
        .f-tagline { font-size: 21px; color: #1f4023; line-height: 1.4; max-width: 280px; font-weight: 500; }
        .f-social { display: flex; gap: 12px; }
        .f-social a {
            width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
            display: inline-flex; align-items: center; justify-content: center;
            transition: transform .2s ease, filter .2s ease;
        }
        .f-social a:hover { transform: translateY(-3px); filter: brightness(1.08); }
        .f-social svg { width: 20px; height: 20px; fill: #fff; }
        .f-social .fb { background: #1877F2; }
        .f-social .ig { background: radial-gradient(circle at 30% 110%, #fdf497 5%, #fd5949 45%, #d6249f 70%, #285AEB 100%); }
        .f-social .yt { background: #FF0000; }
        .f-social .tk { background: #010101; }
        .footer-copy {
            text-align: left; font-size: 13px; color: #8a9a8f;
            padding: 20px 5%; border-top: 1px solid #dcdbd4;
        }
        @media (max-width: 850px) { .footer-grid { grid-template-columns: 1fr 1fr; row-gap: 36px; } }
        @media (max-width: 500px) { .footer-grid { grid-template-columns: 1fr; } }

        /* ---------- CART DRAWER (vista previa) ---------- */
        .cart-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.4); opacity: 0; visibility: hidden; transition: opacity .3s ease; z-index: 40; }
        .cart-backdrop.open { opacity: 1; visibility: visible; }
        .cart-drawer {
            position: fixed; top: 0; right: 0; height: 100%; width: 420px; max-width: 92vw; background: #fff;
            z-index: 41; display: flex; flex-direction: column; transform: translateX(100%);
            transition: transform .32s ease; box-shadow: -8px 0 30px rgba(0,0,0,.12);
        }
        .cart-drawer.open { transform: none; }
        .cd-head { display: flex; align-items: center; justify-content: space-between; padding: 22px 24px; border-bottom: 1px solid #ece6d8; }
        .cd-head .cd-title { font-size: 20px; font-weight: 800; color: #1f3d2b; }
        .cd-head .cd-title span { color: #8a9a8f; font-weight: 500; font-size: 15px; }
        .cd-close { background: none; border: none; font-size: 24px; color: #5a7a64; cursor: pointer; line-height: 1; }
        .cd-body { flex: 1; overflow-y: auto; padding: 16px 24px; }
        .cd-foot { padding: 18px 24px 24px; border-top: 1px solid #ece6d8; }
        .cd-btn-primary { display: block; text-align: center; background: #cde85a; color: #1f3d2b; border-radius: 10px; padding: 15px; font-weight: 700; font-size: 15px; margin-bottom: 10px; transition: background .2s ease; }
        .cd-btn-primary:hover { background: #bedd45; }
        .cd-btn-secondary { display: block; text-align: center; border: 1.5px solid #cbd3c2; color: #1f6b3b; border-radius: 10px; padding: 13px; font-weight: 700; font-size: 15px; transition: background .2s ease; }
        .cd-btn-secondary:hover { background: #f3f7ee; }
        .mc-vacio { color: #5a7a64; text-align: center; padding: 30px 0; }
        .mc-item { display: flex; gap: 14px; padding: 16px 0; border-bottom: 1px solid #f0ece2; }
        .mc-item .mc-img { width: 72px; height: 72px; border-radius: 10px; background: #f3f1ea; overflow: hidden; flex-shrink: 0; }
        .mc-item .mc-img img { width: 100%; height: 100%; object-fit: cover; }
        .mc-info { flex: 1; }
        .mc-info .mc-nombre { font-weight: 600; color: #1f3d2b; font-size: 15px; }
        .mc-info .mc-precio { color: #5a7a64; font-size: 14px; margin-top: 2px; }
        .mc-info .mc-variante, .mc-info .mc-cant { color: #8a9a8f; font-size: 13px; margin-top: 2px; }
        .mc-subtotal { font-weight: 700; color: #1f3d2b; font-size: 15px; white-space: nowrap; }
        .mc-total { display: flex; justify-content: space-between; align-items: center; margin-top: 18px; font-size: 19px; font-weight: 800; color: #1f3d2b; }
        .mc-nota { color: #8a9a8f; font-size: 13px; margin-top: 8px; line-height: 1.4; }
    </style>
    @yield('styles')
</head>
<body>
    <header class="site-header">
        <a href="{{ route('tienda.index') }}" class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="VitaMind"
                 onerror="this.outerHTML='<span class=&quot;logo-text&quot;>VitaMind</span>'">
        </a>
        <nav class="nav-principal">
            <a href="{{ route('tienda.index') }}" class="nav-link {{ request()->routeIs('tienda.index') ? 'active' : '' }}">Inicio</a>
            <a href="{{ route('tienda.productos') }}" class="nav-link {{ request()->routeIs('tienda.productos') ? 'active' : '' }}">Productos</a>
            <a href="{{ route('pagina.acerca') }}" class="nav-link {{ request()->routeIs('pagina.acerca') ? 'active' : '' }}">Acerca de</a>
            <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a>
            <a href="{{ route('pagina.contacto') }}" class="nav-link {{ request()->routeIs('pagina.contacto') ? 'active' : '' }}">Contacto</a>
        </nav>
        <div class="nav-acciones">
            <a href="{{ route('asistente.index') }}" class="btn-asistente {{ request()->routeIs('asistente.index') ? 'active' : '' }}">
                <span class="ba-text">Asistente</span>
                <span class="ba-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 4.5a.75.75 0 0 1 .721.544l.813 2.846a3.75 3.75 0 0 0 2.576 2.576l2.846.813a.75.75 0 0 1 0 1.442l-2.846.813a3.75 3.75 0 0 0-2.576 2.576l-.813 2.846a.75.75 0 0 1-1.442 0l-.813-2.846a3.75 3.75 0 0 0-2.576-2.576l-2.846-.813a.75.75 0 0 1 0-1.442l2.846-.813A3.75 3.75 0 0 0 7.466 7.89l.813-2.846A.75.75 0 0 1 9 4.5ZM18 1.5a.75.75 0 0 1 .728.568l.258 1.036c.236.94.97 1.674 1.91 1.91l1.036.258a.75.75 0 0 1 0 1.456l-1.036.258c-.94.236-1.674.97-1.91 1.91l-.258 1.036a.75.75 0 0 1-1.456 0l-.258-1.036a2.625 2.625 0 0 0-1.91-1.91l-1.036-.258a.75.75 0 0 1 0-1.456l1.036-.258a2.625 2.625 0 0 0 1.91-1.91l.258-1.036A.75.75 0 0 1 18 1.5ZM16.5 15a.75.75 0 0 1 .712.513l.394 1.183c.15.447.5.799.948.948l1.183.395a.75.75 0 0 1 0 1.422l-1.183.395c-.447.15-.799.5-.948.948l-.395 1.183a.75.75 0 0 1-1.422 0l-.395-1.183a1.5 1.5 0 0 0-.948-.948l-1.183-.395a.75.75 0 0 1 0-1.422l1.183-.395c.447-.15.799-.5.948-.948l.395-1.183A.75.75 0 0 1 16.5 15Z"></path>
                    </svg>
                </span>
            </a>
            <a href="{{ route('carrito.ver') }}" class="cart-ico {{ request()->routeIs('carrito.ver') ? 'active' : '' }}" aria-label="Ver carrito" title="Carrito">
                <svg class="cart-bag" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 105.5 126.1" preserveAspectRatio="xMidYMid meet" fill="currentColor">
                    <path d="M102.143 118.16L93.812 48.2067C93.386 44.66 90.3566 42 86.7591 42H79.1382V56H74.4047V42H31.8032V56H27.0697V42H19.4488C15.8513 42 12.8219 44.66 12.3959 48.16L4.06489 118.16C3.78088 120.167 4.44357 122.173 5.76895 123.667C7.14167 125.16 9.0824 126 11.0705 126H95.1374C97.1255 126 99.0662 125.16 100.439 123.667C101.764 122.173 102.427 120.167 102.143 118.16Z"></path>
                    <path d="M32.0594 25.6667C32.0594 14.0933 41.506 4.66667 53.1039 4.66667C64.7018 4.66667 74.1485 14.0933 74.1485 25.6667V42H78.825V25.6667C78.825 11.5267 67.2739 0 53.1039 0C38.9339 0 27.3828 11.5267 27.3828 25.6667V42H32.0594V25.6667Z"></path>
                    <text x="53" y="85.5" dy=".35em" text-anchor="middle" class="cart-count" id="header-cart-count">{{ count(session('carrito', [])) }}</text>
                </svg>
            </a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer" id="contacto">
        <div class="footer-grid">
            <div class="f-col f-brand">
                <img src="{{ asset('img/logo.png') }}" alt="VitaMind" class="f-logo"
                     onerror="this.outerHTML='<span class=&quot;logo-text&quot;>VitaMind</span>'">
                <p class="f-tagline">Nutrici&oacute;n inteligente para una vida plena.</p>
            </div>

            <div class="f-col">
                <h4>Navegaci&oacute;n</h4>
                <a href="{{ route('tienda.index') }}">Inicio</a>
                <a href="{{ route('tienda.productos') }}">Productos</a>
                <a href="{{ route('pagina.acerca') }}">Acerca de</a>
                <a href="{{ route('blog.index') }}">Blog</a>
                <a href="{{ route('pagina.contacto') }}">Contacto</a>
            </div>

            <div class="f-col">
                <h4>Contacto</h4>
                <p>
                    vitamind.oficial@gmail.com<br>
                    +591 79417954<br>
                    Avenida Am&eacute;rica entre Calle Pando 495
                </p>
            </div>

            <div class="f-col">
                <h4>S&iacute;guenos</h4>
                <div class="f-social">
                    <a href="https://www.facebook.com/share/1LBkPQsd3H/" target="_blank" rel="noopener" class="fb" aria-label="Facebook">
                        <svg viewBox="0 0 24 24"><path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07c0 6.02 4.39 11.01 10.13 11.93v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.89v2.25h3.33l-.53 3.49h-2.8V24C19.61 23.08 24 18.09 24 12.07z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/vitamind.oficial?igsh=MWkzazBsdTVzNHpwYQ==" target="_blank" rel="noopener" class="ig" aria-label="Instagram">
                        <svg viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.43.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.43.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41-.56-.22-.96-.48-1.38-.9-.42-.42-.68-.82-.9-1.38-.16-.43-.36-1.06-.41-2.23C2.21 15.58 2.2 15.2 2.2 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.43-.16 1.06-.36 2.23-.41C8.42 2.21 8.8 2.2 12 2.2zm0 1.8c-3.14 0-3.5.01-4.74.07-.9.04-1.38.19-1.71.32-.43.17-.74.37-1.06.69-.32.32-.52.63-.69 1.06-.13.33-.28.81-.32 1.71-.06 1.24-.07 1.6-.07 4.74s.01 3.5.07 4.74c.04.9.19 1.38.32 1.71.17.43.37.74.69 1.06.32.32.63.52 1.06.69.33.13.81.28 1.71.32 1.24.06 1.6.07 4.74.07s3.5-.01 4.74-.07c.9-.04 1.38-.19 1.71-.32.43-.17.74-.37 1.06-.69.32-.32.52-.63.69-1.06.13-.33.28-.81.32-1.71.06-1.24.07-1.6.07-4.74s-.01-3.5-.07-4.74c-.04-.9-.19-1.38-.32-1.71-.17-.43-.37-.74-.69-1.06a2.86 2.86 0 0 0-1.06-.69c-.33-.13-.81-.28-1.71-.32C15.5 4.01 15.14 4 12 4zm0 3.06A4.94 4.94 0 1 0 12 16.94 4.94 4.94 0 0 0 12 7.06zm0 8.14A3.2 3.2 0 1 1 12 8.8a3.2 3.2 0 0 1 0 6.4zm6.29-8.36a1.15 1.15 0 1 1-2.3 0 1.15 1.15 0 0 1 2.3 0z"/></svg>
                    </a>
                    <a href="https://youtube.com/@vitamindoficial?si=FB-hLvbGK0cKjsYx" target="_blank" rel="noopener" class="yt" aria-label="YouTube">
                        <svg viewBox="0 0 24 24"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8zM9.6 15.6V8.4l6.2 3.6-6.2 3.6z"/></svg>
                    </a>
                    <a href="https://www.tiktok.com/@vitamind.oficial?_r=1&_t=ZS-96VO9N7Bfb0" target="_blank" rel="noopener" class="tk" aria-label="TikTok">
                        <svg viewBox="0 0 24 24"><path d="M16.6 5.82a4.28 4.28 0 0 1-1.04-2.82h-3.1v12.4a2.59 2.59 0 0 1-2.59 2.5 2.59 2.59 0 0 1 0-5.18c.27 0 .53.05.77.12v-3.16a5.7 5.7 0 0 0-.77-.05A5.73 5.73 0 0 0 4.14 15.4a5.73 5.73 0 0 0 9.9 3.9 5.7 5.7 0 0 0 1.56-3.9V9.01a7.35 7.35 0 0 0 4.4 1.4V7.3a4.28 4.28 0 0 1-3.4-1.48z"/></svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-copy">© 2026 VitaMind. Todos los derechos reservados.</div>
    </footer>

    {{-- Drawer / vista previa del carrito --}}
    <div class="cart-backdrop" id="cart-backdrop" onclick="cerrarDrawer()"></div>
    <aside class="cart-drawer" id="cart-drawer" aria-hidden="true">
        <div class="cd-head">
            <span class="cd-title">Carrito <span id="cd-count">(0 &iacute;tems)</span></span>
            <button class="cd-close" onclick="cerrarDrawer()" aria-label="Cerrar">&times;</button>
        </div>
        <div class="cd-body" id="cd-body"></div>
        <div class="cd-foot">
            <a href="{{ route('checkout.mostrar') }}" class="cd-btn-primary">Finalizar compra</a>
            <a href="{{ route('carrito.ver') }}" class="cd-btn-secondary">Ver carrito</a>
        </div>
    </aside>

    {{-- Logica del drawer del carrito --}}
    <script>
        function abrirDrawer() {
            document.getElementById('cart-drawer').classList.add('open');
            document.getElementById('cart-backdrop').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function cerrarDrawer() {
            document.getElementById('cart-drawer').classList.remove('open');
            document.getElementById('cart-backdrop').classList.remove('open');
            document.body.style.overflow = '';
        }
        // Agrega al carrito por AJAX y abre la vista previa
        function agregarAlCarrito(form) {
            var token = document.querySelector('meta[name=csrf-token]').content;
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                body: new FormData(form)
            })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                document.getElementById('cd-body').innerHTML = d.html;
                document.getElementById('cd-count').textContent = '(' + d.count + (d.count === 1 ? ' ítem' : ' ítems') + ')';
                var hc = document.getElementById('header-cart-count');
                if (hc) hc.textContent = d.count;
                abrirDrawer();
            })
            .catch(function () { form.submit(); });
            return false;
        }
    </script>

    {{-- Navbar dinamico: agrega la clase .scrolled cuando se baja la pagina --}}
    <script>
        (function () {
            var header = document.querySelector('.site-header');
            function onScroll() {
                if (window.scrollY > 30) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            }
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        })();
    </script>

    @yield('scripts')
</body>
</html>
