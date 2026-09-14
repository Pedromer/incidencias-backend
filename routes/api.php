<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\IncidenciaController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/categorias', [CategoriaController::class, 'index']);
    Route::get('/incidencias', [IncidenciaController::class, 'index']);
    Route::post('/incidencias', [IncidenciaController::class, 'store']);
    Route::get('/incidencias/{incidencia}', [IncidenciaController::class, 'show']);
    Route::put('/incidencias/{incidencia}/tomar', [IncidenciaController::class, 'take']);
    Route::put('/incidencias/{incidencia}/resolver', [IncidenciaController::class, 'resolve']);
    Route::get('/incidencias/{incidencia}/comentarios', [ComentarioController::class, 'index']);
    Route::post('/incidencias/{incidencia}/comentarios', [ComentarioController::class, 'store']);
});