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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('idPago');
            $table->foreignId('idTrabajo')->constrained('trabajos');
            $table->decimal('monto', 10, 2);
            $table->date('fecha');
            $table->string('comentario', 255)->nullable();
            $table->foreignId('idEstadoPago')->constrained('estados_pago');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
