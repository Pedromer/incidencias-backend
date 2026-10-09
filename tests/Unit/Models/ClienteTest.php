<?php

namespace Tests\Unit\Models;

use App\Models\Cliente;
use App\Models\Tecnico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    public function test_cliente_es_creado_con_tipo_cliente(): void
    {
        $cliente = Cliente::create([
            'name'     => 'Juanita Perez',
            'email'    => 'juanita@test.com',
            'password' => 'secret123',
        ]);

        $this->assertEquals('cliente', $cliente->tipo);
        $this->assertInstanceOf(Cliente::class, $cliente);
    }

    public function test_cliente_filtrado_por_tipo_devuelve_clientes(): void
    {
        Cliente::create(['name' => 'Cliente A', 'email' => 'a@test.com', 'password' => '123']);
        Tecnico::create(['name' => 'Tecnico B', 'email' => 'b@test.com', 'password' => '123']);

        $clientes = Cliente::all();

        $this->assertCount(1, $clientes);
        $this->assertEquals('Cliente A', $clientes->first()->name);
    }

}