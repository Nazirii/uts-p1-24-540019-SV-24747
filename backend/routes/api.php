<?php

use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Endpoint publik: hanya login
Route::post('/login', [AuthController::class, 'login']);

// Semua endpoint lain wajib token Sanctum (tanpa token -> 401)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::apiResource('agendas', AgendaController::class);
});
