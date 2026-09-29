<?php

namespace App\Http\Controllers;

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

    public function index() // listado de incidencias con sus relaciones
    {
        $incidencias = Incidencia::with('categoria:id,nombre', 'user:id,name,tipo')
            ->get(['id', 'titulo', 'descripcion', 'categoria_id', 'user_id', 'created_at']);

        return response()->json($incidencias, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // para crear una nueva incidencia
    {
        $validated = $request->validate([
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $incidencia = Incidencia::create([
            'titulo'       => $validated['titulo'],
            'descripcion'  => $validated['descripcion'],
            'categoria_id' => $validated['categoria_id'],
            'user_id'      => $request->user()->id,
        ]);

        return response()->json([
            'message'    => 'Incidencia creada con éxito.',
            'incidencia' => $incidencia->load('categoria:id,nombre', 'user:id,name,tipo'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Incidencia $incidencia)  
    {
        $incidencia->load('categoria:id,nombre', 'user:id,name,tipo', 'comentarios.user:id,name,tipo');

        return response()->json($incidencia, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Incidencia $incidencia)
    {
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
    public function destroy(Incidencia $incidencia) // vacio ya que no quiero borrar incidencias
    {
        //
    }
}
