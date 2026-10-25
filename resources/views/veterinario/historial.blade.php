@extends('layouts.app')

@section('content')

    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('veterinario.buscar') }}" class="text-muted text-decoration-none me-3">← Volver a la búsqueda</a>
        <h2 class="fw-bold mb-0">Historial de {{ $mascota['nombre'] }}</h2>
    </div>

    <div class="d-flex align-items-center gap-3 mb-4">
        @if(!empty($mascota['foto']))
            <img src="{{ $mascota['foto'] }}" class="rounded-circle" width="60" height="60" style="object-fit: cover;">
        @endif
        <p class="mb-0 text-muted">{{ ucfirst($mascota['especie']) }} — {{ $mascota['raza'] ?? '' }}</p>
    </div>

    @if(count($historial) === 0)
        <div class="card card-petcare p-5 text-center">
            <h4 class="fw-bold">Sin registros médicos</h4>
            <p class="text-muted mb-0">Esta mascota todavía no tiene historial cargado.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($historial as $id => $consulta)
                <div class="col-12">
                    <div class="card card-petcare p-4">
                        <span class="badge mb-2
                            @if($consulta['tipo'] === 'vacuna') bg-primary
                            @elseif($consulta['tipo'] === 'enfermedad') bg-danger
                            @elseif($consulta['tipo'] === 'tratamiento') bg-warning text-dark
                            @elseif($consulta['tipo'] === 'desparasitacion') bg-success
                            @else bg-secondary
                            @endif">
                            @if($consulta['tipo'] === 'vacuna') Vacuna
                            @elseif($consulta['tipo'] === 'enfermedad') Enfermedad
                            @elseif($consulta['tipo'] === 'tratamiento') Tratamiento
                            @elseif($consulta['tipo'] === 'desparasitacion') Desparasitación
                            @else Consulta
                            @endif
                        </span>
                        <h5 class="fw-bold mb-1">{{ $consulta['nombre'] }}</h5>
                        <p class="text-muted small mb-1">{{ $consulta['fecha'] }}</p>
                        @if(!empty($consulta['proximo_vencimiento']))
                            @php
                                $venc = \Carbon\Carbon::parse($consulta['proximo_vencimiento']);
                                $estaVencido = $venc->isPast();
                                $diasRestantes = abs($venc->diffInDays(now()));
                            @endphp
                            <p class="small mb-1
                                @if($estaVencido) text-danger fw-bold
                                @elseif($diasRestantes <= 30) text-warning fw-bold
                                @else text-muted
                                @endif">
                                 Próximo: {{ $consulta['proximo_vencimiento'] }}
                                @if($estaVencido)
                                    — <strong>VENCIDO</strong>
                                @elseif($diasRestantes <= 30)
                                    — Vence pronto
                                @endif
                            </p>
                        @endif
                        @if(!empty($consulta['descripcion']))
                            <a href="{{ route('veterinario.historial.detalle', ['userId' => $userId, 'mascotaId' => $mascotaId, 'registroId' => $id]) }}" class="btn btn-outline-secondary btn-sm mt-3">Ver detalle</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection