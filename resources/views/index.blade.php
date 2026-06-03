@extends('layouts.app')

@section('content')

    <!-- Hero -->
    <div class="text-center py-5">
        <h1 class="title-main mb-3">El cuidado que tu mascota merece</h1>
        <p class="text-muted fs-5 mb-4">Gestioná turnos, historial médico y mucho más en un solo lugar.</p>
        <a href="/register" class="btn btn-petcare fs-5">Empezar ahora</a>
    </div>

    <!-- Características -->
    <div class="py-5 bg-white rounded-4 my-5 px-4">
        <h2 class="text-center fw-bold mb-5">¿Qué podés hacer con PetCare?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-petcare p-4 text-center h-100">
                    <div class="fs-1 mb-3">📅</div>
                    <h5 class="fw-bold">Turnos online</h5>
                    <p class="text-muted">Sacá turnos con tu veterinaria favorita sin llamar por teléfono.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-petcare p-4 text-center h-100">
                    <div class="fs-1 mb-3">📋</div>
                    <h5 class="fw-bold">Historial médico</h5>
                    <p class="text-muted">Accedé al historial completo de tu mascota en cualquier momento.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-petcare p-4 text-center h-100">
                    <div class="fs-1 mb-3">🏥</div>
                    <h5 class="fw-bold">Veterinarias cerca</h5>
                    <p class="text-muted">Encontrá veterinarias cercanas y sus horarios de atención.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Membresías -->
    <div class="py-5">
        <h2 class="text-center fw-bold mb-5">Nuestros planes</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-petcare p-4 text-center h-100">
                    <h5 class="fw-bold">Básico</h5>
                    <p class="text-petcare fs-2 fw-bold">Gratis</p>
                    <ul class="list-unstyled text-muted">
                        <li>✅ 1 mascota</li>
                        <li>✅ Turnos online</li>
                        <li>❌ Historial médico</li>
                        <li>❌ Consultas ilimitadas</li>
                    </ul>
                    <a href="/register" class="btn btn-petcare mt-3">Empezar</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-petcare p-4 text-center h-100 border-2" style="border-color: var(--color-primary) !important; border: 2px solid;">
                    <h5 class="fw-bold">Premium</h5>
                    <p class="text-petcare fs-2 fw-bold">$5.999<span class="fs-6 text-muted">/mes</span></p>
                    <ul class="list-unstyled text-muted">
                        <li>✅ 3 mascotas</li>
                        <li>✅ Turnos online</li>
                        <li>✅ Historial médico</li>
                        <li>❌ Consultas ilimitadas</li>
                    </ul>
                    <a href="/register" class="btn btn-petcare mt-3">Empezar</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-petcare p-4 text-center h-100">
                    <h5 class="fw-bold">Pro</h5>
                    <p class="text-petcare fs-2 fw-bold">$9.999<span class="fs-6 text-muted">/mes</span></p>
                    <ul class="list-unstyled text-muted">
                        <li>✅ Mascotas ilimitadas</li>
                        <li>✅ Turnos online</li>
                        <li>✅ Historial médico</li>
                        <li>✅ Consultas ilimitadas</li>
                    </ul>
                    <a href="/register" class="btn btn-petcare mt-3">Empezar</a>
                </div>
            </div>
        </div>
    </div>

@endsection