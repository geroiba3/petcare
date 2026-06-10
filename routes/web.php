<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MascotaController;

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
});