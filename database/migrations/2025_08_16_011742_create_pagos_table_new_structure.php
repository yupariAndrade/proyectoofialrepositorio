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
            $table->foreignId('idTrabajo')->constrained('trabajos')->onDelete('cascade');
            $table->decimal('total', 10, 2);           // Precio del servicio × Cantidad
            $table->decimal('aCuenta', 10, 2);         // Lo que paga el cliente
            $table->decimal('saldo', 10, 2);           // Lo que falta por pagar
            $table->foreignId('idEstadoPago')->constrained('estados_pago');
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
