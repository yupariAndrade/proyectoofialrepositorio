<?php

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Trabajos;
use App\Models\Pagos;
use Illuminate\Support\Facades\DB;

echo "🔧 Corrigiendo estados de pago inconsistentes...\n\n";

try {
    // 1. Buscar trabajos cancelados con estado de pago incorrecto
    $trabajosInconsistentes = Trabajos::where('idEstado', 5) // Cancelado
        ->where('idEstadoPago', '!=', 4) // No cancelado
        ->with(['pagos', 'cliente'])
        ->get();

    echo "📊 Trabajos cancelados con estado de pago incorrecto: " . $trabajosInconsistentes->count() . "\n\n";

    if ($trabajosInconsistentes->count() > 0) {
        foreach ($trabajosInconsistentes as $trabajo) {
            echo "🔍 Trabajo ID: {$trabajo->id}\n";
            echo "   Cliente: {$trabajo->cliente->nombre} {$trabajo->cliente->apellido}\n";
            echo "   Estado Trabajo: Cancelado (ID: 5)\n";
            echo "   Estado Pago Actual: ID {$trabajo->idEstadoPago}\n";
            
            // 2. Actualizar estado de pago del trabajo
            $trabajo->update(['idEstadoPago' => 4]);
            echo "   ✅ Estado de pago del trabajo actualizado a: Cancelado (ID: 4)\n";
            
            // 3. Actualizar estado de pago en la tabla pagos
            $pago = $trabajo->pagos()->latest('idPago')->first();
            if ($pago) {
                $pago->update(['idEstadoPago' => 4]);
                echo "   ✅ Estado de pago en tabla pagos actualizado a: Cancelado (ID: 4)\n";
            } else {
                echo "   ⚠️  No se encontró pago asociado\n";
            }
            
            echo "   ---\n";
        }
        
        echo "\n✅ Corrección completada exitosamente!\n";
    } else {
        echo "✅ No hay trabajos con estados inconsistentes.\n";
    }

    // 4. Verificar resultado final
    echo "\n📋 Verificación final:\n";
    $trabajosCancelados = Trabajos::where('idEstado', 5)
        ->with(['cliente', 'estadoPago'])
        ->get();
    
    foreach ($trabajosCancelados as $trabajo) {
        echo "   Trabajo ID: {$trabajo->id} - Cliente: {$trabajo->cliente->nombre} - Estado Pago: {$trabajo->estadoPago->nombre}\n";
    }

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n🏁 Script finalizado.\n";
