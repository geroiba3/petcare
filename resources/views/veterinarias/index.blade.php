@extends('layouts.app')

@section('content')

    <div class="d-flex align-items-center mb-4">
        <a href="/dashboard" class="text-muted text-decoration-none me-3">← Volver</a>
        <h2 class="fw-bold mb-0">Veterinarias</h2>
    </div>

    @if($veterinarias->isEmpty())
        <div class="card card-petcare p-5 text-center">
            <h4 class="fw-bold">No hay veterinarias cargadas</h4>
            <p class="text-muted mb-0">Todavía no se registró ninguna veterinaria.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($veterinarias as $veterinaria)
                <div class="col-md-6">
                    <div class="card card-petcare p-4 h-100">
                        <h5 class="fw-bold mb-2">{{ $veterinaria['nombre'] ?? 'Sin nombre' }}</h5>

                        <p class="text-muted mb-1">
                            <strong>Dirección:</strong> {{ $veterinaria['direccion'] ?? 'No especificada' }}
                        </p>

                        @if(!empty($veterinaria['telefono']))
                            <p class="text-muted mb-1">
                                <strong>Teléfono:</strong> {{ $veterinaria['telefono'] }}
                            </p>
                        @endif

                        <p class="text-muted mb-3">
                            <strong>Horarios:</strong> {{ $veterinaria['horarios'] ?? 'No especificados' }}
                        </p>

                        <div>
                            <strong class="d-block mb-1">Médicos asociados:</strong>
                            @if(count($veterinaria['medicos']) > 0)
                                <ul class="mb-0 ps-3">
                                    @foreach($veterinaria['medicos'] as $medico)
                                        <li>{{ $medico }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Sin médicos asignados todavía.</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection