@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card card-petcare p-4">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-petcare">🐾 PetCare</h2>
                <p class="text-muted">Creá tu cuenta gratis</p>
            </div>

            <form method="POST" action="/register">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-medium">Nombre</label>
                    <input type="text" name="name" class="form-control" placeholder="Tu nombre" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="tu@email.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-petcare w-100">Crear cuenta</button>
            </form>

            <p class="text-center text-muted mt-4">
                ¿Ya tenés cuenta? <a href="/login" class="text-petcare fw-medium">Iniciá sesión</a>
            </p>
        </div>
    </div>
</div>
@endsection