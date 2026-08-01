@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="d-flex align-items-center mb-4">
                <a href="/turnos" class="text-muted text-decoration-none me-3">← Volver</a>
                <h2 class="fw-bold mb-0">Sacar turno</h2>
            </div>

            @if(count($mascotas) === 0)
                <div class="card card-petcare p-4 text-center py-4">
                    <p class="text-muted mb-3">Necesitás agregar una mascota primero.</p>
                    <a href="/mascotas/crear" class="btn btn-petcare">Agregar mascota</a>
                </div>
            @elseif(count($veterinarias) === 0)
                <div class="card card-petcare p-4 text-center py-4">
                    <p class="text-muted mb-0">Todavía no hay veterinarias cargadas. Contactá a un administrador.</p>
                </div>
            @else

                {{-- Paso 1: elegir veterinaria, vet y fecha --}}
                <div class="card card-petcare p-4 mb-4">
                    <h5 class="fw-bold mb-3">1. Elegí veterinaria, veterinario y fecha</h5>

                    <form method="GET" action="/turnos/crear">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Veterinaria</label>
                            <select name="veterinaria_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Seleccioná una veterinaria</option>
                                @foreach ($veterinarias as $id => $vet)
                                    <option value="{{ $id }}" {{ $veterinariaSeleccionada === $id ? 'selected' : '' }}>
                                        {{ $vet['nombre'] }} — {{ $vet['direccion'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if ($veterinariaSeleccionada)
                            <div class="mb-3">
                                <label class="form-label fw-medium">Veterinario</label>
                                @if ($vets->isEmpty())
                                    <div class="text-muted small">Esta veterinaria todavía no tiene veterinarios asignados.</div>
                                @else
                                    <select name="vet_id" class="form-select" onchange="this.form.submit()">
                                        <option value="">Seleccioná un veterinario</option>
                                        @foreach ($vets as $vet)
                                            <option value="{{ $vet->id }}" {{ $vetSeleccionado == $vet->id ? 'selected' : '' }}>
                                                {{ $vet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        @endif

                        @if ($vetSeleccionado)
                            <div class="mb-2">
                                <label class="form-label fw-medium">Fecha</label>
                                <div class="d-flex gap-2">
                                    <input type="date" name="fecha" class="form-control"
                                           value="{{ $fechaSeleccionada }}" min="{{ now()->toDateString() }}">
                                    <button type="submit" class="btn btn-outline-secondary">Buscar</button>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>

                {{-- Paso 2: horarios disponibles + confirmar --}}
                @if ($vetSeleccionado && $fechaSeleccionada)
                    <div class="card card-petcare p-4">
                        <h5 class="fw-bold mb-3">2. Elegí el horario</h5>

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if (empty($horariosDisponibles))
                            <p class="text-muted mb-0">No hay horarios disponibles para esa fecha. Probá con otro día.</p>
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

                                <input type="hidden" name="veterinaria_id" value="{{ $veterinariaSeleccionada }}">
                                <input type="hidden" name="veterinaria_nombre" value="{{ $veterinarias[$veterinariaSeleccionada]['nombre'] }}">
                                <input type="hidden" name="vet_id" value="{{ $vetSeleccionado }}">
                                <input type="hidden" name="fecha" value="{{ $fechaSeleccionada }}">

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
                                    <label class="form-label fw-medium d-block">Horarios disponibles</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($horariosDisponibles as $h)
                                            <input type="radio" class="btn-check" name="hora" id="hora_{{ $h }}" value="{{ $h }}" autocomplete="off" required>
                                            <label class="btn btn-outline-secondary btn-sm" for="hora_{{ $h }}">{{ $h }}</label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-medium">Motivo de la consulta</label>
                                    <textarea name="motivo" class="form-control" rows="3" placeholder="Ej: Control anual, vacunación..." required></textarea>
                                </div>

                                <button type="submit" class="btn btn-petcare w-100">Confirmar turno</button>
                            </form>
                        @endif
                    </div>
                @endif
            @endif
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