@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="fw-bold mb-5">Mi perfil</h2>

            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            {{-- Datos personales --}}
            <div class="card card-petcare p-4 mb-4">
                <h5 class="fw-bold mb-4">Datos personales</h5>

                <form method="POST" action="/perfil" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Foto de perfil</label>
                        <input type="file" name="foto" accept="image/*" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-petcare w-100">Guardar cambios</button>
                </form>
            </div>


            {{-- Horario de atención (solo veterinarios) --}}
            @if ($user->isVet())
                <div class="card card-petcare p-4 mb-4">
                    <h5 class="fw-bold mb-4">Horario de atención</h5>

                    <form method="POST" action="{{ route('perfil.horario') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-medium d-block">Días que atendés</label>
                            @php
                                $diasSemana = [
                                    'lunes' => 'Lun', 'martes' => 'Mar', 'miercoles' => 'Mié',
                                    'jueves' => 'Jue', 'viernes' => 'Vie', 'sabado' => 'Sáb', 'domingo' => 'Dom',
                                ];
                                $diasGuardados = $horario['dias'] ?? [];
                            @endphp
                            @foreach ($diasSemana as $valor => $label)
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" name="dias[]" value="{{ $valor }}"
                                           class="form-check-input"
                                           id="dia_{{ $valor }}"
                                           {{ in_array($valor, $diasGuardados) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dia_{{ $valor }}">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Desde</label>
                                <input type="time" name="hora_inicio" class="form-control"
                                       value="{{ $horario['hora_inicio'] ?? '09:00' }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Hasta</label>
                                <input type="time" name="hora_fin" class="form-control"
                                       value="{{ $horario['hora_fin'] ?? '18:00' }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Duración de cada turno</label>
                                <select name="duracion_min" class="form-select">
                                    @foreach ([15, 20, 30, 45, 60] as $min)
                                        <option value="{{ $min }}" {{ ($horario['duracion_min'] ?? 30) == $min ? 'selected' : '' }}>
                                            {{ $min }} min
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-petcare w-100">Guardar horario</button>
                    </form>
                </div>
            @endif
            {{-- Cambiar contraseña --}}
            <div class="card card-petcare p-4">
                <h5 class="fw-bold mb-4">Cambiar contraseña</h5>

                <form method="POST" action="/perfil/password">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Contraseña actual</label>
                        <input type="password" name="password_actual" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nueva contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-petcare w-100">Cambiar contraseña</button>
                </form>
            </div>

        </div>
    </div>

@endsection