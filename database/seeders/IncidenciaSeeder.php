<?php

namespace Database\Seeders;

use App\Enums\Estado;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Incidencia;
use App\Models\Tecnico;
use Illuminate\Database\Seeder;

class IncidenciaSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = Cliente::where('email', 'cliente@test.com')->firstOrFail();
        $tecnico = Tecnico::where('email', 'tecnico@test.com')->firstOrFail();
        $categoria = Categoria::where('nombre', 'Impresoras')->firstOrFail();

        Incidencia::updateOrCreate(
            ['titulo' => 'Problema con la impresora'],
            [
                'cliente_id' => $cliente->id,
                'tecnico_id' => $tecnico->id,
                'categoria_id' => $categoria->id,
                'descripcion' => 'La impresora no imprime y hace un ruido extraño al intentar imprimir.',
                'estado' => Estado::EN_CURSO,
            ]
        );
    }
}