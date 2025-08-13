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
        Schema::create('asignaciones_trabajos', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('idTrabajo')->constrained('trabajos');
            $table->foreignId('idUsuario')->constrained('usuarios');
            $table->string('turno', 8)->nullable();
            $table->date('fechaAsignacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones_trabajos');
    }
};
