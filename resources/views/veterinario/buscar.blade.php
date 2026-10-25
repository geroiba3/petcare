@extends('layouts.app')

@section('content')

    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('veterinario.index') }}" class="text-muted text-decoration-none me-3">← Volver</a>
        <h2 class="fw-bold mb-0">Buscar historial de mascota</h2>
    </div>

    <form method="GET" action="{{ route('veterinario.buscar') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" value="{{ $query }}" class="form-control" placeholder="Nombre de la mascota, del dueño o su email" autofocus>
            <button type="submit" class="btn btn-petcare">Buscar</button>
        </div>
    </form>

    @if($query !== '' && count($resultados) === 0)
        <div class="card card-petcare p-5 text-center">
            <h4 class="fw-bold">Sin resultados</h4>
            <p class="text-muted mb-0">No encontramos ninguna mascota ni dueño que coincida con "{{ $query }}".</p>
        </div>
    @elseif(count($resultados) > 0)
        <div class="row g-3">
            @foreach($resultados as $r)
                <div class="col-md-6">
                    <a href="{{ route('veterinario.historial', ['userId' => $r['user_id'], 'mascotaId' => $r['mascota_id']]) }}" class="text-decoration-none">
                        <div class="card card-petcare p-4 h-100">
                            <h5 class="fw-bold mb-1 text-dark">{{ $r['mascota_nombre'] }}</h5>
                            <p class="text-muted small mb-2">{{ ucfirst($r['especie']) }}</p>
                            <p class="text-muted mb-0">Dueño: {{ $r['dueno_nombre'] }} ({{ $r['dueno_email'] }})</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif

@endsection