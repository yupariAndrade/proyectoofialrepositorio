<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Servicios;
use Illuminate\Support\Facades\DB;

class LimpiarDuplicadosServicios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servicios:limpiar-duplicados {--force : Forzar la limpieza sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar servicios duplicados por nombre y usuario';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Buscando servicios duplicados...');

        // Encontrar duplicados por nombre y usuario
        $duplicados = Servicios::select('nombreServicio', 'idUsuario', DB::raw('COUNT(*) as total'))
                              ->groupBy('nombreServicio', 'idUsuario')
                              ->havingRaw('COUNT(*) > 1')
                              ->get();

        if ($duplicados->isEmpty()) {
            $this->info('✅ No se encontraron servicios duplicados.');
            return 0;
        }

        $this->warn("⚠️  Se encontraron {$duplicados->count()} grupos de servicios duplicados:");

        foreach ($duplicados as $duplicado) {
            $servicios = Servicios::where('nombreServicio', $duplicado->nombreServicio)
                                ->where('idUsuario', $duplicado->idUsuario)
                                ->orderBy('created_at', 'desc')
                                ->get();

            $this->line("📋 '{$duplicado->nombreServicio}' (Usuario ID: {$duplicado->idUsuario}) - {$duplicado->total} registros");
            
            foreach ($servicios as $index => $servicio) {
                $status = $index === 0 ? '✅ MANTENER' : '❌ ELIMINAR';
                $this->line("   {$status} ID: {$servicio->id} - Creado: {$servicio->created_at}");
            }
        }

        if (!$this->option('force')) {
            if (!$this->confirm('¿Desea proceder con la limpieza de duplicados?')) {
                $this->info('❌ Operación cancelada.');
                return 0;
            }
        }

        $eliminados = 0;
        $this->info('🧹 Iniciando limpieza de duplicados...');

        foreach ($duplicados as $duplicado) {
            $servicios = Servicios::where('nombreServicio', $duplicado->nombreServicio)
                                ->where('idUsuario', $duplicado->idUsuario)
                                ->orderBy('created_at', 'desc')
                                ->get();

            // Eliminar todos excepto el más reciente
            for ($i = 1; $i < $servicios->count(); $i++) {
                $servicios[$i]->delete();
                $eliminados++;
                $this->line("🗑️  Eliminado: ID {$servicios[$i]->id} - '{$servicios[$i]->nombreServicio}'");
            }
        }

        $this->info("✅ Limpieza completada. Se eliminaron {$eliminados} servicios duplicados.");
        return 0;
    }
}
