<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Trabajos;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar si la columna ya existe
        if (!Schema::hasColumn('trabajos', 'slug')) {
            Schema::table('trabajos', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('idEstado');
            });

            // Generar slugs para registros existentes
            $trabajos = Trabajos::with(['cliente', 'servicio'])->get();
            foreach ($trabajos as $trabajo) {
                $cliente = $trabajo->cliente;
                $servicio = $trabajo->servicio;
                
                $baseSlug = Str::slug(
                    ($cliente ? $cliente->nombre . ' ' . $cliente->apellido : 'trabajo') . 
                    '-' . 
                    ($servicio ? $servicio->nombreServicio : 'servicio') . 
                    '-' . 
                    $trabajo->id
                );
                
                $slug = $baseSlug;
                $counter = 1;
                
                // Asegurar que el slug sea único
                while (Trabajos::where('slug', $slug)->where('id', '!=', $trabajo->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                $trabajo->update(['slug' => $slug]);
            }

            // Hacer el campo único después de generar todos los slugs
            Schema::table('trabajos', function (Blueprint $table) {
                $table->string('slug')->unique()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('trabajos', 'slug')) {
            Schema::table('trabajos', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
