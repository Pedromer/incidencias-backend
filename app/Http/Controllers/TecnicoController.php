<?php

namespace App\Http\Controllers;

use App\Enums\Estado;
use App\Models\Incidencia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TecnicoController extends Controller
{
    public function tomar(Request $request, Incidencia $incidencia): JsonResponse
    {
        abort_unless($request->user()->tipo === 'tecnico', 403, 'Solo un técnico puede tomar incidencias.');

        $tomada = Incidencia::query()
            ->whereKey($incidencia->id)
            ->whereNull('tecnico_id')
            ->where('estado', Estado::ABIERTA->value)
            ->update([
                'tecnico_id' => $request->user()->id,
                'estado' => Estado::EN_CURSO->value,
                'updated_at' => now(),
            ]);

        if ($tomada === 0) {
            return response()->json([
                'message' => 'La incidencia ya fue tomada o no está abierta.',
            ], 409);
        }

        return response()->json([
            'message' => 'Incidencia tomada con éxito.',
            'incidencia' => $incidencia->fresh()->load('categoria:id,nombre', 'cliente:id,name,tipo', 'tecnico:id,name,tipo'),
        ], 200);
    }

    public function resolver(Request $request, Incidencia $incidencia): JsonResponse
    {
        abort_unless($request->user()->tipo === 'tecnico', 403, 'Solo un técnico puede resolver incidencias.');

        $validated = $request->validate([
            'resolucion' => 'required|string',
        ]);

        $actualizada = Incidencia::query()
            ->whereKey($incidencia->id)
            ->where('tecnico_id', $request->user()->id)
            ->where('estado', Estado::EN_CURSO->value)
            ->update([
                'resolucion' => $validated['resolucion'],
                'estado' => Estado::FINALIZADO->value,
                'fecha_finalizacion' => now(),
                'updated_at' => now(),
            ]);

        if ($actualizada === 0) {
            $incidencia->refresh();

            if ($incidencia->tecnico_id !== $request->user()->id) {
                abort(403, 'Solo el técnico asignado puede resolver esta incidencia.');
            }

            return response()->json([
                'message' => 'Solo se pueden resolver incidencias que estén en curso.',
            ], 409);
        }

        return response()->json([
            'message' => 'Resolución registrada con éxito.',
            'incidencia' => $incidencia->fresh()->load('categoria:id,nombre', 'cliente:id,name,tipo', 'tecnico:id,name,tipo'),
        ], 200);
    }
}
