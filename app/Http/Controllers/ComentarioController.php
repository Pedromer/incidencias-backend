<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function index(Request $request, Incidencia $incidencia)
    {
        $this->authorizeView($request, $incidencia);

        return response()->json($incidencia->comentarios()->with('autor')->oldest()->get());
    }

    public function store(Request $request, Incidencia $incidencia)
    {
        $this->authorizeView($request, $incidencia);
        $data = $request->validate(['contenido' => ['required', 'string', 'max:2000']]);

        return response()->json($incidencia->comentarios()->create([
            'user_id' => $request->user()->id,
            'contenido' => $data['contenido'],
        ])->load('autor'), 201);
    }

    private function authorizeView(Request $request, Incidencia $incidencia): void
    {
        abort_if($request->user()->tipo !== 'tecnico' && $incidencia->usuario_id !== $request->user()->id, 403, 'No tenés permiso para esta incidencia.');
    }
}
