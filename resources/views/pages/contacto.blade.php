@extends('layouts.app')

@section('titulo', 'VitaMind - Contacto')

@section('styles')
<style>
    .contacto-page { padding: 40px 5% 70px; max-width: 1200px; margin: 0 auto; }

    .contacto-titulo {
        background: #fffdf7; border: 1px solid #ece6d8; border-radius: 22px;
        padding: 44px 48px; margin-bottom: 30px;
    }
    .contacto-titulo h1 { font-size: 64px; color: #1f4023; font-weight: 800; line-height: 1; }

    .contacto-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start; }
    @media (max-width: 850px) { .contacto-grid { grid-template-columns: 1fr; } }

    .c-card { background: #fffdf7; border: 1px solid #ece6d8; border-radius: 22px; padding: 28px; }

    .local-foto { height: 300px; border-radius: 16px; overflow: hidden; background: #ede7da; margin-bottom: 26px; }
    .local-foto img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .local-foto .ph { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #b6ac98; font-size: 15px; }

    .c-card h2 { font-size: 30px; color: #1f4023; font-weight: 800; margin-bottom: 20px; }

    .linea {
        display: flex; justify-content: space-between; align-items: center;
        border: 1px solid #ece6d8; border-radius: 10px; padding: 16px 18px; margin-bottom: 12px; font-size: 16px;
    }
    .linea:last-child { margin-bottom: 0; }
    .linea .dia { color: #1f3d2b; }
    .linea .hora { color: #3a5444; font-weight: 600; }

    .info-linea {
        background: #f3f7ee; border-radius: 10px; padding: 16px 18px; margin-bottom: 12px;
        font-size: 16px; color: #1f3d2b; word-break: break-word;
    }
    .info-linea:last-child { margin-bottom: 0; }
    .info-linea a:hover { color: #1f6b3b; }

    .mapa { margin-top: 22px; border-radius: 16px; overflow: hidden; line-height: 0; }
    .mapa iframe { width: 100%; height: 320px; border: 0; display: block; }
</style>
@endsection

@section('content')

    <div class="contacto-page">

        <div class="contacto-titulo">
            <h1>Vis&iacute;tanos</h1>
        </div>

        <div class="contacto-grid">

            {{-- Columna izquierda: foto + horario --}}
            <div class="c-card">
                <div class="local-foto">
                    @if (file_exists(public_path('img/local-vitamind.avif')))
                        <img src="{{ asset('img/local-vitamind.avif') }}" alt="Local VitaMind">
                    @else
                        <div class="ph">Foto del local (agrega img/local-vitamind.avif)</div>
                    @endif
                </div>

                <h2>Horario de atenci&oacute;n</h2>
                <div class="linea"><span class="dia">Lun - Vie</span><span class="hora">9 am &ndash; 5 pm</span></div>
                <div class="linea"><span class="dia">Sab</span><span class="hora">9 am &ndash; 2 pm</span></div>
                <div class="linea"><span class="dia">Dom</span><span class="hora">10 am &ndash; 3 pm</span></div>
            </div>

            {{-- Columna derecha: informacion + mapa --}}
            <div class="c-card">
                <h2>Informaci&oacute;n</h2>
                <div class="info-linea">Avenida Am&eacute;rica entre Calle Pando 425</div>
                <div class="info-linea"><a href="mailto:vitamind.oficial@gmail.com">vitamind.oficial@gmail.com</a></div>
                <div class="info-linea"><a href="tel:+59179417954">+591 79417954</a></div>

                <div class="mapa">
                    <iframe
                        src="https://www.google.com/maps?q=Avenida%20America%20entre%20Calle%20Pando%20Cochabamba%20Bolivia&output=embed"
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Ubicaci&oacute;n VitaMind"></iframe>
                </div>
            </div>

        </div>
    </div>

@endsection
