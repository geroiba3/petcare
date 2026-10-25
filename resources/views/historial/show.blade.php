@extends('layouts.app')

@section('content')

    <div class="d-flex align-items-center mb-4">
        <a href="{{ $volverUrl }}" class="text-muted text-decoration-none me-3">← Volver al historial</a>
        <h2 class="fw-bold mb-0">Detalle del registro</h2>
    </div>

    @php
        $tipo = $registro['tipo'] ?? 'consulta';
        $tiposLabel = [
            'vacuna' => 'Vacuna',
            'enfermedad' => 'Enfermedad',
            'tratamiento' => 'Tratamiento',
            'desparasitacion' => 'Desparasitación',
            'consulta' => 'Consulta',
        ];
        $tiposColor = [
            'vacuna' => 'bg-primary',
            'enfermedad' => 'bg-danger',
            'tratamiento' => 'bg-warning text-dark',
            'desparasitacion' => 'bg-success',
            'consulta' => 'bg-secondary',
        ];

        $venc = !empty($registro['proximo_vencimiento']) ? \Carbon\Carbon::parse($registro['proximo_vencimiento']) : null;
        $estaVencido = $venc ? $venc->isPast() : false;
        $diasRestantes = $venc ? abs($venc->diffInDays(now())) : null;

        $registradoPor = match ($registro['creado_por'] ?? null) {
            'veterinario' => 'Veterinario/a ' . ($registro['veterinario_nombre'] ?? ''),
            'dueno' => 'El dueño',
            default => 'No registrado',
        };

        $archivo = $registro['archivo'] ?? null;
        $archivoEsImagen = $archivo && str_starts_with($archivo, 'data:image');
    @endphp

    <div class="card card-petcare p-4 mb-3">
        <div class="d-flex align-items-center gap-3 mb-3">
            @if(!empty($mascota['foto']))
                <img src="{{ $mascota['foto'] }}" class="rounded-circle" width="56" height="56" style="object-fit: cover;">
            @endif
            <div>
                <div class="fw-bold">{{ $mascota['nombre'] }}</div>
                <div class="text-muted small">{{ ucfirst($mascota['especie'] ?? '') }}@if(!empty($mascota['raza'])) — {{ $mascota['raza'] }}@endif</div>
            </div>
        </div>

        <span class="badge mb-2 {{ $tiposColor[$tipo] ?? 'bg-secondary' }}">{{ $tiposLabel[$tipo] ?? 'Consulta' }}</span>
        <h4 class="fw-bold mb-3">{{ $registro['nombre'] ?? 'Sin título' }}</h4>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Fecha</div>
                <div class="fw-bold">{{ $registro['fecha'] ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Registrado por</div>
                <div class="fw-bold">{{ $registradoPor }}</div>
            </div>
            @if(!empty($registro['veterinaria_nombre']))
                <div class="col-md-4">
                    <div class="text-muted small">Veterinaria</div>
                    <div class="fw-bold">{{ $registro['veterinaria_nombre'] }}</div>
                </div>
            @endif
            <div class="col-md-4">
                <div class="text-muted small">Próximo vencimiento</div>
                @if($venc)
                    <div class="fw-bold @if($estaVencido) text-danger @elseif($diasRestantes <= 30) text-warning @endif">
                        {{ $registro['proximo_vencimiento'] }}
                        @if($estaVencido) — VENCIDO
                        @elseif($diasRestantes <= 30) — Vence pronto
                        @endif
                    </div>
                @else
                    <div class="fw-bold">Sin vencimiento</div>
                @endif
            </div>
            @if(!empty($registro['created_at']))
                <div class="col-md-4">
                    <div class="text-muted small">Cargado el</div>
                    <div class="fw-bold">{{ $registro['created_at'] }}</div>
                </div>
            @endif
        </div>
    </div>

    <div class="card card-petcare p-4 mb-3">
        <h5 class="fw-bold mb-2">Descripción / diagnóstico</h5>
        @if(!empty($registro['descripcion']))
            <p class="mb-0" style="white-space: pre-line;">{{ $registro['descripcion'] }}</p>
        @else
            <p class="text-muted mb-0">Sin descripción.</p>
        @endif
    </div>

    @if($archivo)
        <div class="card card-petcare p-4">
            <h5 class="fw-bold mb-2">Archivo adjunto</h5>
            @if($archivoEsImagen)
                <img src="{{ $archivo }}" alt="Adjunto" class="img-fluid rounded mb-3" style="max-height: 420px;">
            @endif
            <a href="{{ $archivo }}" download="{{ $registro['archivo_nombre'] ?? 'adjunto' }}" class="btn btn-outline-secondary btn-sm">
                Descargar {{ $registro['archivo_nombre'] ?? 'archivo' }}
            </a>
        </div>
    @endif

@endsection