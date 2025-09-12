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
            // Eliminar la restricción de unicidad en la combinación de nombre completo
            $table->dropUnique('usuarios_nombre_completo_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Restaurar la restricción de unicidad si es necesario
            $table->unique(['nombre', 'apellidoPaterno', 'apellidoMaterno'], 'usuarios_nombre_completo_unique');
        });
    }
};
