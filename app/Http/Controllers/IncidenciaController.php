<?php

namespace App\Http\Controllers;

use App\Enums\Estado;
use App\Models\Incidencia;
use Illuminate\Http\Request;

class IncidenciaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Incidencia::with(['cliente', 'tecnico', 'categoria']);

        if ($user->tipo !== 'tecnico') {
            $query->where('usuario_id', $user->id);
        }

        $query->when($request->filled('estado'), function ($builder) use ($request) {
            $estado = Estado::tryFrom($request->string('estado')->toString());

            abort_unless($estado, 422, 'El estado indicado no es válido.');
            $builder->where('estado', $estado);
        });
        $query->when($request->filled('categoria_id'), fn ($builder) => $builder->where('categoria_id', $request->integer('categoria_id')));

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
        ]);

        $incidencia = Incidencia::create([
            ...$data,
            'usuario_id' => $request->user()->id,
            'estado' => Estado::ABIERTO,
        ]);

        return response()->json($incidencia->load(['cliente', 'tecnico', 'categoria']), 201);
    }

    public function show(Request $request, Incidencia $incidencia)
    {
        $this->authorizeView($request, $incidencia);

        return response()->json($incidencia->load(['cliente', 'tecnico', 'categoria', 'comentarios.autor']));
    }

    public function take(Request $request, Incidencia $incidencia)
    {
        abort_if($request->user()->tipo !== 'tecnico', 403, 'Solo un técnico puede tomar incidencias.');
        abort_unless($incidencia->estado === Estado::ABIERTO, 409, 'Solo una incidencia abierta puede ser tomada.');

        $incidencia->update([
            'tecnico_id' => $request->user()->id,
            'estado' => Estado::EN_CURSO,
        ]);

        return response()->json($incidencia->load(['cliente', 'tecnico', 'categoria']));
    }

    public function resolve(Request $request, Incidencia $incidencia)
    {
        $data = $request->validate(['resolucion' => ['required', 'string', 'min:3']]);
        abort_if($request->user()->tipo !== 'tecnico', 403, 'Solo un técnico puede resolver incidencias.');
        abort_unless($incidencia->tecnico_id === $request->user()->id, 403, 'Solo el técnico asignado puede resolverla.');
        abort_unless($incidencia->estado === Estado::EN_CURSO, 409, 'La incidencia debe estar en proceso.');

        $incidencia->update([
            'resolucion' => $data['resolucion'],
            'estado' => Estado::FINALIZADO,
            'fecha_finalizacion' => now(),
        ]);

        return response()->json($incidencia->load(['cliente', 'tecnico', 'categoria']));
    }

    private function authorizeView(Request $request, Incidencia $incidencia): void
    {
        abort_if($request->user()->tipo !== 'tecnico' && $incidencia->usuario_id !== $request->user()->id, 403, 'No tenés permiso para ver esta incidencia.');
    }
}
