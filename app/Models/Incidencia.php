<?php

namespace App\Models;

use App\Enums\Estado;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $table = 'incidencias';

    protected $fillable = [
        'usuario_id',
        'tecnico_id',
        'categoria_id',
        'titulo',
        'descripcion',
        'estado',
        'resolucion',
        'fecha_finalizacion',
    ];

    protected $casts = [
        'estado' => Estado::class,
        'fecha_finalizacion' => 'datetime',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'usuario_id');
    }

    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class, 'tecnico_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'incidencia_id');
    }

    // Métodos
    public function asignarTecnico(Tecnico $tecnico): void
    {
        $this->tecnico_id = $tecnico->id;
        $this->estado = Estado::EN_CURSO;
        $this->save();
    }

    public function cambiarEstado(Estado $nuevoEstado): void
    {
        $this->estado = $nuevoEstado;
        $this->save();
    }

    public function agregarResolucion(string $resolucion): void
    {
        $this->resolucion = $resolucion;
        $this->save();
    }

    public function finalizar(): void
    {
        $this->estado = Estado::FINALIZADO;
        $this->fecha_finalizacion = now();
        $this->save();
    }
}