@extends('layouts.app')

@section('titulo', 'VitaMind - Asistente')

@section('styles')
<style>
    .asistente-main { max-width: 1000px; margin: 0 auto; padding: 40px 24px 70px; }
    .asistente-main h1 { font-size: 30px; margin-bottom: 6px; color: #1f6b3b; }
    .asistente-main .sub { color: #5a7a64; margin-bottom: 22px; }

    .buscador {
        background: #fff; border: 1px solid #e7e0d3; border-radius: 12px; padding: 20px; margin-bottom: 26px;
    }
    .buscador form { display: flex; gap: 10px; }
    .buscador input {
        flex: 1; border: 1px solid #d8d0c0; border-radius: 8px; padding: 12px; font-size: 15px;
    }
    .buscador button {
        background: #1f6b3b; color: #fff; border: none; border-radius: 8px;
        padding: 12px 22px; font-size: 15px; font-weight: bold; cursor: pointer;
    }
    .ejemplos { font-size: 13px; color: #8a9a8f; margin-top: 10px; }

    .fuente {
        display: inline-block; font-size: 12px; padding: 4px 12px; border-radius: 12px;
        margin-bottom: 14px; font-weight: bold;
    }
    .fuente.ia { background: #dde9f7; color: #1f4f86; }
    .fuente.reglas { background: #f1ece0; color: #7a6a44; }

    .mensaje {
        background: #e3f0e8; color: #1f6b3b; padding: 14px 16px; border-radius: 8px;
        margin-bottom: 14px; font-size: 15px; line-height: 1.5;
    }

    .asistente-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 24px; }
    .asistente-grid .card {
        background: #fff; border: 1px solid #ece6d8; border-radius: 16px; overflow: hidden;
        display: flex; flex-direction: column; transition: transform .25s ease, box-shadow .25s ease;
    }
    .asistente-grid .card:hover { transform: translateY(-5px); box-shadow: 0 16px 36px rgba(31, 61, 43, 0.12); }
    .asistente-grid .card .card-img { display: block; height: 180px; background: #f3f1ea; overflow: hidden; }
    .asistente-grid .card .card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s ease; }
    .asistente-grid .card:hover .card-img img { transform: scale(1.05); }
    .asistente-grid .card .card-cont { padding: 16px 18px 18px; display: flex; flex-direction: column; flex: 1; }
    .asistente-grid .card .badge { font-size: 11px; text-transform: uppercase; color: #1f6b3b; margin-bottom: 6px; letter-spacing: .5px; }
    .asistente-grid .card h3 { font-size: 16px; margin-bottom: 6px; color: #1f3d2b; line-height: 1.3; }
    .asistente-grid .card h3 a:hover { color: #1f6b3b; }
    .asistente-grid .card .precio { font-size: 19px; font-weight: bold; margin: 4px 0 14px; color: #1f3d2b; }
    .asistente-grid .card form { margin-top: auto; display: flex; gap: 8px; }
    .asistente-grid .card .qty { width: 54px; border: 1px solid #d8d0c0; border-radius: 8px; padding: 8px; text-align: center; }
    .asistente-grid .card .btn-add {
        flex: 1; background: #9ccc3c; color: #1f3d2b; border: none; border-radius: 8px;
        padding: 10px; font-weight: bold; cursor: pointer; transition: background .2s ease;
    }
    .asistente-grid .card .btn-add:hover { background: #8fc02f; }
</style>
@endsection

@section('content')

    <div class="asistente-main">
        <h1>Asistente de bienestar</h1>
        <p class="sub">Conta tu objetivo y te recomendamos los productos ideales para vos.</p>

        <div class="buscador">
            <form method="GET" action="{{ route('asistente.index') }}">
                <input type="text" name="consulta" value="{{ $consulta }}"
                       placeholder="Ej: quiero mas energia para entrenar">
                <button type="submit">Recomendar</button>
            </form>
            <p class="ejemplos">Proba con: "bajar de peso", "ganar musculo", "recetas saludables", "subir defensas".</p>
        </div>

        @if ($consulta)
            @if ($fuente === 'ia')
                <div class="fuente ia">Recomendado por IA (Gemini)</div>
            @elseif ($fuente === 'reglas')
                <div class="fuente reglas">Recomendado por reglas del sistema</div>
            @endif

            @foreach ($mensajes as $mensaje)
                <div class="mensaje">{{ $mensaje }}</div>
            @endforeach

            <div class="asistente-grid">
                @foreach ($recomendaciones as $producto)
                    <div class="card">
                        <a href="{{ route('tienda.producto', $producto) }}" class="card-img">
                            @if ($producto->imagen)
                                <img src="{{ asset('img/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
                            @endif
                        </a>
                        <div class="card-cont">
                            <div class="badge">{{ $producto->categoria->nombre }}</div>
                            <h3><a href="{{ route('tienda.producto', $producto) }}">{{ $producto->nombre }}</a></h3>
                            <div class="precio">Bs {{ number_format($producto->precio, 2) }}</div>

                            <form action="{{ route('carrito.agregar', $producto) }}" method="POST">
                                @csrf
                                <input type="number" name="cantidad" value="1" min="1" class="qty">
                                <button type="button" class="btn-add" onclick="return agregarAlCarrito(this.form)">Agregar</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
