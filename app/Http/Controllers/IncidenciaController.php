<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\Estado;
use App\Models\Tecnico;

class IncidenciaController extends Controller
{
    public function store(Request $request)
    {
        if ($request->user()->tipo !== 'cliente') {
            return response()->json([
                'message' => 'Solo los clientes pueden crear incidencias.',
            ], 403);
        }

        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'categoria_id' => [
                'required',
                'integer',
                Rule::exists('categorias', 'id'),
            ],
        ]);

        $incidencia = Incidencia::create([
            'usuario_id' => $request->user()->id,
            'categoria_id' => $datos['categoria_id'],
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'estado' => 'abierto',
        ]);

        return response()->json([
            'message' => 'Incidencia creada correctamente.',
            'incidencia' => $incidencia->load('categoria'),
        ], 201);
    }
    public function misIncidencias(Request $request)
    {
        if ($request->user()->tipo !== 'cliente') {
            return response()->json([
                'message' => 'Solo los clientes pueden consultar sus incidencias.',
            ], 403);
        }

        $incidencias = Incidencia::where(
            'usuario_id',
            $request->user()->id
        )
            ->with(['categoria', 'tecnico'])
            ->latest()
            ->get();

        return response()->json($incidencias, 200);
    }
    public function todasParaTecnico(Request $request)
    {
        if ($request->user()->tipo !== 'tecnico') {
            return response()->json([
                'message' => 'Solo los técnicos pueden consultar todas las incidencias.',
            ], 403);
        }

        $incidencias = Incidencia::with([
            'cliente',
            'categoria',
            'tecnico',
        ])
            ->latest()
            ->get();

        return response()->json($incidencias, 200);
    }

    public function tomar(Request $request, Incidencia $incidencia)
    {
        if ($request->user()->tipo !== 'tecnico') {
            return response()->json([
                'message' => 'Solo los técnicos pueden tomar incidencias.',
            ], 403);
        }

        if ($incidencia->tecnico_id !== null) {
            return response()->json([
                'message' => 'La incidencia ya tiene un técnico asignado.',
            ], 409);
        }

        $tecnico = Tecnico::withoutGlobalScopes()
            ->findOrFail($request->user()->id);

        $incidencia->tecnico_id = $tecnico->id;
        $incidencia->estado = Estado::EN_CURSO;
        $incidencia->save();

        return response()->json([
            'message' => 'Incidencia tomada correctamente.',
            'incidencia' => $incidencia->load([
                'cliente',
                'categoria',
                'tecnico',
            ]),
        ], 200);
    }
}
