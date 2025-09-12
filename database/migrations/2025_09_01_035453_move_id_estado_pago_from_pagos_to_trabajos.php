<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar idEstadoPago a trabajos
        Schema::table('trabajos', function (Blueprint $table) {
            $table->foreignId('idEstadoPago')->nullable()->constrained('estados_pago');
        });
        
        // Copiar datos de pagos a trabajos antes de eliminar
        DB::statement('UPDATE trabajos t JOIN pagos p ON t.id = p.idTrabajo SET t.idEstadoPago = p.idEstadoPago');
        
        // Remover idEstadoPago de pagos
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['idEstadoPago']);
            $table->dropColumn('idEstadoPago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir: agregar idEstadoPago de vuelta a pagos
        Schema::table('pagos', function (Blueprint $table) {
            $table->foreignId('idEstadoPago')->nullable()->constrained('estados_pago');
        });
        
        // Revertir: remover idEstadoPago de trabajos
        Schema::table('trabajos', function (Blueprint $table) {
            $table->dropForeign(['idEstadoPago']);
            $table->dropColumn('idEstadoPago');
        });
    }
};
