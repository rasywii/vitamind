@extends('layouts.app')

@section('titulo', $post->titulo . ' - VitaMind')

@section('styles')
<style>
    .post-detalle { max-width: 800px; margin: 0 auto; padding: 30px 24px 70px; }

    .post-volver { display: inline-block; color: #1f6b3b; font-size: 14px; font-weight: 600; margin-bottom: 28px; }
    .post-volver:hover { text-decoration: underline; }

    .pd-author { display: flex; align-items: center; gap: 12px; margin-bottom: 22px; }
    .pd-author .avatar {
        width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0;
        background: #1f6b3b; color: #fff; font-weight: bold; font-size: 17px;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .pd-author .autor-nombre { font-size: 14px; font-weight: 600; color: #1f3d2b; }
    .pd-author .autor-meta { font-size: 13px; color: #8a9a8f; }

    .post-detalle h1 { font-size: 42px; line-height: 1.15; color: #1f4023; font-weight: 800; margin-bottom: 22px; }
    .pd-lead { font-size: 18px; color: #3a5444; line-height: 1.6; margin-bottom: 28px; }

    .pd-hero { border-radius: 14px; overflow: hidden; margin-bottom: 30px; background: #f1f6ec; }
    .pd-hero img { width: 100%; display: block; }

    /* Contenido del articulo */
    .pd-contenido { color: #3a5444; font-size: 17px; line-height: 1.7; }
    .pd-contenido h2 { font-size: 26px; color: #1f4023; font-weight: 800; margin: 32px 0 14px; }
    .pd-contenido p { margin-bottom: 18px; }
    .pd-contenido ul, .pd-contenido ol { margin: 0 0 18px 22px; }
    .pd-contenido li { margin-bottom: 10px; }
    .pd-contenido strong { color: #1f3d2b; }

    .pd-share { display: flex; align-items: center; border-top: 1px solid #e3ddcd; margin-top: 36px; padding-top: 20px; }

    .pd-stats {
        display: flex; align-items: center; gap: 18px; color: #8a9a8f; font-size: 14px;
        border-top: 1px solid #e3ddcd; margin-top: 20px; padding-top: 18px;
    }
    .like-btn {
        margin-left: auto; display: inline-flex; align-items: center; gap: 7px;
        background: none; border: none; cursor: pointer; font-size: 14px; padding: 0;
    }
    .like-btn .like-count { color: #5a7a64; font-weight: 600; }
    .like-btn .like-count:empty { display: none; }
    .like-btn svg { width: 22px; height: 22px; fill: none; stroke: #e8607d; stroke-width: 1.4; transition: transform .2s ease; }
    .like-btn:hover svg { transform: scale(1.15); }
    .like-btn.liked svg { fill: #e8607d; stroke: #e8607d; }

    /* Entradas recientes */
    .recientes { margin-top: 50px; }
    .recientes-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .recientes-head h3 { font-size: 22px; color: #1f4023; font-weight: 800; }
    .recientes-head a { font-size: 14px; color: #1f6b3b; font-weight: 600; }
    .recientes-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
    @media (max-width: 600px) { .recientes-grid { grid-template-columns: 1fr; } }
    .rec-card { background: #fffdf7; border: 1px solid #ece6d8; border-radius: 14px; overflow: hidden; }
    .rec-card .rec-img { display: block; height: 150px; background: #f1f6ec; overflow: hidden; }
    .rec-card .rec-img img { width: 100%; height: 100%; object-fit: cover; }
    .rec-card .rec-body { padding: 14px 16px 16px; }
    .rec-card h4 { font-size: 16px; color: #1f4023; font-weight: 700; line-height: 1.3; }
    .rec-card h4 a:hover { color: #1f6b3b; }

    /* Comentarios */
    .comentarios { margin-top: 50px; border-top: 1px solid #e3ddcd; padding-top: 30px; }
    .comentarios h3 { font-size: 20px; color: #1f3d2b; font-weight: 700; margin-bottom: 18px; }
    .comentarios textarea {
        width: 100%; min-height: 90px; border: 1px solid #d8d0c0; border-radius: 10px;
        padding: 14px; font-size: 15px; font-family: inherit; resize: vertical; color: #1f3d2b;
    }
    .comentarios .com-acciones { margin-top: 12px; text-align: right; }
    .comentarios .com-btn {
        background: #1f6b3b; color: #fff; border: none; border-radius: 8px;
        padding: 10px 22px; font-size: 14px; font-weight: bold; cursor: pointer;
    }
    .comentarios .com-btn:hover { background: #185530; }
</style>
@endsection

@section('content')

    <article class="post-detalle">
        <a href="{{ route('blog.index') }}" class="post-volver">&larr; All Posts</a>

        <div class="pd-author">
            <span class="avatar">{{ mb_strtoupper(mb_substr($post->autor, 0, 1)) }}</span>
            <div>
                <div class="autor-nombre">{{ $post->autor }}</div>
                <div class="autor-meta">{{ $post->created_at->locale('es')->translatedFormat('d M') }} &middot; {{ $post->tiempo_lectura }} min de lectura</div>
            </div>
        </div>

        <h1>{{ $post->titulo }}</h1>

        @if ($post->extracto)
            <p class="pd-lead">{{ $post->extracto }}</p>
        @endif

        @if ($post->imagen)
            <div class="pd-hero">
                <img src="{{ asset('img/' . $post->imagen) }}" alt="{{ $post->titulo }}">
            </div>
        @endif

        <div class="pd-contenido">
            {!! $post->contenido !!}
        </div>

        {{-- Compartir (mismo estilo que el footer: solo Facebook e Instagram) --}}
        <div class="pd-share">
            <div class="f-social">
                <a href="https://facebook.com" target="_blank" rel="noopener" class="fb" aria-label="Facebook">
                    <svg viewBox="0 0 24 24"><path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07c0 6.02 4.39 11.01 10.13 11.93v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.89v2.25h3.33l-.53 3.49h-2.8V24C19.61 23.08 24 18.09 24 12.07z"/></svg>
                </a>
                <a href="https://instagram.com" target="_blank" rel="noopener" class="ig" aria-label="Instagram">
                    <svg viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.43.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.43.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41-.56-.22-.96-.48-1.38-.9-.42-.42-.68-.82-.9-1.38-.16-.43-.36-1.06-.41-2.23C2.21 15.58 2.2 15.2 2.2 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.43-.16 1.06-.36 2.23-.41C8.42 2.21 8.8 2.2 12 2.2zm0 1.8c-3.14 0-3.5.01-4.74.07-.9.04-1.38.19-1.71.32-.43.17-.74.37-1.06.69-.32.32-.52.63-.69 1.06-.13.33-.28.81-.32 1.71-.06 1.24-.07 1.6-.07 4.74s.01 3.5.07 4.74c.04.9.19 1.38.32 1.71.17.43.37.74.69 1.06.32.32.63.52 1.06.69.33.13.81.28 1.71.32 1.24.06 1.6.07 4.74.07s3.5-.01 4.74-.07c.9-.04 1.38-.19 1.71-.32.43-.17.74-.37 1.06-.69.32-.32.52-.63.69-1.06.13-.33.28-.81.32-1.71.06-1.24.07-1.6.07-4.74s-.01-3.5-.07-4.74c-.04-.9-.19-1.38-.32-1.71-.17-.43-.37-.74-.69-1.06a2.86 2.86 0 0 0-1.06-.69c-.33-.13-.81-.28-1.71-.32C15.5 4.01 15.14 4 12 4zm0 3.06A4.94 4.94 0 1 0 12 16.94 4.94 4.94 0 0 0 12 7.06zm0 8.14A3.2 3.2 0 1 1 12 8.8a3.2 3.2 0 0 1 0 6.4zm6.29-8.36a1.15 1.15 0 1 1-2.3 0 1.15 1.15 0 0 1 2.3 0z"/></svg>
                </a>
            </div>
        </div>

        {{-- Visualizaciones y like --}}
        <div class="pd-stats">
            <span>{{ $post->vistas }} {{ $post->vistas === 1 ? 'visualizacion' : 'visualizaciones' }}</span>
            <span>0 comentarios</span>
            <button type="button" class="like-btn {{ $yaLike ? 'liked' : '' }}" id="like-btn" data-url="{{ route('blog.like', $post) }}" aria-label="Me gusta">
                <span class="like-count">{{ $post->likes ?: '' }}</span>
                <svg viewBox="0 0 19 19"><path d="M9.44985848,15.5291774 C9.43911371,15.5362849 9.42782916,15.5449227 9.41715267,15.5553324 L9.44985848,15.5291774 Z M9.44985848,15.5291774 L9.49370677,15.4941118 C9.15422701,15.7147757 10.2318883,15.0314406 10.7297038,14.6971183 C11.5633567,14.1372547 12.3827081,13.5410755 13.1475707,12.9201001 C14.3829188,11.9171478 15.3570936,10.9445466 15.9707237,10.0482572 C16.0768097,9.89330422 16.1713564,9.74160032 16.2509104,9.59910798 C17.0201658,8.17755699 17.2088969,6.78363112 16.7499013,5.65913129 C16.4604017,4.81092573 15.7231445,4.11008901 14.7401472,3.70936139 C13.1379564,3.11266008 11.0475663,3.84092251 9.89976068,5.36430396 L9.50799408,5.8842613 L9.10670536,5.37161711 C7.94954806,3.89335486 6.00516066,3.14638251 4.31830373,3.71958508 C3.36517186,4.00646284 2.65439601,4.72068063 2.23964629,5.77358234 C1.79050315,6.87166888 1.98214559,8.26476279 2.74015555,9.58185512 C2.94777753,9.93163559 3.23221417,10.3090129 3.5869453,10.7089994 C4.17752179,11.3749196 4.94653811,12.0862394 5.85617417,12.8273544 C7.11233096,13.8507929 9.65858244,15.6292133 9.58280954,15.555334 C9.53938013,15.5129899 9.48608859,15.5 9.50042471,15.5 C9.5105974,15.5 9.48275828,15.5074148 9.44985848,15.5291774 Z"></path></svg>
            </button>
        </div>

        {{-- Entradas recientes --}}
        @if ($recientes->count())
            <div class="recientes">
                <div class="recientes-head">
                    <h3>Entradas recientes</h3>
                    <a href="{{ route('blog.index') }}">Ver todo</a>
                </div>
                <div class="recientes-grid">
                    @foreach ($recientes as $rec)
                        <div class="rec-card">
                            <a href="{{ route('blog.show', $rec) }}" class="rec-img">
                                @if ($rec->imagen)
                                    <img src="{{ asset('img/' . $rec->imagen) }}" alt="{{ $rec->titulo }}">
                                @endif
                            </a>
                            <div class="rec-body">
                                <h4><a href="{{ route('blog.show', $rec) }}">{{ $rec->titulo }}</a></h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Comentarios (visual, sin backend por ahora) --}}
        <div class="comentarios">
            <h3>Comentarios</h3>
            <textarea placeholder="Escribir un comentario..."></textarea>
            <div class="com-acciones">
                <button type="button" class="com-btn" onclick="alert('Los comentarios estaran disponibles proximamente.')">Publicar</button>
            </div>
        </div>
    </article>

@endsection

@section('scripts')
<script>
    (function () {
        var btn = document.getElementById('like-btn');
        if (!btn) return;
        var token = document.querySelector('meta[name=csrf-token]').content;

        btn.addEventListener('click', function () {
            fetch(btn.dataset.url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                btn.classList.toggle('liked', data.liked);
                btn.querySelector('.like-count').textContent = data.likes > 0 ? data.likes : '';
            })
            .catch(function () {});
        });
    })();
</script>
@endsection
