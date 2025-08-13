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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id');
            $table->string('nombre', 100);
            $table->string('apellido', 100)->nullable();
            $table->string('ci', 20)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('correoElectronico', 150)->nullable();
            $table->foreignId('idUsuario')->constrained('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
