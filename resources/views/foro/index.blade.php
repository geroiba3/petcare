@extends('layouts.app')
 
@section('content')
 
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0">💬 Foro de la comunidad</h2>
        <a href="{{ route('foro.create', ['categoria' => $categoria]) }}" class="btn btn-petcare">+ Nuevo tema</a>
    </div>
 
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
 
    {{-- Pestañas de categorías --}}
    <ul class="nav nav-pills mb-4 gap-2">
        @foreach ($categorias as $clave => $nombre)
            <li class="nav-item">
                <a class="nav-link {{ $categoria === $clave ? 'active bg-warning text-white' : 'text-secondary bg-white' }}"
                   href="{{ route('foro.index', ['categoria' => $clave]) }}">
                    {{ $nombre }}
                </a>
            </li>
        @endforeach
    </ul>
 
    @if (empty($posts))
        <div class="card card-petcare p-5 text-center">
            <div class="fs-1 mb-3">🐾</div>
            <h4 class="fw-bold">Todavía no hay temas en {{ $categorias[$categoria] }}</h4>
            <p class="text-muted">Sé el primero en abrir una conversación.</p>
            <div>
                <a href="{{ route('foro.create', ['categoria' => $categoria]) }}" class="btn btn-petcare">Crear tema</a>
            </div>
        </div>
    @else
        <div class="d-flex flex-column gap-3">
            @foreach ($posts as $id => $post)
                <a href="{{ route('foro.show', ['categoria' => $categoria, 'postId' => $id]) }}"
                   class="text-decoration-none text-dark">
                    <div class="card card-petcare p-4">
                        <h5 class="fw-bold mb-1">{{ $post['titulo'] }}</h5>
                        <p class="text-muted mb-2">{{ \Illuminate\Support\Str::limit($post['contenido'], 140) }}</p>
                        <small class="text-muted">
                            Por {{ $post['autor_nombre'] ?? 'Anónimo' }}
                            @if (($post['autor_rol'] ?? '') === 'veterinario') · 🩺 Veterinario @endif
                            · {{ \Carbon\Carbon::parse($post['created_at'])->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
 
@endsection