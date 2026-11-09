<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\TecnicoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {

    //logout
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    //incidencias
    Route::get('/incidencias', [IncidenciaController::class, 'index']); // mostrar todas las incidencias del usuario
    // 1. Metodo: get, 2. URL: /api/incidencias, 3. Controlador: IncidenciaController, 4. Metodo del controlador: index
    Route::get('/incidencias/{incidencia}', [IncidenciaController::class, 'show']);
    Route::post('/incidencias', [IncidenciaController::class, 'store']);
    Route::put('/incidencias/{incidencia}', [IncidenciaController::class, 'update']);

    // Operaciones específicas del técnico
    Route::put('/incidencias/{incidencia}/tomar', [TecnicoController::class, 'tomar']);
    Route::put('/incidencias/{incidencia}/resolver', [TecnicoController::class, 'resolver']);


    //categorias
    Route::get('/categorias', [CategoriaController::class, 'index']);

    //comentarios
    Route::get('/incidencias/{incidencia}/comentarios', [ComentarioController::class, 'index']);
    Route::post('/incidencias/{incidencia}/comentarios', [ComentarioController::class, 'store']);

    //usuarios (por ahora no es necesario)

    //para filtrar por estado, categoria o tecnico, se puede usar query params:
    // /api/incidencias?estado=ABIERTA
    // /api/incidencias?categoria=REDES
    // /api/incidencias?tecnico_id=1
    
});