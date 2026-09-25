<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\IncidenciaController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/categorias', [CategoriaController::class, 'index']);

    Route::post('/incidencias', [IncidenciaController::class, 'store']);
    Route::get('/mis-incidencias', [IncidenciaController::class, 'misIncidencias']);
    Route::get('/incidencias', [IncidenciaController::class,'todasParaTecnico',]);
    Route::put('/incidencias/{incidencia}/tomar', [IncidenciaController::class,'tomar',]);
});