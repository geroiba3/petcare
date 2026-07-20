@extends('admin.layout')

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Veterinarias</h3>
    <a href="{{ route('admin.veterinarias.create') }}" class="btn btn-petcare">+ Nueva veterinaria</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if (empty($veterinarias))
    <div class="card-petcare p-4 text-center text-muted">
        Todavía no hay veterinarias cargadas.
    </div>
@else
    <div class="card-petcare p-3">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Horarios</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($veterinarias as $id => $vet)
                    <tr>
                        <td>{{ $vet['nombre'] }}</td>
                        <td>{{ $vet['direccion'] }}</td>
                        <td>{{ $vet['telefono'] ?: '—' }}</td>
                        <td>{{ $vet['horarios'] ?: '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.veterinarias.edit', $id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                            <form action="{{ route('admin.veterinarias.destroy', $id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar esta veterinaria?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection