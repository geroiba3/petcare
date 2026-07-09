@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="fw-bold mb-5">Mi perfil</h2>

            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            {{-- Datos personales --}}
            <div class="card card-petcare p-4 mb-4">
                <h5 class="fw-bold mb-4">Datos personales</h5>

                <form method="POST" action="/perfil" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Foto de perfil</label>
                        <input type="file" name="foto" accept="image/*" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-petcare w-100">Guardar cambios</button>
                </form>
            </div>

            {{-- Cambiar contraseña --}}
            <div class="card card-petcare p-4">
                <h5 class="fw-bold mb-4">Cambiar contraseña</h5>

                <form method="POST" action="/perfil/password">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Contraseña actual</label>
                        <input type="password" name="password_actual" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nueva contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-petcare w-100">Cambiar contraseña</button>
                </form>
            </div>

        </div>
    </div>

@endsection