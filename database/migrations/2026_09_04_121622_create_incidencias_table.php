<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::create('incidencias', function (Blueprint $table) {
        $table->id();
        $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('tecnico_id')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
        $table->string('titulo');
        $table->text('descripcion');
        $table->string('estado')->default('abierto');
        $table->text('resolucion')->nullable();
        $table->dateTime('fecha_finalizacion')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
