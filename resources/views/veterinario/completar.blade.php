@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('veterinario.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 38px; height: 38px;" title="Volver">←</a>
            <div>
                <h2 class="fw-bold mb-0">Completar consulta</h2>
                <p class="text-muted mb-0 small">Cargá el diagnóstico y sumalo al historial médico de la mascota.</p>
            </div>
        </div>

     
        <div class="card card-petcare p-4 mb-4" style="border-left: 5px solid #FFC107;">
          <div class="d-flex align-items-center gap-3 mb-3">
    @if($mascotaFoto)
        <img src="{{ $mascotaFoto }}" alt="{{ $turno['mascota_nombre'] }}" class="rounded-circle" style="width: 64px; height: 64px; object-fit: cover;">
    @else
        <div class="d-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-25 fw-bold" style="width: 64px; height: 64px; font-size: 22px;">
            {{ strtoupper(substr($turno['mascota_nombre'], 0, 1)) }}
        </div>
    @endif
    <div>
        <h4 class="fw-bold mb-1">{{ $turno['mascota_nombre'] }}</h4>
        <span class="badge bg-warning text-dark">Turno pendiente</span>
    </div>
</div>
            <div class="row g-3">
                <div class="col-sm-4">
                    <div class="text-muted small">Dueño</div>
                    <div class="fw-medium">{{ $turno['usuario_nombre'] }}</div>
                </div>
                <div class="col-sm-4">
                    <div class="text-muted small">Fecha y hora</div>
                    <div class="fw-medium">{{ $turno['fecha'] }} — {{ $turno['hora'] }}</div>
                </div>
                <div class="col-sm-4">
                    <div class="text-muted small">Motivo</div>
                    <div class="fw-medium">{{ $turno['motivo'] }}</div>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="card card-petcare p-4">
            <h5 class="fw-bold mb-4">Registro médico</h5>

            <form method="POST" action="{{ route('veterinario.guardar', $turnoId) }}">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="mb-4">
                    <label class="form-label fw-medium mb-2">Tipo de consulta</label>
                    <div class="d-flex flex-wrap gap-2">
                        @php
                            $tipos = [
                                'vacuna' => 'Vacuna',
                                'enfermedad' => 'Enfermedad',
                                'tratamiento' => 'Tratamiento',
                                'desparasitacion' => 'Desparasitación',
                                'consulta' => 'Consulta general',
                            ];
                        @endphp
                        @foreach ($tipos as $valor => $label)
                            <input type="radio" class="btn-check" name="tipo" id="tipo-{{ $valor }}" value="{{ $valor }}"
                                   {{ old('tipo') === $valor ? 'checked' : '' }} required>
                            <label class="btn btn-outline-secondary rounded-pill px-3 py-2" for="tipo-{{ $valor }}">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium d-flex justify-content-between">
                        <span>Diagnóstico / Notas</span>
                        <span class="text-muted small fw-normal"><span id="contador">0</span>/500</span>
                    </label>
                    <textarea name="diagnostico" id="diagnostico" class="form-control" rows="4" maxlength="500"
                              placeholder="Describí el diagnóstico, tratamiento aplicado, observaciones..." required>{{ old('diagnostico') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Próximo vencimiento <span class="text-muted small fw-normal">(opcional — para vacunas y desparasitaciones)</span></label>
                    <input type="date" name="proximo_vencimiento" class="form-control" value="{{ old('proximo_vencimiento') }}">
                </div>

                <button type="submit" class="btn btn-petcare w-100 py-2 fw-bold">
                    Guardar y completar turno
                </button>
            </form>
        </div>

    </div>
</div>

<style>
    .btn-check:checked + .btn-outline-secondary {
        background: #F97316;
        border-color: #F97316;
        color: #fff;
    }
</style>

<script>
    const textarea = document.getElementById('diagnostico');
    const contador = document.getElementById('contador');
    if (textarea && contador) {
        const actualizar = () => contador.textContent = textarea.value.length;
        textarea.addEventListener('input', actualizar);
        actualizar();
    }
</script>

@endsection