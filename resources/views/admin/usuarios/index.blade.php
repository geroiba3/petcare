@extends('admin.layout')

@section('admin-content')
<h3 class="mb-4">Usuarios</h3>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card-petcare p-3">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Registrado</th>
                <th>Estado</th>
                <th style="width: 220px;">Rol</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>
                        {{ $usuario->name }}
                        @if ($usuario->id === auth()->id())
                            <span class="badge bg-secondary">Vos</span>
                        @endif
                    </td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                     <td>
                        @if ($usuario->activo)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        @if ($usuario->id === auth()->id())
                            <span class="text-muted">{{ $usuario->role }}</span>
                        @else
                            <form action="{{ route('admin.usuarios.rol', $usuario->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                @method('PUT')
                                <select name="role" class="form-select form-select-sm">
                                    <option value="user" {{ $usuario->role === 'user' ? 'selected' : '' }}>user</option>
                                    <option value="vet" {{ $usuario->role === 'vet' ? 'selected' : '' }}>vet</option>
                                    <option value="admin" {{ $usuario->role === 'admin' ? 'selected' : '' }}>admin</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-petcare">OK</button>
                            </form>
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($usuario->id !== auth()->id())
                            <form action="{{ route('admin.usuarios.activo', $usuario->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                @if ($usuario->activo)
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('¿Desactivar a {{ $usuario->name }}? No podrá iniciar sesión y sus mascotas quedarán ocultas.');">
                                        Desactivar
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-outline-success">Activar</button>
                                @endif
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection