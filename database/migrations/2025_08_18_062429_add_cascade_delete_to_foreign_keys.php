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
        Schema::table('detalle_trabajo', function (Blueprint $table) {
            // Eliminar la restricción existente
            $table->dropForeign(['idTrabajo']);
            
            // Agregar la nueva restricción con cascade
            $table->foreign('idTrabajo')->references('id')->on('trabajos')->onDelete('cascade');
        });

        Schema::table('bitacora_trabajos', function (Blueprint $table) {
            // Eliminar la restricción existente
            $table->dropForeign(['idTrabajo']);
            
            // Agregar la nueva restricción con cascade
            $table->foreign('idTrabajo')->references('id')->on('trabajos')->onDelete('cascade');
        });

        Schema::table('asignaciones_trabajos', function (Blueprint $table) {
            // Eliminar la restricción existente
            $table->dropForeign(['idTrabajo']);
            
            // Agregar la nueva restricción con cascade
            $table->foreign('idTrabajo')->references('id')->on('trabajos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_trabajo', function (Blueprint $table) {
            // Eliminar la restricción con cascade
            $table->dropForeign(['idTrabajo']);
            
            // Restaurar la restricción original sin cascade
            $table->foreign('idTrabajo')->references('id')->on('trabajos');
        });

        Schema::table('bitacora_trabajos', function (Blueprint $table) {
            // Eliminar la restricción con cascade
            $table->dropForeign(['idTrabajo']);
            
            // Restaurar la restricción original sin cascade
            $table->foreign('idTrabajo')->references('id')->on('trabajos');
        });

        Schema::table('asignaciones_trabajos', function (Blueprint $table) {
            // Eliminar la restricción con cascade
            $table->dropForeign(['idTrabajo']);
            
            // Restaurar la restricción original sin cascade
            $table->foreign('idTrabajo')->references('id')->on('trabajos');
        });
    }
};
