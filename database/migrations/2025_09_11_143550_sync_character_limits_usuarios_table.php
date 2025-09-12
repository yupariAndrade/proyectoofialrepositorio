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
        Schema::table('usuarios', function (Blueprint $table) {
            // Sincronizar límites de caracteres para que coincidan con las migraciones originales
            $table->string('nombre', 50)->change();
            $table->string('apellidoPaterno', 50)->nullable()->change();
            $table->string('apellidoMaterno', 50)->nullable()->change();
            $table->string('direccion', 50)->nullable()->change();
            // CI, telefono y email ya están correctos (20, 20, 150 respectivamente)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Revertir a los límites originales de la BD
            $table->string('nombre', 100)->change();
            $table->string('apellidoPaterno', 100)->nullable()->change();
            $table->string('apellidoMaterno', 100)->nullable()->change();
            $table->string('direccion', 255)->nullable()->change();
        });
    }
};