<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    ## migration usada para fixear el cambio de estado de incidencias de "abierto" a "abierta" en la tabla incidencias
    public function up(): void
    {
        DB::table('incidencias')
            ->where('estado', 'abierto')
            ->update(['estado' => 'abierta']);
    }

    public function down(): void
    {
        DB::table('incidencias')
            ->where('estado', 'abierta')
            ->update(['estado' => 'abierto']);
    }
};
