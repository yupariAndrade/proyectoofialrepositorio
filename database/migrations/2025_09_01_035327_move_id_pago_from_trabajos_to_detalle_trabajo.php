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
        // Agregar idPago a detalle_trabajo
        Schema::table('detalle_trabajo', function (Blueprint $table) {
            $table->foreignId('idPago')->nullable()->constrained('pagos', 'idPago');
        });
        
        // Remover idPago de trabajos
        Schema::table('trabajos', function (Blueprint $table) {
            $table->dropForeign(['idPago']);
            $table->dropColumn('idPago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir: agregar idPago de vuelta a trabajos
        Schema::table('trabajos', function (Blueprint $table) {
            $table->foreignId('idPago')->nullable()->constrained('pagos', 'idPago');
        });
        
        // Revertir: remover idPago de detalle_trabajo
        Schema::table('detalle_trabajo', function (Blueprint $table) {
            $table->dropForeign(['idPago']);
            $table->dropColumn('idPago');
        });
    }
};
