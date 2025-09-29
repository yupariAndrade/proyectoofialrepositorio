<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicios;

class CalculoController extends Controller
{
    /**
     * Calcular total de servicios desde el backend
     */
    public function calcularTotal(Request $request)
    {
        \Log::info('🔄 CalculoController: Recibiendo datos', [
            'servicios' => $request->input('servicios', []),
            'aCuenta' => $request->input('aCuenta', 0),
            'aCuenta_type' => gettype($request->input('aCuenta', 0)),
            'aCuenta_raw' => $request->input('aCuenta')
        ]);
        
        $servicios = $request->input('servicios', []);
        $aCuenta = (float) $request->input('aCuenta', 0);
        $total = 0;
        
        foreach ($servicios as $servicio) {
            if (isset($servicio['idServicio']) && isset($servicio['cantidad'])) {
                $servicioModel = Servicios::find($servicio['idServicio']);
                if ($servicioModel) {
                    $precio = $servicioModel->precioReferencial;
                    $cantidad = $servicio['cantidad'];
                    $descuento = $servicio['descuento'] ?? 0;
                    
                    $subtotalBruto = $precio * $cantidad;
                    $subtotalFinal = max(0, $subtotalBruto - $descuento);
                    $total += $subtotalFinal;
                }
            }
        }
        
        $saldo = max(0, $total - $aCuenta);
        
        \Log::info('✅ CalculoController: Resultado', [
            'total' => $total,
            'aCuenta' => $aCuenta,
            'saldo' => $saldo
        ]);
        
        return response()->json([
            'total' => $total,
            'saldo' => $saldo
        ]);
    }
}
