@extends('layouts.app')

@section('content')

    <h2 class="fw-bold mb-5">Panel del veterinario</h2>

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if(count($turnos) === 0)
        <div class="card card-petcare p-5 text-center">
            <div class="fs-1 mb-3">📅</div>
            <h4 class="fw-bold">No hay turnos pendientes</h4>
            <p class="text-muted">Cuando un dueño saque un turno aparecerá acá.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($turnos as $id => $turno)
                <div class="col-md-6">
                    <div class="card card-petcare p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0">🐾 {{ $turno['mascota_nombre'] }}</h5>
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        </div>
                        <p class="text-muted mb-1">👤 Dueño: {{ $turno['usuario_nombre'] }}</p>
                        <p class="text-muted mb-1">📅 {{ $turno['fecha'] }} — 🕐 {{ $turno['hora'] }}</p>
                        <p class="text-muted mb-3">📝 {{ $turno['motivo'] }}</p>
                        <a href="/veterinario/turnos/{{ $id }}/completar" class="btn btn-petcare btn-sm">
                            Completar consulta
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection