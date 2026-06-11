@extends('layouts.app')

@section('titulo', 'VitaMind - Todos los productos')

@section('styles')
<style>
    .productos-page { padding: 24px 5% 70px; }

    .breadcrumb { font-size: 14px; color: #5a7a64; margin-bottom: 18px; }
    .breadcrumb a:hover { color: #1f6b3b; }
    .breadcrumb .sep { margin: 0 8px; color: #b6c2b6; }
    .breadcrumb .actual { color: #1f3d2b; }

    .pp-cover { width: 100%; height: 300px; border-radius: 14px; overflow: hidden; background: #eef0ec; margin-bottom: 30px; }
    .pp-cover img { width: 100%; height: 100%; object-fit: cover; display: block; }

    .pp-title { font-size: 40px; color: #1f4023; margin-bottom: 30px; font-weight: 800; }

    .productos-layout { display: grid; grid-template-columns: 240px 1fr; gap: 44px; align-items: start; }

    /* Sidebar de filtros */
    .filtros .fgrupo { padding: 20px 0; border-bottom: 1px solid #e3ddcd; }
    .filtros .fgrupo:first-child { padding-top: 0; }
    .filtros h4 { font-size: 17px; color: #1f3d2b; margin-bottom: 14px; font-weight: 700; }
    .filtros .opt {
        display: flex; align-items: center; gap: 10px; padding: 6px 0;
        font-size: 15px; color: #3a5444; cursor: pointer; transition: color .15s ease;
    }
    .filtros .opt:hover { color: #1f6b3b; }
    .filtros .opt input { accent-color: #1f6b3b; width: 16px; height: 16px; cursor: pointer; }
    .filtros .opt.activo { color: #1f6b3b; font-weight: 600; }
    .precio-rango { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
    .precio-rango input { width: 72px; border: 1px solid #cbd3c2; border-radius: 8px; padding: 8px; font-size: 14px; }
    .precio-rango span { color: #8a9a8f; }
    .btn-aplicar {
        background: #1f6b3b; color: #fff; border: none; border-radius: 8px;
        padding: 9px 18px; font-size: 14px; font-weight: bold; cursor: pointer;
    }
    .btn-aplicar:hover { background: #185530; }
    .limpiar { display: inline-block; margin-top: 10px; font-size: 13px; color: #8a9a8f; }
    .limpiar:hover { color: #1f6b3b; }

    /* Zona de productos */
    .productos-topbar {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .productos-topbar .conteo { font-size: 15px; color: #5a7a64; }
    .productos-topbar .orden { font-size: 14px; color: #3a5444; display: flex; align-items: center; gap: 6px; }
    .productos-topbar select {
        border: none; background: transparent; font-size: 14px; color: #1f6b3b; font-weight: 600; cursor: pointer;
    }

    .productos-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
    .pcard { display: block; color: inherit; }
    .pcard .pimg { height: 240px; background: #f3f1ea; border-radius: 14px; overflow: hidden; margin-bottom: 12px; }
    .pcard .pimg img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s ease; }
    .pcard:hover .pimg img { transform: scale(1.05); }
    .pcard h3 { font-size: 16px; font-weight: 600; color: #1f3d2b; margin-bottom: 4px; }
    .pcard:hover h3 { color: #1f6b3b; }
    .pcard .pprecio { font-size: 15px; color: #5a7a64; }

    .sin-resultados { color: #5a7a64; font-size: 16px; padding: 30px 0; }

    @media (max-width: 900px) {
        .productos-layout { grid-template-columns: 1fr; gap: 24px; }
        .productos-grid { grid-template-columns: repeat(2, 1fr); }
        .pp-title { font-size: 32px; }
    }
    @media (max-width: 560px) { .productos-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')

    <form method="GET" action="{{ route('tienda.productos') }}" id="form-filtros">
    <div class="productos-page">

        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <a href="{{ route('tienda.index') }}">Inicio</a>
            <span class="sep">&rsaquo;</span>
            <span class="actual">Todos los productos</span>
        </div>

        {{-- Portada --}}
        <div class="pp-cover">
            <img src="{{ asset('img/portada-productos.avif') }}" alt="Productos VitaMind">
        </div>

        <h1 class="pp-title">Todos los productos</h1>

        <div class="productos-layout">

            {{-- SIDEBAR DE FILTROS --}}
            <aside class="filtros">
                <div class="fgrupo">
                    <h4>Explorar por</h4>
                    <label class="opt {{ request('cat') ? '' : 'activo' }}">
                        <input type="radio" name="cat" value="" onchange="document.getElementById('form-filtros').submit()" {{ request('cat') ? '' : 'checked' }}>
                        Todos los productos
                    </label>
                    @foreach ($categorias as $cat)
                        <label class="opt {{ request('cat') == $cat->id ? 'activo' : '' }}">
                            <input type="radio" name="cat" value="{{ $cat->id }}" onchange="document.getElementById('form-filtros').submit()" {{ request('cat') == $cat->id ? 'checked' : '' }}>
                            {{ $cat->nombre }}
                        </label>
                    @endforeach
                </div>

                <div class="fgrupo">
                    <h4>Precio (Bs)</h4>
                    <div class="precio-rango">
                        <input type="number" name="min" value="{{ request('min') }}" placeholder="{{ $precioMin }}" min="0">
                        <span>&ndash;</span>
                        <input type="number" name="max" value="{{ request('max') }}" placeholder="{{ $precioMax }}" min="0">
                    </div>
                    <button type="submit" class="btn-aplicar">Aplicar</button>
                    {{-- Conservamos el orden y la categoria al aplicar el precio --}}
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @if (request()->hasAny(['cat', 'min', 'max']))
                        <a href="{{ route('tienda.productos') }}" class="limpiar">Limpiar filtros</a>
                    @endif
                </div>
            </aside>

            {{-- LISTA DE PRODUCTOS --}}
            <div class="productos-main">
                <div class="productos-topbar">
                    <span class="conteo">{{ $productos->count() }} {{ $productos->count() === 1 ? 'producto' : 'productos' }}</span>
                    <label class="orden">
                        Ordenar por:
                        <select name="sort" onchange="document.getElementById('form-filtros').submit()">
                            <option value=""           {{ request('sort') == '' ? 'selected' : '' }}>Recomendados</option>
                            <option value="precio_asc"  {{ request('sort') == 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                            <option value="precio_desc" {{ request('sort') == 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                            <option value="nombre"      {{ request('sort') == 'nombre' ? 'selected' : '' }}>Nombre (A-Z)</option>
                        </select>
                    </label>
                </div>

                @if ($productos->isEmpty())
                    <p class="sin-resultados">No encontramos productos con esos filtros. <a href="{{ route('tienda.productos') }}" style="color:#1f6b3b;">Ver todos</a>.</p>
                @else
                    <div class="productos-grid">
                        @foreach ($productos as $producto)
                            <a href="{{ route('tienda.producto', $producto) }}" class="pcard">
                                <div class="pimg">
                                    @if ($producto->imagen)
                                        <img src="{{ asset('img/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
                                    @endif
                                </div>
                                <h3>{{ $producto->nombre }}</h3>
                                <div class="pprecio">Bs {{ number_format($producto->precio, 2) }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
    </form>

@endsection
