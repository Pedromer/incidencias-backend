<?php

//gestiona el orden cronologico de los comentarios en una incidencia

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Incidencia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    
     //Listar los comentarios de una incidencia en orden cronológico.
     //GET /api/incidencias/{incidencia}/comentarios
    public function index(Request $request, int $incidenciaId): JsonResponse
    {
        $incidencia = Incidencia::findOrFail($incidenciaId); ## busca la incidencia por su ID ## si no existe devuelve error 

        if ( ## control de acceso: verifica si el usuario es un cliente y si la incidencia pertenece a ese cliente
            $request->user()->tipo === 'cliente'
            && $incidencia->cliente_id !== $request->user()->id
        ) {
            abort(404);
        }

        $comentarios = $incidencia->comentarios()
            ->with('user:id,name,tipo')
            ->oldest()
            ->get();

        return response()->json($comentarios, 200); ## respuesta en json
    }

     //Crear un nuevo comentario para una incidencia.
     //POST /api/incidencias/{incidencia}/comentarios
     
    public function store(Request $request, int $incidenciaId): JsonResponse ## almacena un nuevo comentario para la incidencia
    {
        $incidencia = Incidencia::findOrFail($incidenciaId);

        if ( ## control de acceso: verifica si el usuario es un cliente y si la incidencia pertenece a ese cliente
            $request->user()->tipo === 'cliente'
            && $incidencia->cliente_id !== $request->user()->id
        ) {
            abort(404);
        }

        $validated = $request->validate([ ## valida los datos de entrada
            'contenido' => 'required|string', ##
        ]);

        $comentario = $incidencia->comentarios()->create([ ## crea un nuevo comentario asociado a la incidencia
            'contenido' => $validated['contenido'],
            'user_id' => $request->user()->id,
        ]);

        return response()->json([ ## respuesta en json con el comentario creado
            'message' => 'Comentario registrado con éxito.',
            'comentario' => $comentario->load('user:id,name,tipo'),
        ], 201);
    }
}