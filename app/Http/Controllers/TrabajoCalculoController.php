<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicios;

class TrabajoCalculoController extends Controller
{
    /**
     * Calcular totales de un trabajo
     */
    public function calcularTotales(Request $request)
    {
        $servicios = $request->input('servicios', []);
        $aCuenta = $request->input('aCuenta', 0);
        
        $total = 0;
        $detalles = [];
        
        foreach ($servicios as $servicio) {
            $servicioInfo = Servicios::find($servicio['idServicio']);
            if (!$servicioInfo) continue;
            
            $cantidad = $servicio['cantidad'] ?? 1;
            $descuento = $servicio['descuento'] ?? 0;
            
            $subtotalBruto = $servicioInfo->precioReferencial * $cantidad;
            $subtotalFinal = max(0, $subtotalBruto - $descuento);
            
            $detalles[] = [
                'servicio' => $servicioInfo->nombreServicio,
                'precioUnitario' => $servicioInfo->precioReferencial,
                'cantidad' => $cantidad,
                'descuento' => $descuento,
                'subtotalBruto' => $subtotalBruto,
                'subtotalFinal' => $subtotalFinal
            ];
            
            $total += $subtotalFinal;
        }
        
        $saldo = max(0, $total - $aCuenta);
        
        return response()->json([
            'total' => $total,
            'saldo' => $saldo,
            'aCuenta' => $aCuenta,
            'detalles' => $detalles
        ]);
    }
    
    /**
     * Calcular totales de un trabajo existente
     */
    public function calcularTotalesTrabajo($id)
    {
        $trabajo = \App\Models\Trabajos::with(['servicios.servicio'])->find($id);
        
        if (!$trabajo) {
            return response()->json(['error' => 'Trabajo no encontrado'], 404);
        }
        
        $total = 0;
        $detalles = [];
        
        foreach ($trabajo->servicios as $servicioTrabajo) {
            $servicio = $servicioTrabajo->servicio;
            if (!$servicio) continue;
            
            $cantidad = $servicioTrabajo->cantidad ?? 1;
            $descuento = $servicioTrabajo->descuento ?? 0;
            
            $subtotalBruto = $servicio->precioReferencial * $cantidad;
            $subtotalFinal = max(0, $subtotalBruto - $descuento);
            
            $detalles[] = [
                'servicio' => $servicio->nombreServicio,
                'precioUnitario' => $servicio->precioReferencial,
                'cantidad' => $cantidad,
                'descuento' => $descuento,
                'subtotalBruto' => $subtotalBruto,
                'subtotalFinal' => $subtotalFinal
            ];
            
            $total += $subtotalFinal;
        }
        
        $aCuenta = $trabajo->detallesTrabajo->sum('pago.aCuenta') ?? 0;
        $saldo = max(0, $total - $aCuenta);
        
        return response()->json([
            'total' => $total,
            'saldo' => $saldo,
            'aCuenta' => $aCuenta,
            'detalles' => $detalles
        ]);
    }
    
    /**
     * Calcular descuento válido para un servicio
     */
    public function validarDescuento(Request $request)
    {
        $idServicio = $request->input('idServicio');
        $cantidad = $request->input('cantidad', 1);
        $descuento = $request->input('descuento', 0);
        
        $servicio = Servicios::find($idServicio);
        if (!$servicio) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }
        
        $subtotalBruto = $servicio->precioReferencial * $cantidad;
        $descuentoMaximo = $subtotalBruto - 0.01;
        $descuentoValido = min($descuento, $descuentoMaximo);
        
        return response()->json([
            'descuentoValido' => $descuentoValido,
            'descuentoMaximo' => $descuentoMaximo,
            'subtotalBruto' => $subtotalBruto,
            'subtotalFinal' => max(0, $subtotalBruto - $descuentoValido)
        ]);
    }
}