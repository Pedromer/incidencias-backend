<?php

namespace App\Http\Controllers;

use App\Enums\Estado;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class IncidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //obtiene el detalle de una incidencia
    // GET /api/incidencias/{id}

    public function index(Request $request): JsonResponse ## obtiene la lista de incidencias con filtros y control de acceso
    {
        $user = $request->user(); ## obtiene el usuario autenticado

        // Eager loading para evitar N+1
        $query = Incidencia::with(['categoria', 'cliente', 'tecnico']);

        // SI ES CLIENTE, solo ve sus incidencias
        if ($user->tipo === 'cliente') {
            $query->where('cliente_id', $user->id);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->query('estado'));
        }

        // Filtro por categoría
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->query('categoria_id'));
        }

        // Filtros exclusivos de técnicos
        if ($user->tipo === 'tecnico') {
            if ($request->filled('tecnico_id')) {
                $query->where('tecnico_id', $request->query('tecnico_id'));
            }

            if ($request->boolean('sin_asignar')) {
                $query->whereNull('tecnico_id');
            }
        }

        $incidencias = $query->latest()->get();

        return response()->json($incidencias, 200);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // crea una nueva incidencia
    {
        abort_unless( ## control de acceso: solo un cliente puede crear incidencias
            $request->user()->tipo === 'cliente',
            403,
            'Solo un cliente puede crear incidencias.'
        );
        $validated = $request->validate([ ## validacion de datos de entrada
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $incidencia = Incidencia::create([ ## creacion propiamente dicha
            'titulo'       => $validated['titulo'],
            'descripcion'  => $validated['descripcion'],
            'categoria_id' => $validated['categoria_id'],
            'cliente_id'   => $request->user()->id, ##
            'estado'       => Estado::ABIERTA, ## siempre se crea abierta
        ]);

        return response()->json([
            'message'    => 'Incidencia creada con éxito.',
            'incidencia' => $incidencia->load('categoria:id,nombre', 'cliente:id,name,tipo', 'tecnico:id,name,tipo'),
        ], 201);

        /*
        body en json para postman:
        {
            "titulo": "Problema con la red",
            "descripcion": "No puedo conectarme a internet.",
            "categoria_id": 1
        }
        */
    }

    /**
     * Display the specified resource.
     * muestra el recurso especifico (detalle de la incidencia id X)
     * GET /api/incidencias/{id}
     */
    public function show(Request $request, Incidencia $incidencia): JsonResponse
    {
    if ($request->user()->tipo === 'cliente' ## control de acceso: verifica si el usuario es un cliente y si la incidencia pertenece a ese cliente
        && $incidencia->cliente_id !== $request->user()->id) {
        abort(404);
    }
        $incidencia->load(
            'categoria:id,nombre',
            'user:id,name,tipo',
            'cliente:id,name,tipo',
            'tecnico:id,name,tipo',
            'comentarios.user:id,name,tipo'
        );

        return response()->json($incidencia, 200);
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/incidencias/{id}
     * actualiza el recurso especifico
     */
public function update(Request $request, Incidencia $incidencia): JsonResponse
{
    $usuario = $request->user();

    if (
        $usuario->tipo !== 'cliente'
        || $incidencia->cliente_id !== $usuario->id
    ) {
        abort(404);
    }

    $validated = $request->validate([
        'titulo'       => 'sometimes|required|string|max:255',
        'descripcion'  => 'sometimes|required|string',
        'categoria_id' => 'sometimes|required|exists:categorias,id',
    ]);

    $incidencia->update($validated);

    return response()->json([
        'message'    => 'Incidencia actualizada con éxito.',
        'incidencia' => $incidencia->load('categoria:id,nombre', 'user:id,name,tipo'),
    ], 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incidencia $incidencia) ## por ahora no se implementa la eliminacion de incidencias
    {
        //
    }
}
