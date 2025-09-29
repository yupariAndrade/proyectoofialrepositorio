<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trabajos;
use App\Models\EstadosTrabajo;
use App\Models\EstadoPago;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncEstadosPagoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:estados-pago {--dry-run : Solo mostrar qué se actualizaría sin hacer cambios}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza automáticamente los estados de pago para trabajos cancelados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Sincronizando estados de pago para trabajos cancelados...');
        $this->newLine();

        try {
            // Obtener el ID del estado "Cancelado" para trabajos
            $estadoTrabajoCancelado = EstadosTrabajo::where('nombre', 'Cancelado')->first();
            if (!$estadoTrabajoCancelado) {
                $this->error('❌ Error: No se encontró el estado "Cancelado" en estados_trabajo');
                return 1;
            }

            // Obtener el ID del estado "Cancelado" para pagos
            $estadoPagoCancelado = EstadoPago::where('nombre', 'Cancelado')->first();
            if (!$estadoPagoCancelado) {
                $this->error('❌ Error: No se encontró el estado "Cancelado" en estados_pago');
                return 1;
            }

            $this->info('📊 Estados encontrados:');
            $this->line("   - Trabajo Cancelado: ID {$estadoTrabajoCancelado->id}");
            $this->line("   - Pago Cancelado: ID {$estadoPagoCancelado->id}");
            $this->newLine();

            // Buscar trabajos cancelados
            $trabajosCancelados = Trabajos::where('idEstado', $estadoTrabajoCancelado->id)->get();
            
            $this->info("🔍 Trabajos cancelados encontrados: {$trabajosCancelados->count()}");
            $this->newLine();

            if ($trabajosCancelados->count() === 0) {
                $this->info('✅ No hay trabajos cancelados para sincronizar.');
                return 0;
            }

            $actualizados = 0;
            $errores = 0;
            $isDryRun = $this->option('dry-run');

            if ($isDryRun) {
                $this->warn('🔍 MODO DRY-RUN: Solo se mostrará qué se actualizaría');
                $this->newLine();
            }

            foreach ($trabajosCancelados as $trabajo) {
                try {
                    // Verificar si ya está sincronizado
                    if ($trabajo->idEstadoPago == $estadoPagoCancelado->id) {
                        $this->line("⏭️  Trabajo ID {$trabajo->id} - Ya está sincronizado");
                        continue;
                    }

                    if ($isDryRun) {
                        $clienteNombre = $trabajo->cliente ? $trabajo->cliente->nombre : 'Sin cliente';
                        $this->line("🔄 Trabajo ID {$trabajo->id} - Cliente: {$clienteNombre} - Se actualizaría");
                        $actualizados++;
                        continue;
                    }

                    DB::transaction(function () use ($trabajo, $estadoPagoCancelado) {
                        // 1. Actualizar el estado de pago del trabajo
                        $trabajo->update([
                            'idEstadoPago' => $estadoPagoCancelado->id
                        ]);

                        // 2. Actualizar el estado de pago en la tabla pagos
                        $pago = $trabajo->pagos()->latest('idPago')->first();
                        if ($pago) {
                            $pago->update([
                                'idEstadoPago' => $estadoPagoCancelado->id
                            ]);
                        }
                    });

                    $actualizados++;
                    $clienteNombre = $trabajo->cliente ? $trabajo->cliente->nombre : 'Sin cliente';
                    $this->line("✅ Trabajo ID {$trabajo->id} - Cliente: {$clienteNombre} - Sincronizado");

                } catch (\Exception $e) {
                    $errores++;
                    $this->error("❌ Error en trabajo ID {$trabajo->id}: " . $e->getMessage());
                }
            }

            $this->newLine();
            $this->info('📈 Resumen de sincronización:');
            $this->line("   - Trabajos actualizados: {$actualizados}");
            $this->line("   - Errores: {$errores}");
            $this->line("   - Total procesados: " . ($actualizados + $errores));
            $this->newLine();

            if ($actualizados > 0 && !$isDryRun) {
                $this->info('✅ Sincronización completada exitosamente!');
                
                // Log de auditoría
                Log::info('Sincronización de estados de pago completada', [
                    'trabajos_actualizados' => $actualizados,
                    'errores' => $errores,
                    'fecha' => now()
                ]);
            } elseif ($isDryRun) {
                $this->info('🔍 Dry-run completado. Ejecuta sin --dry-run para aplicar los cambios.');
            }

        } catch (\Exception $e) {
            $this->error('❌ Error general: ' . $e->getMessage());
            Log::error('Error en sincronización de estados de pago: ' . $e->getMessage());
            return 1;
        }

        $this->newLine();
        $this->info('🎯 Próximos pasos:');
        $this->line('   1. Verificar en la aplicación que los estados se muestren correctamente');
        $this->line('   2. Los nuevos trabajos cancelados se sincronizarán automáticamente');
        $this->line('   3. Este comando se puede ejecutar periódicamente si es necesario');
        $this->newLine();

        return 0;
    }
}
