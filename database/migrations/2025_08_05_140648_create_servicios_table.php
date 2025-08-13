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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id('id');
            $table->string('nombreServicio', 100);
            $table->decimal('precioReferencial', 10, 2);
            $table->string('descripcion', 255)->nullable();
            $table->boolean('estado');
            $table->string('imagenReferencia', 255)->nullable();
            $table->foreignId('idUsuario')->constrained('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
