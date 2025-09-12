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
        Schema::table('servicios', function (Blueprint $table) {
            // Sincronizar límites de caracteres para que coincidan con las migraciones originales
            $table->string('nombreServicio', 50)->change();
            $table->string('descripcion', 100)->nullable()->change();
            // precioReferencial e imagenReferencia ya están correctos (DECIMAL(10,2) y VARCHAR(255))
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            // Revertir a los límites originales de la BD
            $table->string('nombreServicio', 100)->change();
            $table->string('descripcion', 255)->nullable()->change();
        });
    }
};