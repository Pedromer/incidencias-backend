<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Hardware',
                'descripcion' => 'Fallas físicas en componentes, discos, fuentes o periféricos.',
            ],
            [
                'nombre' => 'Software',
                'descripcion' => 'Problemas con el sistema operativo, controladores o programas instalados.',
            ],
            [
                'nombre' => 'Red',
                'descripcion' => 'Cortes de conectividad, cables de red, configuración IP o problemas de Wi-Fi.',
            ],
            [
                'nombre' => 'Impresoras',
                'descripcion' => 'Atascos de papel, falta de tóner o errores en colas de impresión.',
            ],
            [
                'nombre' => 'Otros',
                'descripcion' => 'Consultas generales o pedidos que no encajan en las demás clasificaciones.',
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(
                ['nombre' => $categoria['nombre']],
                $categoria
            );
        }
    }
}