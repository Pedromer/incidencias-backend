<?php

namespace App\Models;

use App\Enums\Estado;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class Tecnico extends User
{
    protected $table = 'users';

    protected static function booted(): void
    {
        // filtrar consultas para obtener solo registros de tipo 'tecnico'
        static::addGlobalScope('tecnico', function (Builder $builder) {
            $builder->where('tipo', 'tecnico');
        });

        // asignar automáticamente el tipo al instanciar y guardar un tecnico
        static::creating(function ($tecnico) {
            $tecnico->tipo = 'tecnico';
        });
    }

    // Tecnico "0..1" --> "0..*" Incidencia : atiende
    public function incidenciasAsignadas()
    {
        return $this->hasMany(Incidencia::class, 'tecnico_id');
    }

    public function verTodasLasIncidencias(): Collection
    {
        return Incidencia::with(['cliente', 'categoria'])->get();
    }

    public function consultarIncidencia(int $id): Incidencia
    {
        return Incidencia::with(['cliente', 'categoria', 'comentarios.autor'])->findOrFail($id);
    }

    public function tomarIncidencia(Incidencia $incidencia): void
    {
        $incidencia->asignarTecnico($this);
    }

    public function cambiarEstado(Incidencia $incidencia, Estado $estado): void
    {
        $incidencia->cambiarEstado($estado);
    }
}