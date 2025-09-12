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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id');
            $table->string('nombre', 50);
            $table->string('apellidoPaterno', 50)->nullable();
            $table->string('apellidoMaterno', 50)->nullable();
            $table->string('ci', 20)->unique()->nullable();
            $table->string('telefono', 20)->unique()->nullable();
            $table->string('direccion', 50)->nullable();
            $table->string('email', 150)->unique()->nullable();
            $table->string('password')->nullable(); // Campo password agregado
            $table->date('fechaIngreso')->nullable();
            $table->date('fechaFinal')->nullable();
            $table->boolean('estado');
            $table->foreignId('idRol')->constrained('roles');
            $table->timestamps();
            
            // Índice compuesto para evitar nombres completos duplicados
            $table->unique(['nombre', 'apellidoPaterno', 'apellidoMaterno'], 'usuarios_nombre_completo_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
