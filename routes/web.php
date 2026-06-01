<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('auth.login');
});
Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
});
Route::get('/register', function () {
    return view('auth.register');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});