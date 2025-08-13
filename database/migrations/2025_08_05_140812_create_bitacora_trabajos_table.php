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
        Schema::create('bitacora_trabajos', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('idTrabajo')->constrained('trabajos');
            $table->foreignId('idUsuario')->constrained('usuarios');
            $table->string('accion', 50)->nullable();
            $table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_trabajos');
    }
};
