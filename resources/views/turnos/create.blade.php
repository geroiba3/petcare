@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="d-flex align-items-center mb-4">
                <a href="/turnos" class="text-muted text-decoration-none me-3">← Volver</a>
                <h2 class="fw-bold mb-0">Sacar turno</h2>
            </div>

            <div class="card card-petcare p-4">

                @if(count($mascotas) === 0)
                    <div class="text-center py-4">
                        <p class="text-muted mb-3">Necesitás agregar una mascota primero.</p>
                        <a href="/mascotas/crear" class="btn btn-petcare">Agregar mascota</a>
                    </div>
                @else
                    <form method="POST" action="/turnos">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                @foreach ($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-medium">Mascota</label>
                            <select name="mascota_select" class="form-select" required onchange="actualizarMascota(this)">
                                <option value="">Seleccioná una mascota</option>
                                @foreach($mascotas as $id => $mascota)
                                    <option value="{{ $id }}|{{ $mascota['nombre'] }}">{{ $mascota['nombre'] }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="mascota_id" id="mascota_id">
                            <input type="hidden" name="mascota_nombre" id="mascota_nombre">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Fecha</label>
                            <input type="date" name="fecha" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Hora</label>
                            <input type="time" name="hora" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Motivo de la consulta</label>
                            <textarea name="motivo" class="form-control" rows="3" placeholder="Ej: Control anual, vacunación..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-petcare w-100">Confirmar turno</button>
                    </form>
                @endif
            </div>

        </div>
    </div>

    <script>
        function actualizarMascota(select) {
            const [id, nombre] = select.value.split('|');
            document.getElementById('mascota_id').value = id;
            document.getElementById('mascota_nombre').value = nombre;
        }
    </script>

@endsection