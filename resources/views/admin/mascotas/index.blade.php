@extends('admin.layout')

@section('admin-content')
<h3 class="mb-4">Mascotas</h3>

<div class="btn-group mb-3" role="group">
    <a href="{{ route('admin.mascotas.index') }}"
       class="btn btn-sm {{ $filtro === 'todas' ? 'btn-petcare' : 'btn-outline-secondary' }}">Todas</a>
    <a href="{{ route('admin.mascotas.index', ['estado' => 'activas']) }}"
       class="btn btn-sm {{ $filtro === 'activas' ? 'btn-petcare' : 'btn-outline-secondary' }}">Activas</a>
    <a href="{{ route('admin.mascotas.index', ['estado' => 'inactivas']) }}"
       class="btn btn-sm {{ $filtro === 'inactivas' ? 'btn-petcare' : 'btn-outline-secondary' }}">Inactivas</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if (empty($mascotas))
    <div class="card-petcare p-4 text-center text-muted">
        @if ($filtro === 'todas')
            No hay mascotas registradas.
        @else
            No hay mascotas {{ $filtro }}.
        @endif
    </div>
@else
    <div class="card-petcare p-3">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th></th>
                    <th>Nombre</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th>Dueño</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mascotas as $mascota)
                    <tr>
                        <td style="width: 50px;">
                            @if (!empty($mascota['foto']))
                                <img src="{{ $mascota['foto'] }}" alt="{{ $mascota['nombre'] }}"
                                     class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <span class="d-inline-block rounded-circle bg-light text-center"
                                      style="width: 40px; height: 40px; line-height: 40px;">🐾</span>
                            @endif
                        </td>
                        <td>{{ $mascota['nombre'] }}</td>
                        <td>{{ $mascota['especie'] }}</td>
                        <td>{{ $mascota['raza'] ?: '—' }}</td>
                        <td>{{ $mascota['duenio'] }}</td>
                        <td>
                            @if ($mascota['activo'] ?? true)
                                <span class="badge bg-success">Activa</span>
                            @else
                                <span class="badge bg-secondary">Inactiva</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <form action="{{ route('admin.mascotas.activo', [$mascota['user_id'], $mascota['id']]) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                @if ($mascota['activo'] ?? true)
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Desactivar</button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-outline-success">Activar</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection