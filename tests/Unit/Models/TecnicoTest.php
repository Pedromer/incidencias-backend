<?php

namespace Tests\Unit\Models;

use App\Models\Tecnico;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TecnicoTest extends TestCase
{
    use RefreshDatabase;

    public function test_tecnico_es_creado_con_tipo_tecnico(): void
    {
        $tecnico = Tecnico::create([
            'name'     => 'Carlos Rodriguez',
            'email'    => 'carlos@test.com',
            'password' => 'secret123',
        ]);

        $this->assertEquals('tecnico', $tecnico->tipo);
        $this->assertInstanceOf(Tecnico::class, $tecnico);
    }

    public function test_tecnico_filtrado_por_tipo_devuelve_tecnicos(): void
    {
        Tecnico::create(['name' => 'Tecnico A', 'email' => 'a@test.com', 'password' => '123']);
        Tecnico::create(['name' => 'Tecnico B', 'email' => 'b@test.com', 'password' => '123']);
        Cliente::create(['name' => 'Cliente C', 'email' => 'c@test.com', 'password' => '123']);

        $tecnicos = Tecnico::all();
        $this->assertCount(2, $tecnicos);
        $this->assertEquals('Tecnico A', $tecnicos->first()->name);
    }
}
