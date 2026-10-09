<?php

namespace Tests\Unit\Models;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Tecnico;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_tosubclass_devuelve_instancia_correcta(): void
    {
        $usuario = User::create([
            'name'     => 'Josepe',
            'email'    => 'josepe@test.com',
            'password' => 'secret123',
            'tipo'     => 'tecnico',
        ]);

        $instanciaSubclase = $usuario->toSubclass();

        $this->assertInstanceOf(Tecnico::class, $instanciaSubclase);
    }
    public function test_user_tosubclass_devuelve_instancia_cliente_por_defecto(): void
    {
        $usuario = User::create([
            'name'     => 'Maria',
            'email'    => 'maria@test.com',
            'password' => 'secret123',
            // no se especifica 'tipo', por lo que debería ser 'cliente' por defecto
        ]);
        $instanciaSubclase = $usuario->toSubclass();

        $this->assertInstanceOf(Cliente::class, $instanciaSubclase);
    }
}
