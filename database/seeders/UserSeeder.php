<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Tecnico;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        Cliente::firstOrCreate(
            ['email' => 'cliente@test.com'],
            [
                'name'     => 'Maria cliente',
                'password' => '123',
                'tipo'     => 'cliente',
            ]
        );

        Tecnico::firstOrCreate(
            ['email' => 'tecnico@test.com'],
            [
                'name'     => 'Juan mecanico',
                'password' => '123',
                'tipo'     => 'tecnico',
            ]
        );
    }
}