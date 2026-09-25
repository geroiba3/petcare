@extends('layouts.app')

@section('content')

   <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <a href="/mascotas" class="text-muted text-decoration-none me-3">← Volver</a>
            <h2 class="fw-bold mb-0">Historial de {{ $mascota['nombre'] }}</h2>
        </div>
        <a href="{{ route('historial.create', $mascotaId) }}" class="btn btn-petcare">+ Agregar registro</a>
    </div>

    <div class="d-flex align-items-center gap-3 mb-4">
        @if(!empty($mascota['foto']))
            <img src="{{ $mascota['foto'] }}" class="rounded-circle" width="60" height="60" style="object-fit: cover;">
        @endif
        <p class="mb-0 text-muted">{{ ucfirst($mascota['especie']) }} — {{ $mascota['raza'] ?? '' }}</p>
    </div>

     @if(session('success'))
   <div class="alert alert-success mb-4">{{ session('success') }}</div>
     @endif
   <div class="d-flex flex-wrap gap-2 mb-4">
        @php 
       $tipos = [
        'todos' => 'Todos',
        'vacuna' => 'Vacunas',
        'enfermedad' => 'Enfermedades',
        'tratamiento' => 'Tratamientos',
        'desparasitacion' => 'Desparasitaciones'
        ];

         @endphp
        @foreach($tipos as $valor => $label)
            <a href="{{ route('historial.index', ['mascotaId' => $mascotaId, 'tipo' => $valor]) }}" class="btn btn-outline-petcare {{ $tipo === $valor ? 'active' : '' }}">{{ $label }}</a>
            
        @endforeach
     </div>
    @if(count($historial) === 0)
        <div class="card card-petcare p-5 text-center">
            <div class="fs-1 mb-3">📋</div>
            @if ($tipo === 'todos')
                <h4 class="fw-bold">Sin registros médicos</h4>
                <p class="text-muted">El historial médico de {{ $mascota['nombre'] }} aparecerá acá una vez que el veterinario complete un turno.</p>
            @else
                <h4 class="fw-bold">Sin registros de este tipo</h4>
                <p class="text-muted">No hay registros de "{{ $tipos[$tipo] }}" para {{ $mascota['nombre'] }}.</p>
            @endif

    @else
        <div class="row g-3">
            @foreach($historial as $id => $consulta)
                <div class="col-12">
                    <div class="card card-petcare p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge mb-2
                                    @if($consulta['tipo'] === 'vacuna') bg-primary
                                    @elseif($consulta['tipo'] === 'enfermedad') bg-danger
                                    @elseif($consulta['tipo'] === 'tratamiento') bg-warning text-dark
                                    @elseif($consulta['tipo'] === 'desparasitacion') bg-success
                                    @else bg-secondary
                                    @endif">
                                    @if($consulta['tipo'] === 'vacuna')  Vacuna
                                    @elseif($consulta['tipo'] === 'enfermedad')  Enfermedad
                                    @elseif($consulta['tipo'] === 'tratamiento') Tratamiento
                                    @elseif($consulta['tipo'] === 'desparasitacion')  Desparasitación
                                    @else  Consulta
                                    @endif
                                </span>
                                <h5 class="fw-bold mb-1">{{ $consulta['nombre'] }}</h5>
                                <p class="text-muted small mb-1"> {{ $consulta['fecha'] }}</p>
                                @if(!empty($consulta['proximo_vencimiento']))
                                    <p class="small mb-1
                                        @if(\Carbon\Carbon::parse($consulta['proximo_vencimiento'])->isPast()) text-danger fw-bold
                                        @elseif(\Carbon\Carbon::parse($consulta['proximo_vencimiento'])->diffInDays(now()) <= 30) text-warning fw-bold
                                        @else text-muted
                                        @endif">
                                         Próximo: {{ $consulta['proximo_vencimiento'] }}
                                        @if(\Carbon\Carbon::parse($consulta['proximo_vencimiento'])->isPast())
                                            — <strong>VENCIDO</strong>
                                        @elseif(\Carbon\Carbon::parse($consulta['proximo_vencimiento'])->diffInDays(now()) <= 30)
                                            — Vence pronto
                                        @endif
                                    </p>
                                @endif
                                @if(!empty($consulta['descripcion']))
                                    <p class="text-muted mb-0">{{ $consulta['descripcion'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection