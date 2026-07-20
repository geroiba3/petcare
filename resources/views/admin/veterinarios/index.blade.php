@extends('admin.layout')

@section('admin-content')
<h3 class="mb-4">Veterinarios</h3>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if ($usuarios->isEmpty())
    <div class="card-petcare p-4 text-center text-muted">
        No hay veterinarios registrados. Podés convertir un usuario en veterinario desde la sección
        <a href="{{ route('admin.usuarios.index') }}" class="text-petcare">Usuarios</a>, cambiándole el rol a "vet".
    </div>
@else
    <div class="card-petcare p-3">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Registrado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <form action="{{ route('admin.usuarios.rol', $usuario->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Quitar el rol de veterinario a {{ $usuario->name }}? Pasará a ser usuario común.');">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="role" value="user">
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Quitar rol vet</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection