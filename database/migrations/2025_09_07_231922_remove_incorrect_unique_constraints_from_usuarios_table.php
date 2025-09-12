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
        Schema::table('usuarios', function (Blueprint $table) {
            // Eliminar restricciones de unicidad incorrectas
            $table->dropUnique('usuarios_nombre_unique');
            $table->dropUnique('usuarios_apellido_paterno_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Restaurar las restricciones si es necesario
            $table->unique('nombre', 'usuarios_nombre_unique');
            $table->unique('apellidoPaterno', 'usuarios_apellido_paterno_unique');
        });
    }
};
