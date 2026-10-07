@extends('layouts.app')
 
@section('content')
 
    <div class="mb-4">
        <a href="{{ route('foro.index', ['categoria' => $categoria]) }}" class="text-decoration-none text-muted">← Volver al foro</a>
    </div>
 
    <div class="card card-petcare p-4 mx-auto" style="max-width: 720px;">
        <h3 class="fw-bold mb-4">Nuevo tema</h3>
 
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
 
        <form method="POST" action="{{ route('foro.store') }}">
            @csrf
 
            <div class="mb-3">
                <label for="categoria" class="form-label fw-semibold">Categoría</label>
                <select name="categoria" id="categoria" class="form-select">
                    @foreach ($categorias as $clave => $nombre)
                        <option value="{{ $clave }}" {{ old('categoria', $categoria) === $clave ? 'selected' : '' }}>
                            {{ $nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
 
            <div class="mb-3">
                <label for="titulo" class="form-label fw-semibold">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control"
                       maxlength="150" value="{{ old('titulo') }}" required>
            </div>
 
            <div class="mb-4">
                <label for="contenido" class="form-label fw-semibold">Contanos tu consulta</label>
                <textarea name="contenido" id="contenido" rows="6" class="form-control"
                          maxlength="2000" required>{{ old('contenido') }}</textarea>
            </div>
 
            <button type="submit" class="btn btn-petcare">Publicar</button>
        </form>
    </div>
 
@endsection