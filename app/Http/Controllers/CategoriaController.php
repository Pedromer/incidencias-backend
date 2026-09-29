<?php

//Provee el endpoint de solo lectura para listar las categorías disponibles

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    /**
     * Listar todas las categorías disponibles.
     * GET /api/categorias
     */
    public function index(): JsonResponse
    {
        $categorias = Categoria::all(['id', 'nombre']);

        return response()->json($categorias, 200);
    }
}
