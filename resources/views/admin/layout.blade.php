@extends('layouts.app')

@section('content')
<div class="d-flex" style="min-height: calc(100vh - 120px);">
   <nav class="bg-white border-end p-3" style="width: 220px;">
    <h6 class="text-petcare text-uppercase small fw-bold mb-3">Panel Admin</h6>
    <div class="nav flex-column gap-1">
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'fw-bold text-petcare' : 'text-dark' }}">
            Dashboard
        </a>
         <a href="{{ route('admin.veterinarias.index') }}"
           class="nav-link {{ request()->routeIs('admin.veterinarias.*') ? 'fw-bold text-petcare' : 'text-dark' }}">
            Veterinarias
         </a> 
        <a href="{{ route('admin.usuarios.index') }}"
           class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'fw-bold text-petcare' : 'text-dark' }}">
            Usuarios
        </a>
        <a href="{{ route('admin.veterinarios.index') }}"
           class="nav-link {{ request()->routeIs('admin.veterinarios.*') ? 'fw-bold text-petcare' : 'text-dark' }}">
            Veterinarios 
        </a>
        <a href="{{ route('admin.mascotas.index') }}"
           class="nav-link {{ request()->routeIs('admin.mascotas.*') ? 'fw-bold text-petcare' : 'text-dark' }}">
            Mascotas    
        </a>
    </div>
</nav>
    <div class="flex-grow-1 p-4">
        @yield('admin-content')
    </div>
</div>
@endsection