@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold">Mis turnos</h2>
        <a href="/turnos/crear" class="btn btn-petcare">+ Sacar turno</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if(count($turnos) === 0)
        <div class="card card-petcare p-5 text-center">
            <div class="fs-1 mb-3">📅</div>
            <h4 class="fw-bold">Todavía no tenés turnos</h4>
            <p class="text-muted">Sacá tu primer turno para tu mascota.</p>
            <div>
                <a href="/turnos/crear" class="btn btn-petcare">Sacar turno</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($turnos as $id => $turno)
                <div class="col-md-6">
                    <div class="card card-petcare p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0">🐾 {{ $turno['mascota_nombre'] }}</h5>
                            <span class="badge bg-warning text-white">{{ ucfirst($turno['estado']) }}</span>
                        </div>
                        <p class="text-muted mb-1">📅 {{ $turno['fecha'] }} - 🕐 {{ $turno['hora'] }}</p>
                        <p class="text-muted mb-3">{{ $turno['motivo'] }}</p>
                        <p class="text-muted mb-1">🏥 {{ $turno['veterinaria_nombre'] ?? 'Sin especificar' }}</p>
                        <form method="POST" action="/turnos/{{ $id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Cancelar turno</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection