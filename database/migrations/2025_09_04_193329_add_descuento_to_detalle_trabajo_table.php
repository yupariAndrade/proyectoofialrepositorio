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
        Schema::table('detalle_trabajo', function (Blueprint $table) {
            $table->decimal('descuento', 8, 2)->default(0)->after('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_trabajo', function (Blueprint $table) {
            $table->dropColumn('descuento');
        });
    }
};
