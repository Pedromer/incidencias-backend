<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ComentarioController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {

    //logout
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    //usuarios
    Route::get('/usuarios', [\App\Http\Controllers\UserController::class, 'index']);
    Route::get('/usuarios/{usuario}', [\App\Http\Controllers\UserController::class, 'show']);
    Route::post('/usuarios', [\App\Http\Controllers\UserController::class, 'store']);
    Route::put('/usuarios/{usuario}', [\App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/usuarios/{usuario}', [\App\Http\Controllers\UserController::class, 'destroy']);

    //incidencias
    Route::get('/incidencias', [\App\Http\Controllers\IncidenciaController::class, 'index']);
    Route::get('/incidencias/{incidencia}', [\App\Http\Controllers\IncidenciaController::class, 'show']);
    Route::post('/incidencias', [\App\Http\Controllers\IncidenciaController::class, 'store']);
    Route::put('/incidencias/{incidencia}', [\App\Http\Controllers\IncidenciaController::class, 'update']);

    //categorias
    Route::get('/categorias', [CategoriaController::class, 'index']);

    //comentarios
    Route::get('/incidencias/{incidencia}/comentarios', [ComentarioController::class, 'index']);
    Route::post('/incidencias/{incidencia}/comentarios', [ComentarioController::class, 'store']);

});