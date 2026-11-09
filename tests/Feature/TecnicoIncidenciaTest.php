<?php

namespace Tests\Feature;

use App\Enums\Estado;
use App\Models\Incidencia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TecnicoIncidenciaTest extends TestCase
{
    use RefreshDatabase;

    public function test_tecnico_puede_iniciar_sesion(): void
    {
        User::create([
            'name' => 'Técnico',
            'email' => 'tecnico@example.com',
            'password' => 'password',
            'tipo' => 'tecnico',
        ]);

        $this->postJson('/api/login', [
            'email' => 'tecnico@example.com',
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJsonPath('user.tipo', 'tecnico')
            ->assertJsonStructure(['token']);
    }

    public function test_tecnico_puede_tomar_una_incidencia_abierta(): void
    {
        $tecnico = $this->crearUsuario('tecnico');
        $incidencia = $this->crearIncidencia();
        Sanctum::actingAs($tecnico);

        $this->putJson("/api/incidencias/{$incidencia->id}/tomar")
            ->assertOk()
            ->assertJsonPath('incidencia.estado', Estado::EN_CURSO->value)
            ->assertJsonPath('incidencia.tecnico_id', $tecnico->id);

        $this->assertDatabaseHas('incidencias', [
            'id' => $incidencia->id,
            'tecnico_id' => $tecnico->id,
            'estado' => Estado::EN_CURSO->value,
        ]);
    }

    public function test_tecnico_lista_y_consulta_incidencias(): void
    {
        Sanctum::actingAs($this->crearUsuario('tecnico'));
        $incidencia = $this->crearIncidencia();

        $this->getJson('/api/incidencias')
            ->assertOk()
            ->assertJsonPath('0.id', $incidencia->id);

        $this->getJson("/api/incidencias/{$incidencia->id}")
            ->assertOk()
            ->assertJsonPath('id', $incidencia->id);
    }

    public function test_cliente_no_puede_tomar_una_incidencia(): void
    {
        Sanctum::actingAs($this->crearUsuario('cliente'));
        $incidencia = $this->crearIncidencia();

        $this->putJson("/api/incidencias/{$incidencia->id}/tomar")
            ->assertForbidden();
    }

    public function test_tecnico_asignado_puede_registrar_la_resolucion(): void
    {
        $tecnico = $this->crearUsuario('tecnico');
        $incidencia = $this->crearIncidencia([
            'tecnico_id' => $tecnico->id,
            'estado' => Estado::EN_CURSO,
        ]);
        Sanctum::actingAs($tecnico);

        $this->putJson("/api/incidencias/{$incidencia->id}/resolver", [
            'resolucion' => 'Se restableció el servicio.',
        ])
            ->assertOk()
            ->assertJsonPath('incidencia.estado', Estado::FINALIZADO->value)
            ->assertJsonPath('incidencia.resolucion', 'Se restableció el servicio.');

        $this->assertDatabaseHas('incidencias', [
            'id' => $incidencia->id,
            'estado' => Estado::FINALIZADO->value,
            'resolucion' => 'Se restableció el servicio.',
        ]);
        $this->assertNotNull($incidencia->fresh()->fecha_finalizacion);
    }

    public function test_otro_tecnico_no_puede_resolver_una_incidencia_asignada(): void
    {
        $asignado = $this->crearUsuario('tecnico');
        $otroTecnico = $this->crearUsuario('tecnico', 'otro@example.com');
        $incidencia = $this->crearIncidencia([
            'tecnico_id' => $asignado->id,
            'estado' => Estado::EN_CURSO,
        ]);
        Sanctum::actingAs($otroTecnico);

        $this->putJson("/api/incidencias/{$incidencia->id}/resolver", [
            'resolucion' => 'Resuelto.',
        ])->assertForbidden();
    }

    private function crearUsuario(string $tipo, string $email = 'usuario@example.com'): User
    {
        return User::create([
            'name' => ucfirst($tipo),
            'email' => $email,
            'password' => 'password',
            'tipo' => $tipo,
        ]);
    }

    private function crearIncidencia(array $atributos = []): Incidencia
    {
        $cliente = $this->crearUsuario('cliente', 'cliente@example.com');
        $categoriaId = DB::table('categorias')->insertGetId([
            'nombre' => 'General',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Incidencia::create(array_merge([
            'cliente_id' => $cliente->id,
            'categoria_id' => $categoriaId,
            'titulo' => 'Problema de prueba',
            'descripcion' => 'Descripción de prueba',
            'estado' => Estado::ABIERTA,
        ], $atributos));
    }
}
