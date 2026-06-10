@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card card-petcare p-4">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-petcare">🐾 PetCare</h2>
                <p class="text-muted">Iniciá sesión en tu cuenta</p>
            </div>

           <form method="POST" action="{{ route('login') }}">
                @csrf
                @if ($errors->any())
            <div class="alert alert-danger mb-3">
                @foreach ($errors->all() as $error)
            <p class="mb-0">{{ $error }}</p>
            @endforeach
            </div>
            @endif
                <div class="mb-3">
                    <label class="form-label fw-medium">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="tu@email.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-petcare w-100">Iniciar sesión</button>
            </form>

            <p class="text-center text-muted mt-4">
                ¿No tenés cuenta? <a href="/register" class="text-petcare fw-medium">Registrate</a>
            </p>
        </div>
    </div>
</div>
@endsection