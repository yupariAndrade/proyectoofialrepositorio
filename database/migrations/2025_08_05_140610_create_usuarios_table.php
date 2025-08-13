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
            $table->string('nombre', 100);
            $table->string('apellidoPaterno', 100)->nullable();
            $table->string('apellidoMaterno', 100)->nullable();
            $table->string('ci', 20)->unique()->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('email', 150)->unique()->nullable();
            $table->date('fechaIngreso')->nullable();
            $table->date('fechaFinal')->nullable();
            $table->boolean('estado');
            $table->foreignId('idRol')->constrained('roles');
            $table->timestamps();
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
