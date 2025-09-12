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
        // Agregar idServicio a detalle_trabajo
        Schema::table('detalle_trabajo', function (Blueprint $table) {
            $table->foreignId('idServicio')->nullable()->constrained('servicios');
        });
        
        // Remover idServicio de trabajos
        Schema::table('trabajos', function (Blueprint $table) {
            $table->dropForeign(['idServicio']);
            $table->dropColumn('idServicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir: agregar idServicio de vuelta a trabajos
        Schema::table('trabajos', function (Blueprint $table) {
            $table->foreignId('idServicio')->nullable()->constrained('servicios');
        });
        
        // Revertir: remover idServicio de detalle_trabajo
        Schema::table('detalle_trabajo', function (Blueprint $table) {
            $table->dropForeign(['idServicio']);
            $table->dropColumn('idServicio');
        });
    }
};
