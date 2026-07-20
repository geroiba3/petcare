@extends('admin.layout')

@section('admin-content')
<h3 class="mb-4">Dashboard</h3>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card-petcare p-3">
            <div class="text-muted small">Usuarios</div>
            <div class="fs-3 fw-bold">{{ $totalUsuarios }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-petcare p-3">
            <div class="text-muted small">Veterinarios</div>
            <div class="fs-3 fw-bold">{{ $totalVeterinarios }}</div>
        </div>
    </div>
</div>
@endsection