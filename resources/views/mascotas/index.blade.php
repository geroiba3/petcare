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
                    <div class="card card-petcare p-4">
                        <div class="fs-1 mb-2">
                            @if($mascota['especie'] === 'perro') 🐶
                            @elseif($mascota['especie'] === 'gato') 🐱
                            @elseif($mascota['especie'] === 'ave') 🐦
                            @else 🐾
                            @endif
                        </div>
                        <h5 class="fw-bold">{{ $mascota['nombre'] }}</h5>
                        <p class="text-muted mb-1">{{ ucfirst($mascota['especie']) }}</p>
                        @if($mascota['raza'])
                            <p class="text-muted mb-1">{{ $mascota['raza'] }}</p>
                        @endif
                        @if($mascota['fecha_nacimiento'])
                            <p class="text-muted small">Nacimiento: {{ $mascota['fecha_nacimiento'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection