<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VerificarTablas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verificar:tablas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar la estructura y contenido de las tablas principales';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando tablas principales...');
        
        // Verificar tabla trabajos
        $this->info('\n📋 Tabla: trabajos');
        if (Schema::hasTable('trabajos')) {
            $trabajos = DB::table('trabajos')->count();
            $this->info("   ✅ Existe - Registros: $trabajos");
            
            if ($trabajos > 0) {
                $ultimoTrabajo = DB::table('trabajos')->latest('id')->first();
                $this->info("   📊 Último trabajo ID: {$ultimoTrabajo->id}");
            }
        } else {
            $this->error('   ❌ No existe');
        }
        
        // Verificar tabla detalle_trabajo
        $this->info('\n📋 Tabla: detalle_trabajo');
        if (Schema::hasTable('detalle_trabajo')) {
            $detalles = DB::table('detalle_trabajo')->count();
            $this->info("   ✅ Existe - Registros: $detalles");
            
            if ($detalles > 0) {
                $ultimoDetalle = DB::table('detalle_trabajo')->latest('id')->first();
                $this->info("   📊 Último detalle ID: {$ultimoDetalle->id}");
                $this->info("   📊 ID Trabajo: {$ultimoDetalle->idTrabajo}");
                $this->info("   📊 Cantidad: {$ultimoDetalle->cantidad}");
            }
        } else {
            $this->error('   ❌ No existe');
        }
        
        // Verificar tabla pagos
        $this->info('\n📋 Tabla: pagos');
        if (Schema::hasTable('pagos')) {
            $pagos = DB::table('pagos')->count();
            $this->info("   ✅ Existe - Registros: $pagos");
            
            if ($pagos > 0) {
                $ultimoPago = DB::table('pagos')->latest('idPago')->first();
                $this->info("   📊 Último pago ID: {$ultimoPago->idPago}");
                $this->info("   📊 ID Trabajo: {$ultimoPago->idTrabajo}");
                $this->info("   📊 Total: {$ultimoPago->total}");
                $this->info("   📊 A Cuenta: {$ultimoPago->aCuenta}");
                $this->info("   📊 Saldo: {$ultimoPago->saldo}");
            }
        } else {
            $this->error('   ❌ No existe');
        }
        
        // Verificar relaciones
        $this->info('\n🔗 Verificando relaciones...');
        if (Schema::hasTable('trabajos') && Schema::hasTable('detalle_trabajo')) {
            $trabajosSinDetalle = DB::table('trabajos')
                ->leftJoin('detalle_trabajo', 'trabajos.id', '=', 'detalle_trabajo.idTrabajo')
                ->whereNull('detalle_trabajo.idTrabajo')
                ->count();
            
            $this->info("   📊 Trabajos sin detalle: $trabajosSinDetalle");
            
            // Mostrar trabajos sin detalle
            if ($trabajosSinDetalle > 0) {
                $this->info("   📋 Trabajos sin detalle:");
                $trabajosSinDetalleList = DB::table('trabajos')
                    ->leftJoin('detalle_trabajo', 'trabajos.id', '=', 'detalle_trabajo.idTrabajo')
                    ->whereNull('detalle_trabajo.idTrabajo')
                    ->select('trabajos.id', 'trabajos.fechaRegistro')
                    ->get();
                
                foreach ($trabajosSinDetalleList as $trabajo) {
                    $this->info("      - ID: {$trabajo->id}, Fecha: {$trabajo->fechaRegistro}");
                }
            }
        }
        
        if (Schema::hasTable('trabajos') && Schema::hasTable('pagos')) {
            $trabajosSinPago = DB::table('trabajos')
                ->leftJoin('pagos', 'trabajos.id', '=', 'pagos.idTrabajo')
                ->whereNull('pagos.idTrabajo')
                ->count();
            
            $this->info("   📊 Trabajos sin pago: $trabajosSinPago");
            
            // Mostrar trabajos sin pago
            if ($trabajosSinPago > 0) {
                $this->info("   📋 Trabajos sin pago:");
                $trabajosSinPagoList = DB::table('trabajos')
                    ->leftJoin('pagos', 'trabajos.id', '=', 'pagos.idTrabajo')
                    ->whereNull('pagos.idTrabajo')
                    ->select('trabajos.id', 'trabajos.fechaRegistro')
                    ->get();
                
                foreach ($trabajosSinPagoList as $trabajo) {
                    $this->info("      - ID: {$trabajo->id}, Fecha: {$trabajo->fechaRegistro}");
                }
            }
        }
        
        // Verificar trabajos existentes que podrían tener problemas
        $this->info('\n🔍 Verificando trabajos existentes problemáticos...');
        
        if (Schema::hasTable('trabajos')) {
            $trabajosExistentes = DB::table('trabajos')
                ->leftJoin('detalle_trabajo', 'trabajos.id', '=', 'detalle_trabajo.idTrabajo')
                ->leftJoin('pagos', 'trabajos.id', '=', 'pagos.idTrabajo')
                ->select(
                    'trabajos.id',
                    'trabajos.fechaRegistro',
                    'detalle_trabajo.id as detalle_id',
                    'pagos.idPago as pago_id'
                )
                ->orderBy('trabajos.id')
                ->get();
            
            foreach ($trabajosExistentes as $trabajo) {
                $status = [];
                if ($trabajo->detalle_id) $status[] = '✅ Detalle';
                else $status[] = '❌ Sin Detalle';
                
                if ($trabajo->pago_id) $status[] = '✅ Pago';
                else $status[] = '❌ Sin Pago';
                
                $this->info("   📋 Trabajo ID {$trabajo->id} ({$trabajo->fechaRegistro}): " . implode(', ', $status));
            }
        }
        
        // Verificar estructura de las tablas
        $this->info('\n🏗️ Verificando estructura de tablas...');
        
        if (Schema::hasTable('detalle_trabajo')) {
            $columnas = Schema::getColumnListing('detalle_trabajo');
            $this->info("   📋 Tabla detalle_trabajo - Columnas: " . implode(', ', $columnas));
            
            // Verificar si tiene timestamps
            if (in_array('created_at', $columnas) && in_array('updated_at', $columnas)) {
                $this->info("   ✅ Tiene timestamps automáticos");
            } else {
                $this->info("   ❌ NO tiene timestamps automáticos");
            }
        }
        
        if (Schema::hasTable('pagos')) {
            $columnas = Schema::getColumnListing('pagos');
            $this->info("   📋 Tabla pagos - Columnas: " . implode(', ', $columnas));
            
            // Verificar si tiene timestamps
            if (in_array('created_at', $columnas) && in_array('updated_at', $columnas)) {
                $this->info("   ✅ Tiene timestamps automáticos");
            } else {
                $this->info("   ❌ NO tiene timestamps automáticos");
            }
        }
        
        // Verificar tabla estados_pago
        $this->info('\n💰 Verificando tabla estados_pago...');
        if (Schema::hasTable('estados_pago')) {
            $columnas = Schema::getColumnListing('estados_pago');
            $this->info("   📋 Columnas de estados_pago: " . implode(', ', $columnas));
            
            $estadosPago = DB::table('estados_pago')->get();
            $this->info("   📋 Estados de pago disponibles:");
            foreach ($estadosPago as $estado) {
                $this->info("      - Registro completo: " . json_encode($estado));
            }
        } else {
            $this->error("   ❌ Tabla estados_pago no existe");
        }
        
        // Verificar si hay problemas con timestamps en los modelos
        $this->info('\n🔍 Verificando configuración de timestamps en modelos...');
        
        try {
            $detalleTrabajo = new \App\Models\DetalleTrabajo();
            $this->info("   📋 Modelo DetalleTrabajo - timestamps: " . ($detalleTrabajo->timestamps ? 'true' : 'false'));
            
            $pagos = new \App\Models\Pagos();
            $this->info("   📋 Modelo Pagos - timestamps: " . ($pagos->timestamps ? 'true' : 'false'));
        } catch (\Exception $e) {
            $this->error("   ❌ Error al verificar modelos: " . $e->getMessage());
        }
        
        $this->info('\n✅ Verificación completada');
    }
}
