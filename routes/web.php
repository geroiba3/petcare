<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\VeterinarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VeterinariaController;
use App\Http\Controllers\Admin\UsuarioAdminController;
use App\Http\Controllers\Admin\MascotaAdminController;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

Route::get('/perfil', [UsuarioController::class, 'index'])->name('perfil');
Route::get('/perfil/editar', [UsuarioController::class, 'edit'])->name('perfil.edit');
Route::put('/perfil', [UsuarioController::class, 'update'])->name('perfil.update');
Route::post('/perfil/password', [UsuarioController::class, 'cambiarPassword'])->name('perfil.password');


Route::get('/mascotas', [MascotaController::class, 'index'])->name('mascotas');
Route::get('/mascotas/crear', [MascotaController::class, 'create'])->name('mascotas.crear');
Route::post('/mascotas', [MascotaController::class, 'store'])->name('mascotas.store');
Route::get('/mascotas/{id}/editar', [MascotaController::class, 'edit'])->name('mascotas.editar');
Route::put('/mascotas/{id}', [MascotaController::class, 'update'])->name('mascotas.update');
Route::delete('/mascotas/{id}', [MascotaController::class, 'destroy'])->name('mascotas.destroy');


Route::get('/turnos', [TurnoController::class, 'index'])->name('turnos');
Route::get('/turnos/crear', [TurnoController::class, 'create'])->name('turnos.crear');
Route::post('/turnos', [TurnoController::class, 'store'])->name('turnos.store');
Route::delete('/turnos/{id}', [TurnoController::class, 'destroy'])->name('turnos.destroy');

Route::get('/mascotas/{mascotaId}/historial', [HistorialController::class, 'index'])->name('historial.index');

Route::get('/veterinario', [VeterinarioController::class, 'index'])->name('veterinario.index');
Route::get('/veterinario/turnos/{turnoId}/completar', [VeterinarioController::class, 'completar'])->name('veterinario.completar');
Route::post('/veterinario/turnos/{turnoId}/completar', [VeterinarioController::class, 'guardarConsulta'])->name('veterinario.guardar');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('veterinarias', VeterinariaController::class)->except(['show']);
});
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('veterinarias', VeterinariaController::class)->except(['show']);
    Route::get('usuarios', [UsuarioAdminController::class, 'index'])->name('usuarios.index');
    Route::get('usuarios/{id}/editar', [UsuarioAdminController::class, 'edit'])->name('usuarios.edit');
    Route::put('usuarios/{id}', [UsuarioAdminController::class, 'update'])->name('usuarios.update');
    Route::put('usuarios/{id}/rol', [UsuarioAdminController::class, 'cambiarRol'])->name('usuarios.rol');
    Route::put('usuarios/{id}/activo', [UsuarioAdminController::class, 'toggleActivo'])->name('usuarios.activo');
    Route::get('veterinarios', [UsuarioAdminController::class, 'veterinarios'])->name('veterinarios.index');
    Route::get('mascotas', [MascotaAdminController::class, 'index'])->name('mascotas.index');
    Route::get('mascotas/{userId}/{mascotaId}/editar', [MascotaAdminController::class, 'edit'])->name('mascotas.edit');
    Route::put('mascotas/{userId}/{mascotaId}', [MascotaAdminController::class, 'update'])->name('mascotas.update');
    Route::put('mascotas/{userId}/{mascotaId}/activo', [MascotaAdminController::class, 'toggleActivo'])->name('mascotas.activo');
});
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');