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
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('idCliente')->constrained('clientes');
            //$table->foreignId('idServicio')->constrained('servicios');
            $table->foreignId('idUsuario')->constrained('usuarios');
            $table->date('fechaRegistro');
            $table->date('fechaEntrega')->nullable();
            $table->foreignId('idEstado')->constrained('estados_trabajo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajos');
    }
};
