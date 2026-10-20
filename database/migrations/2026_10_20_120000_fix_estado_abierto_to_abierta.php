<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix rows written with the old backing value 'abierto' before the
     * Estado enum was corrected to use 'abierta'.
     */
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
