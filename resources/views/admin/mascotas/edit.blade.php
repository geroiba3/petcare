@extends('admin.layout')

@section('admin-content')
<h3 class="mb-4">Editar mascota</h3>

<div class="card-petcare p-4" style="max-width: 600px;">
    <form action="{{ route('admin.mascotas.update', [$userId, $mascotaId]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre *</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $mascota['nombre']) }}" required>
            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Especie *</label>
            <input type="text" name="especie" class="form-control @error('especie') is-invalid @enderror"
                   value="{{ old('especie', $mascota['especie']) }}" required>
            @error('especie') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Raza</label>
            <input type="text" name="raza" class="form-control"
                   value="{{ old('raza', $mascota['raza'] ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                   value="{{ old('fecha_nacimiento', $mascota['fecha_nacimiento'] ?? '') }}">
            @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-petcare">Guardar cambios</button>
            <a href="{{ route('admin.mascotas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection