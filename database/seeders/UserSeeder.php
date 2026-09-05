<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // cargar las 5 categorías
        $this->call(CategoriaSeeder::class);

        // cliente
        Cliente::firstOrCreate(
            ['email' => 'cliente@test.com'],
            [
            'name'     => 'Maria cliente',
            'password' => Hash::make('123'),
            ]
        );

        // tecnico
        Tecnico::firstOrCreate(
            ['email' => 'tecnico@test.com'],
            [
            'name'     => 'Juan mecanico',
            'password' => Hash::make('123'),
            ]
        );
    }
}