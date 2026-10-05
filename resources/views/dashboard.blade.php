@extends('layouts.app')

@section('content')

    <h2 class="fw-bold mb-5">Panel principal</h2>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="/mascotas" class="text-decoration-none">
                <div class="card card-petcare p-4 text-center">
                    <div class="fs-1 mb-3">🐶</div>
                    <h5 class="fw-bold text-dark">Mis mascotas</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="/turnos" class="text-decoration-none">
                <div class="card card-petcare p-4 text-center">
                    <div class="fs-1 mb-3">📅</div>
                    <h5 class="fw-bold text-dark">Agenda</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="/foro" class="text-decoration-none">
                <div class="card card-petcare p-4 text-center">
                    <div class="fs-1 mb-3">🗣️</div>
                    <h5 class="fw-bold text-dark">Foro</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="/veterinarias" class="text-decoration-none">
                <div class="card card-petcare p-4 text-center">
                    <div class="fs-1 mb-3">🏥</div>
                    <h5 class="fw-bold text-dark">Veterinarias</h5>
                </div>
            </a>
        </div>
    </div>

@endsection