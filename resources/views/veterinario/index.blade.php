@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Panel del veterinario</h2>
        <p class="text-muted mb-2">Tu agenda de turnos pendientes.</p>
        <a href="{{ route('veterinario.buscar') }}" class="btn btn-outline-secondary btn-sm">Buscar historial de una mascota</a>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('veterinario.index', ['semana' => $offset - 1]) }}" class="btn btn-outline-secondary btn-sm">‹</a>
        <span class="fw-bold">
            {{ ucfirst($inicioSemana->locale('es')->isoFormat('D [de] MMMM')) }}
            —
            {{ $inicioSemana->copy()->addDays(6)->locale('es')->isoFormat('D [de] MMMM') }}
        </span>
        <a href="{{ route('veterinario.index', ['semana' => $offset + 1]) }}" class="btn btn-outline-secondary btn-sm">›</a>
        @if($offset !== 0)
            <a href="{{ route('veterinario.index') }}" class="btn btn-link btn-sm text-petcare">Hoy</a>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
@endif

@if(!$horario)
    <div class="alert alert-warning d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <span>⚠️ Todavía no configuraste tu horario de atención, así que no podemos armarte la vista semanal.</span>
        <a href="/perfil/editar" class="btn btn-sm btn-petcare">Configurar horario</a>
    </div>

    {{-- Fallback: lista simple de turnos, igual que antes --}}
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
                        <a href="{{ route('veterinario.completar', $id) }}" class="btn btn-petcare btn-sm">
                            Completar consulta
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@else
    {{-- Grilla semanal --}}
    @if(count($diasGrid) === 0 || count($slots) === 0)
        <div class="alert alert-warning">
            Tu horario configurado no genera franjas válidas — revisá los días y el horario de inicio/fin en tu perfil.
        </div>
    @else
        <div class="card card-petcare p-0 overflow-hidden">
            <div style="overflow-x: auto;">
                <div style="display: grid; grid-template-columns: 90px repeat({{ count($diasGrid) }}, minmax(150px, 1fr)); min-width: {{ 90 + count($diasGrid) * 150 }}px;">

                    {{-- fila de encabezado --}}
                    <div class="border-bottom bg-light"></div>
                    @foreach($diasGrid as $dia)
                        <div class="border-bottom border-start bg-light text-center py-2">
                            <div class="text-muted small text-uppercase fw-bold">{{ $dia['label'] }}</div>
                            <div class="fw-bold fs-5">{{ $dia['dia'] }}</div>
                        </div>
                    @endforeach

                    {{-- filas por horario --}}
                    @foreach($slots as $hora)
                        <div class="border-bottom p-2 text-muted small fw-bold">{{ $hora }}</div>
                        @foreach($diasGrid as $dia)
                            @php $t = $turnosPorFechaHora[$dia['fecha']][$hora] ?? null; @endphp
                            <div class="border-bottom border-start p-2" style="min-height: 84px;">
                                @if($t)
                                    <a href="{{ route('veterinario.completar', $t['turno_id']) }}"
                                       class="d-block text-decoration-none rounded p-2 h-100"
                                       style="background: #FFF3CD; border-left: 4px solid #FFC107;">
                                        <div class="fw-bold small text-dark">🐾 {{ $t['mascota_nombre'] }}</div>
                                        <div class="small text-muted">{{ $t['motivo'] }}</div>
                                        <div class="small text-muted">👤 {{ $t['usuario_nombre'] }}</div>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    @endforeach

                </div>
            </div>
        </div>
        <p class="text-muted small mt-3">💡 Tocá un turno pendiente para cargar la consulta y sumarla al historial médico de la mascota.</p>
    @endif
@endif

@endsection