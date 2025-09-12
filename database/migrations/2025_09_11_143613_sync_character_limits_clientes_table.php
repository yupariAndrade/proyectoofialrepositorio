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
        Schema::table('clientes', function (Blueprint $table) {
            // Sincronizar límites de caracteres para que coincidan con las migraciones originales
            $table->string('nombre', 50)->change();
            $table->string('apellido', 50)->change();
            // CI, telefono y correoElectronico ya están correctos (20, 20, 150 respectivamente)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Revertir a los límites originales de la BD
            $table->string('nombre', 100)->change();
            $table->string('apellido', 100)->change();
        });
    }
};