@extends('admin.layout')

@section('admin-content')
<h3 class="mb-4">Nueva veterinaria</h3>

<div class="card-petcare p-4" style="max-width: 600px;">
    <form action="{{ route('admin.veterinarias.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre *</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre') }}" required>
            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección *</label>
            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
                   value="{{ old('direccion') }}" required>
            @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Horarios</label>
            <input type="text" name="horarios" class="form-control" value="{{ old('horarios') }}"
                   placeholder="Ej: Lun a Vie 9:00-18:00, Sáb 9:00-13:00">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-petcare">Guardar</button>
            <a href="{{ route('admin.veterinarias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection