@extends('layouts.app')
 
@section('content')
 
    <div class="mb-4">
        <a href="{{ route('foro.index', ['categoria' => $categoria]) }}" class="text-decoration-none text-muted">← Volver al foro</a>
    </div>
 
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
 
    {{-- Tema --}}
    <div class="card card-petcare p-4 mb-4">
        <span class="badge bg-warning text-white align-self-start mb-2">{{ $categorias[$categoria] ?? $categoria }}</span>
        <h3 class="fw-bold">{{ $post['titulo'] }}</h3>
        <p class="mt-2" style="white-space: pre-line;">{{ $post['contenido'] }}</p>
        <small class="text-muted">
            Por {{ $post['autor_nombre'] ?? 'Anónimo' }}
            @if (($post['autor_rol'] ?? '') === 'veterinario') · 🩺 Veterinario @endif
            · {{ \Carbon\Carbon::parse($post['created_at'])->format('d/m/Y H:i') }}
        </small>
 
        @auth
            @if (auth()->user()->isAdmin())
                <form method="POST"
                      action="{{ route('foro.destroy', ['categoria' => $categoria, 'postId' => $postId]) }}"
                      class="mt-3"
                      onsubmit="return confirm('¿Eliminar este tema?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar tema</button>
                </form>
            @endif
        @endauth
    </div>
 
    {{-- Comentarios con Disqus --}}
    <div class="card card-petcare p-4">
        <h5 class="fw-bold mb-3">Respuestas</h5>
 
        @if (config('services.disqus.shortname'))
            <div id="disqus_thread"></div>
            <script>
                var disqus_config = function () {
                    this.page.url = @json(url()->current());
                    this.page.identifier = @json('foro-' . $categoria . '-' . $postId);
                    this.page.title = @json($post['titulo']);
                };
                (function () {
                    var d = document, s = d.createElement('script');
                    s.src = 'https://{{ config('services.disqus.shortname') }}.disqus.com/embed.js';
                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                })();
            </script>
            <noscript>Activá JavaScript para ver y escribir comentarios.</noscript>
        @else
            <div class="alert alert-warning mb-0">
                Falta configurar <code>DISQUS_SHORTNAME</code> en el archivo <code>.env</code>.
            </div>
        @endif
    </div>
 
@endsection