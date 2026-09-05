<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];


    // Incidencia "0..*" --> "1" Categoria
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'categoria_id');
    }
}