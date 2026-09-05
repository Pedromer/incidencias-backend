<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'comentarios';

    protected $fillable = [
        'user_id',
        'incidencia_id',
        'contenido',
    ];

    // Usuario "1" o Tecnico "1" --> "0..*" Comentario : realiza
    public function autor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Incidencia "1" --> "0..*" Comentario : contiene
    public function incidencia()
    {
        return $this->belongsTo(Incidencia::class, 'incidencia_id');
    }
}