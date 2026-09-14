<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class Cliente extends User
{
    protected $table = 'users';

    //tipo de usuario
    protected static function booted(): void
    {
        // filtrar consultas para obtener solo registros de tipo 'cliente'
        static::addGlobalScope('cliente', function (Builder $builder) {
            $builder->where('tipo', 'cliente');
        });

        // asignar automáticamente el tipo al instanciar y guardar un cliente
        static::creating(function ($cliente) {
            $cliente->tipo = 'cliente';
        });
    }

    // Relación: Cliente "1" --> "0..*" Incidencia
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'usuario_id');
    }

    // Método del UML: + agregarIncidencia(titulo, descripcion, categoria) : Incidencia
    public function agregarIncidencia(string $titulo, string $descripcion, int $categoriaId): Incidencia
    {
        return Incidencia::create([
            'usuario_id'   => $this->id,
            'categoria_id' => $categoriaId,
            'titulo'       => $titulo,
            'descripcion'  => $descripcion,
        ]);
    }

    // Método del UML: + consultarMisIncidencias() : List<Incidencia>
    public function consultarMisIncidencias(): Collection
    {
        return $this->incidencias()->with(['categoria', 'tecnico'])->get();
    }

    // Método del UML: + borrarMiIncidencia(idIncidencia) : int
    public function borrarMiIncidencia(int $idIncidencia): int
    {
        return Incidencia::where('id', $idIncidencia)
            ->where('usuario_id', $this->id)
            ->delete();
    }
}