@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold">Mis mascotas</h2>
        <a href="/mascotas/crear" class="btn btn-petcare">+ Agregar mascota</a>
    </div>

    <!-- Lista vacía por ahora -->
    <div class="card card-petcare p-5 text-center">
        <div class="fs-1 mb-3">🐶</div>
        <h4 class="fw-bold">Todavía no tenés mascotas</h4>
        <p class="text-muted">Agregá tu primera mascota para empezar.</p>
        <div>
            <a href="/mascotas/crear" class="btn btn-petcare">Agregar mascota</a>
        </div>
    </div>

@endsection