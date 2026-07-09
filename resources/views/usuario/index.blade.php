@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="fw-bold mb-5">Mi perfil</h2>

            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            {{-- Info actual --}}
            <div class="card card-petcare p-4 mb-4 text-center">
                <div class="fs-1 mb-2">👤</div>
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-1">{{ $user->email }}</p>
                <span class="badge 
                    @if($user->role === 'admin') bg-danger
                    @elseif($user->role === 'vet') bg-success
                    @else bg-primary
                    @endif">
                    {{ ucfirst($user->role) }}
                </span>
                <div class="mt-3">
                    <a href="/perfil/editar" class="btn btn-petcare btn-sm">Editar perfil</a>
                </div>
            </div>

        </div>
    </div>

@endsection