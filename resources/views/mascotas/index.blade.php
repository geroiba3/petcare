@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold">Mis mascotas</h2>
        <a href="/mascotas/crear" class="btn btn-petcare">+ Agregar mascota</a>
    </div>
  
    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (count($mascotas)===0)
     <div class="card card-petcare p-5 text-center">
            <div class="fs-1 mb-3">🐶</div>
            <h4 class="fw-bold">Todavía no tenés mascotas</h4>
            <p class="text-muted">Agregá tu primera mascota para empezar.</p>
            <div>
                <a href="/mascotas/crear" class="btn btn-petcare">Agregar mascota</a>
            </div>
        </div>
     @else
        <div class="row g-4">
            @foreach($mascotas as $id => $mascota)
                <div class="col-md-4">
                    <div class="card card-petcare p-4 text-center">

                        @if(!empty($mascota['foto']))
                            <img src="{{ $mascota['foto'] }}" class="rounded-circle mx-auto mb-3" width="100" height="100" style="object-fit: cover;">
                        @else
                            <div class="fs-1 mb-2">
                                @if($mascota['especie'] === 'perro') 
                                @elseif($mascota['especie'] === 'gato') 
                                @elseif($mascota['especie'] === 'ave') 
                                @else 🐾
                                @endif
                            </div>
                        @endif

                        <h5 class="fw-bold mb-1">{{ $mascota['nombre'] }}</h5>
                        <p class="text-muted mb-1">{{ ucfirst($mascota['especie']) }}</p>
                        @if($mascota['raza'])
                            <p class="text-muted mb-1">{{ $mascota['raza'] }}</p>
                        @endif
                        @if(!empty($mascota['edad']))
                            <p class="text-muted small mb-0">{{ $mascota['edad'] }}</p>
                        @endif
                    </div>
                <div class="d-flex gap-2 justify-content-center mt-2 flex-wrap">
                        <a href="/mascotas/{{ $id }}/historial" class="btn btn-outline-primary btn-sm">📋 Historial</a>
                        <a href="/mascotas/{{ $id }}/editar" class="btn btn-outline-secondary btn-sm">Editar</a>
                    <form method="POST" action="/mascotas/{{ $id }}" onsubmit="return confirm('¿Seguro que querés eliminar a {{ $mascota['nombre'] }}?')">
                        @csrf
                        @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar</button>
                    </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection