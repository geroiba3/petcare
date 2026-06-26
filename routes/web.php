<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\TurnoController;

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
    Auth()->logout();
    return redirect('/');
})->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

Route::get('/mascotas', [MascotaController::class, 'index'])->name('mascotas');
Route::get('/mascotas/crear', [MascotaController::class, 'create'])->name('mascotas.crear');
Route::post('/mascotas', [MascotaController::class, 'store'])->name('mascotas.store');

Route::get('/turnos', [TurnoController::class, 'index'])->name('turnos');
Route::get('/turnos/crear', [TurnoController::class, 'create'])->name('turnos.crear');
Route::post('/turnos', [TurnoController::class, 'store'])->name('turnos.store');
Route::delete('/turnos/{id}', [TurnoController::class, 'destroy'])->name('turnos.destroy');

});
