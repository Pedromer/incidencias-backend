<?php

//gestiona el orden cronologico de los comentarios en una incidencia

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Incidencia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    /**
     * Listar los comentarios de una incidencia en orden cronológico.
     * GET /api/incidencias/{incidencia}/comentarios
     */
    public function index(int $incidenciaId): JsonResponse
    {
        $incidencia = Incidencia::findOrFail($incidenciaId);

        $comentarios = $incidencia->comentarios()
            ->with('user:id,name,tipo')
            ->oldest()
            ->get();

        return response()->json($comentarios, 200);
    }

    /**
     * Crear un nuevo comentario para una incidencia.
     * POST /api/incidencias/{incidencia}/comentarios
     */
    public function store(Request $request, int $incidenciaId): JsonResponse
    {
        $incidencia = Incidencia::findOrFail($incidenciaId);

        $validated = $request->validate([
            'contenido' => 'required|string',
        ]);

        $comentario = $incidencia->comentarios()->create([
            'contenido'  => $validated['contenido'],
            'user_id'    => $request->user()->id,
        ]);

        return response()->json([
            'message'    => 'Comentario registrado con éxito.',
            'comentario' => $comentario->load('user:id,name,tipo'),
        ], 201);
    }
}