<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VerificarDetalleTrabajo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verificar:detalle-trabajo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica y corrige los datos de detalle_trabajo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando tabla detalle_trabajo...');

        // Verificar si la tabla existe
        if (!Schema::hasTable('detalle_trabajo')) {
            $this->error('❌ La tabla detalle_trabajo no existe');
            return 1;
        }

        // Verificar si el campo 'otros' existe
        if (Schema::hasColumn('detalle_trabajo', 'otros')) {
            $this->warn('⚠️  El campo "otros" aún existe en la tabla');
            
            // Migrar datos de 'otros' a 'descripcion'
            $this->info('🔄 Migrando datos de "otros" a "descripcion"...');
            
            $detalles = DB::table('detalle_trabajo')->get();
            $migrados = 0;
            
            foreach ($detalles as $detalle) {
                if (!empty($detalle->otros) && empty($detalle->descripcion)) {
                    DB::table('detalle_trabajo')
                        ->where('id', $detalle->id)
                        ->update(['descripcion' => $detalle->otros]);
                    $migrados++;
                }
            }
            
            $this->info("✅ Se migraron {$migrados} registros");
            
            // Ahora eliminar el campo 'otros'
            $this->info('🗑️  Eliminando campo "otros"...');
            Schema::table('detalle_trabajo', function ($table) {
                $table->dropColumn('otros');
            });
            $this->info('✅ Campo "otros" eliminado');
        } else {
            $this->info('✅ El campo "otros" ya fue eliminado');
        }

        // Verificar datos actuales
        $this->info('📊 Verificando datos actuales...');
        $detalles = DB::table('detalle_trabajo')->get();
        
        $this->table(
            ['ID', 'ID Trabajo', 'Descripción', 'Tamaño', 'Color', 'Modelo', 'Cantidad'],
            $detalles->map(function ($d) {
                return [
                    $d->id,
                    $d->idTrabajo,
                    $d->descripcion ?: 'Sin descripción',
                    $d->tamano ?: 'Sin especificar',
                    $d->color ?: 'Sin especificar',
                    $d->modelo ?: 'Sin especificar',
                    $d->cantidad
                ];
            })->toArray()
        );

        // Verificar relación con Trabajos
        $this->info('🔗 Verificando relación con Trabajos...');
        $trabajos = DB::table('trabajos')->get();
        $relaciones = [];
        
        foreach ($trabajos as $trabajo) {
            $detalle = DB::table('detalle_trabajo')->where('idTrabajo', $trabajo->id)->first();
            $relaciones[] = [
                'Trabajo ID' => $trabajo->id,
                'Cliente ID' => $trabajo->idCliente,
                'Servicio ID' => $trabajo->idServicio,
                'Tiene Detalle' => $detalle ? 'SÍ' : 'NO',
                'Detalle ID' => $detalle ? $detalle->id : 'N/A',
                'Descripción' => $detalle ? ($detalle->descripcion ?: 'Vacía') : 'N/A'
            ];
        }
        
        $this->table(
            ['Trabajo ID', 'Cliente ID', 'Servicio ID', 'Tiene Detalle', 'Detalle ID', 'Descripción'],
            $relaciones
        );

        // Verificar con Eloquent
        $this->info('🔍 Verificando con Eloquent...');
        try {
            $trabajo = \App\Models\Trabajos::with('detallesTrabajo')->find(16);
            if ($trabajo) {
                $this->info("✅ Trabajo encontrado: ID {$trabajo->id}");
                if ($trabajo->detallesTrabajo && $trabajo->detallesTrabajo->count() > 0) {
                    $this->info("✅ Detalle encontrado: " . json_encode($trabajo->detallesTrabajo->first()->toArray()));
                } else {
                    $this->warn("⚠️  Detalle NO encontrado para trabajo ID {$trabajo->id}");
                }
            } else {
                $this->error("❌ Trabajo ID 16 no encontrado");
            }
        } catch (\Exception $e) {
            $this->error("❌ Error al verificar con Eloquent: " . $e->getMessage());
        }

        $this->info('✅ Verificación completada');
        return 0;
    }
}
