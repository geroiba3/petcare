@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('historial.index', $mascotaId) }}" class="text-muted text-decoration-none me-3">← Volver</a>
                <h2 class="fw-bold mb-0">Agregar registro — {{ $mascota['nombre'] }}</h2>
            </div>

            <div class="card card-petcare p-4">
                <form method="POST" action="{{ route('historial.store', $mascotaId) }}" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-medium">Tipo</label>
                        <select name="tipo" class="form-select" required>
                            <option value="">Seleccioná un tipo</option>
                            <option value="vacuna">Vacuna</option>
                            <option value="enfermedad">Enfermedad</option>
                            <option value="tratamiento">Tratamiento</option>
                            <option value="desparasitacion">Desparasitación</option>
                            <option value="consulta">Consulta</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Análisis de sangre" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Fecha</label>
                        <input type="date" name="fecha" class="form-control" max="{{ now()->toDateString() }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Próximo vencimiento (opcional)</label>
                        <input type="date" name="proximo_vencimiento" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Descripción (opcional)</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Archivo (opcional)</label>
                        <input type="file" name="archivo" class="form-control" accept="image/jpeg,image/png,application/pdf">
                        <div class="form-text">JPG, PNG o PDF, máx 5MB.</div>
                    </div>

                    <button type="submit" class="btn btn-petcare w-100">Guardar registro</button>
                </form>
            </div>

        </div>
    </div>

@endsection