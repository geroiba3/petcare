<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});
Route::post('/login', [\App\Http\Controllers\Api\AuthApiController::class, 'login']);