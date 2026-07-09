@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="d-flex align-items-center mb-4">
                <a href="/veterinario" class="text-muted text-decoration-none me-3">← Volver</a>
                <h2 class="fw-bold mb-0">Completar consulta</h2>
            </div>

            <div class="card card-petcare p-4 mb-4">
                <h5 class="fw-bold mb-1">🐾 {{ $turno['mascota_nombre'] }}</h5>
                <p class="text-muted mb-1">👤 Dueño: {{ $turno['usuario_nombre'] }}</p>
                <p class="text-muted mb-1">📅 {{ $turno['fecha'] }} — 🕐 {{ $turno['hora'] }}</p>
                <p class="text-muted mb-0">📝 Motivo: {{ $turno['motivo'] }}</p>
            </div>

            <div class="card card-petcare p-4">
                <h5 class="fw-bold mb-4">Registro médico</h5>

                <form method="POST" action="/veterinario/turnos/{{ $turnoId }}/completar">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-medium">Tipo de consulta</label>
                        <select name="tipo" class="form-select" required>
                            <option value="">Seleccioná una opción</option>
                            <option value="vacuna"> Vacuna</option>
                            <option value="enfermedad"> Enfermedad</option>
                            <option value="tratamiento"> Tratamiento</option>
                            <option value="desparasitacion"> Desparasitación</option>
                            <option value="consulta">🔬 Consulta general</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Diagnóstico / Notas</label>
                        <textarea name="diagnostico" class="form-control" rows="4" placeholder="Describí el diagnóstico, tratamiento aplicado, observaciones..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Próximo vencimiento <span class="text-muted small">(opcional — para vacunas y desparasitaciones)</span></label>
                        <input type="date" name="proximo_vencimiento" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-petcare w-100">Guardar y completar turno</button>
                </form>
            </div>

        </div>
    </div>

@endsection