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
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('incidencia_id')->constrained('incidencias')->cascadeOnDelete();
            $table->text('contenido');
            $table->timestamps(); // maneja fechaCreacion con created_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
