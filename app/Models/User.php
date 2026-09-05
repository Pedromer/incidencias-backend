<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // esto es para
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // metodo comun para cliente y tecnico
    public function comentar(int $idIncidencia, string $contenido): Comentario
    {
        return Comentario::create([
            'user_id'       => $this->id,
            'incidencia_id' => $idIncidencia,
            'contenido'     => $contenido,
        ]);
    }
    
    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'user_id');
    }

    // metodo para obtener la clase hija (cliente o tecnico) segun el tipo
    public function claseHija(): Cliente|Tecnico
    {
    return match ($this->tipo) {
        'tecnico' => Tecnico::withoutGlobalScopes()->findOrFail($this->id),
        default   => Cliente::withoutGlobalScopes()->findOrFail($this->id),
    };
}
}