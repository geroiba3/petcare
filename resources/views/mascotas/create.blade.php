@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="d-flex align-items-center mb-4">
                <a href="/mascotas" class="text-muted text-decoration-none me-3">← Volver</a>
                <h2 class="fw-bold mb-0">Agregar mascota</h2>
            </div>

            <div class="card card-petcare p-4">
                <form method="POST" action="/mascotas" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-3 text-center">
                        <label class="form-label fw-medium d-block">Foto de perfil</label>
                        <img id="preview" src="https://via.placeholder.com/120x120?text=🐾" class="rounded-circle mb-2" width="120" height="120" style="object-fit: cover;">
                        <input type="file" name="foto" accept="image/*" class="form-control" onchange="previsualizar(this)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Firulais" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Especie</label>
                        <select name="especie" class="form-select" required>
                            <option value="">Seleccioná una opción</option>
                            <option value="perro"> Perro</option>
                            <option value="gato"> Gato</option>
                            <option value="ave"> Ave</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Raza</label>
                        <input type="text" name="raza" class="form-control" placeholder="Ej: Labrador">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-petcare w-100">Guardar mascota</button>
                </form>
            </div>

        </div>
    </div>

    <script>
        function previsualizar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById('preview').src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

@endsection