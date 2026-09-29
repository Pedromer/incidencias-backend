<?php

namespace Database\Seeders;

use App\Models\Incidencia;
use Illuminate\Database\Seeder;

class IncidenciaSeeder extends Seeder
{
    public function run(): void
    {
        Incidencia::firstOrCreate(
            ['titulo' => 'Problema con la impresora'],
            [
                'cliente_id' => 1,
                'tecnico_id' => 2,
                'categoria_id' => 1,
                'descripcion' => 'La impresora no imprime y hace un ruido extraño al intentar imprimir.',
                'estado' => \App\Enums\Estado::ABIERTA,
            ]
        );
    }
}