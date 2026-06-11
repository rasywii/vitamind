@extends('layouts.app')

@section('titulo', 'VitaMind - Blog')

@section('styles')
<style>
    .blog-page { padding: 40px 5% 70px; max-width: 1200px; margin: 0 auto; }
    .blog-head { margin-bottom: 36px; }
    .blog-head h1 { font-size: 44px; color: #1f4023; font-weight: 800; margin-bottom: 8px; }
    .blog-head p { color: #5a7a64; font-size: 17px; }

    .blog-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 36px; }
    @media (max-width: 850px) { .blog-grid { grid-template-columns: 1fr; } }

    .post-card {
        background: #fffdf7; border: 1px solid #ece6d8; border-radius: 16px; overflow: hidden;
        display: flex; flex-direction: column; transition: transform .25s ease, box-shadow .25s ease;
    }
    .post-card:hover { transform: translateY(-5px); box-shadow: 0 16px 40px rgba(31, 61, 43, 0.12); }
    .post-card .post-img { display: block; height: 300px; background: #f1f6ec; overflow: hidden; }
    .post-card .post-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .35s ease; }
    .post-card:hover .post-img img { transform: scale(1.04); }
    .post-card .post-body { padding: 22px 26px 24px; display: flex; flex-direction: column; flex: 1; }

    .post-author { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
    .post-author .avatar {
        width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
        background: #1f6b3b; color: #fff; font-weight: bold; font-size: 16px;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .post-author .autor-nombre { font-size: 14px; font-weight: 600; color: #1f3d2b; }
    .post-author .autor-meta { font-size: 13px; color: #8a9a8f; }

    .post-card h2 { font-size: 23px; line-height: 1.25; margin-bottom: 12px; color: #1f4023; font-weight: 800; }
    .post-card h2 a:hover { color: #1f6b3b; }
    .post-extracto { color: #5a7a64; font-size: 15px; line-height: 1.6; margin-bottom: 20px; }

    .post-footer {
        margin-top: auto; padding-top: 16px; border-top: 1px solid #ece6d8;
        display: flex; align-items: center; gap: 20px; color: #8a9a8f; font-size: 14px;
    }
    .post-footer .stat { display: inline-flex; align-items: center; gap: 6px; }
    .post-footer .stat svg { width: 18px; height: 18px; }
    .post-footer .like-btn {
        margin-left: auto; display: inline-flex; align-items: center; gap: 6px;
        background: none; border: none; cursor: pointer; padding: 0; font-size: 14px;
    }
    .post-footer .like-btn .like-count { color: #5a7a64; font-weight: 600; }
    .post-footer .like-btn .like-count:empty { display: none; }
    .post-footer .like-btn svg { width: 20px; height: 20px; fill: none; stroke: #e8607d; stroke-width: 1.4; transition: transform .2s ease; }
    .post-footer .like-btn:hover svg { transform: scale(1.15); }
    .post-footer .like-btn.liked svg { fill: #e8607d; stroke: #e8607d; }
</style>
@endsection

@section('content')

    <div class="blog-page">
        <div class="blog-head">
            <h1>Blog</h1>
            <p>Recetas, consejos de nutrici&oacute;n y bienestar para tu d&iacute;a a d&iacute;a.</p>
        </div>

        <div class="blog-grid">
            @foreach ($posts as $post)
                <article class="post-card">
                    <a href="{{ route('blog.show', $post) }}" class="post-img">
                        @if ($post->imagen)
                            <img src="{{ asset('img/' . $post->imagen) }}" alt="{{ $post->titulo }}">
                        @endif
                    </a>
                    <div class="post-body">
                        <div class="post-author">
                            <span class="avatar">{{ mb_strtoupper(mb_substr($post->autor, 0, 1)) }}</span>
                            <div>
                                <div class="autor-nombre">{{ $post->autor }}</div>
                                <div class="autor-meta">{{ $post->created_at->locale('es')->translatedFormat('d M') }} &middot; {{ $post->tiempo_lectura }} min de lectura</div>
                            </div>
                        </div>

                        <h2><a href="{{ route('blog.show', $post) }}">{{ $post->titulo }}</a></h2>
                        <p class="post-extracto">{{ $post->extracto }}</p>

                        <div class="post-footer">
                            <span class="stat">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                {{ $post->vistas }}
                            </span>
                            <span class="stat">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                0
                            </span>
                            <button type="button" class="like-btn {{ in_array($post->id, $liked) ? 'liked' : '' }}" data-url="{{ route('blog.like', $post) }}" aria-label="Me gusta">
                                <span class="like-count">{{ $post->likes ?: '' }}</span>
                                <svg viewBox="0 0 19 19"><path d="M9.44985848,15.5291774 C9.43911371,15.5362849 9.42782916,15.5449227 9.41715267,15.5553324 L9.44985848,15.5291774 Z M9.44985848,15.5291774 L9.49370677,15.4941118 C9.15422701,15.7147757 10.2318883,15.0314406 10.7297038,14.6971183 C11.5633567,14.1372547 12.3827081,13.5410755 13.1475707,12.9201001 C14.3829188,11.9171478 15.3570936,10.9445466 15.9707237,10.0482572 C16.0768097,9.89330422 16.1713564,9.74160032 16.2509104,9.59910798 C17.0201658,8.17755699 17.2088969,6.78363112 16.7499013,5.65913129 C16.4604017,4.81092573 15.7231445,4.11008901 14.7401472,3.70936139 C13.1379564,3.11266008 11.0475663,3.84092251 9.89976068,5.36430396 L9.50799408,5.8842613 L9.10670536,5.37161711 C7.94954806,3.89335486 6.00516066,3.14638251 4.31830373,3.71958508 C3.36517186,4.00646284 2.65439601,4.72068063 2.23964629,5.77358234 C1.79050315,6.87166888 1.98214559,8.26476279 2.74015555,9.58185512 C2.94777753,9.93163559 3.23221417,10.3090129 3.5869453,10.7089994 C4.17752179,11.3749196 4.94653811,12.0862394 5.85617417,12.8273544 C7.11233096,13.8507929 9.65858244,15.6292133 9.58280954,15.555334 C9.53938013,15.5129899 9.48608859,15.5 9.50042471,15.5 C9.5105974,15.5 9.48275828,15.5074148 9.44985848,15.5291774 Z"></path></svg>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>

@endsection

@section('scripts')
<script>
    (function () {
        var token = document.querySelector('meta[name=csrf-token]').content;

        document.querySelectorAll('.like-btn').forEach(function (btn) {
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
        });
    })();
</script>
@endsection
