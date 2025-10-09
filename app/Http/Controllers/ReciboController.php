<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabajos;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReciboController extends Controller
{
    /**
     * ✅ ARQUITECTURA MVC - Generar recibo PDF para un trabajo
     */
    public function generarRecibo($id)
    {
        try {
            // Obtener el trabajo con todas sus relaciones
            $trabajo = Trabajos::with([
                'cliente',
                'responsable.rol',
                'estado',
                'estadoPago',
                'detallesTrabajo.servicio',
                'pagos'
            ])->findOrFail($id);

            // Calcular totales
            $totalTrabajo = $trabajo->detallesTrabajo->sum(function ($detalle) {
                $precioOriginal = $detalle->servicio->precioReferencial ?? 0;
                $cantidad = $detalle->cantidad ?? 1;
                $descuento = $detalle->descuento ?? 0;
                
                $subtotalBruto = $precioOriginal * $cantidad;
                $subtotalFinal = $subtotalBruto - $descuento;
                
                return $subtotalFinal;
            });

            // Obtener datos del último pago
            $ultimoPago = $trabajo->pagos()->latest('idPago')->first();
            $totalPagado = $ultimoPago ? $ultimoPago->aCuenta : 0;
            $saldo = $totalTrabajo - $totalPagado;

            // Preparar datos para el PDF
            $datosRecibo = [
                'trabajo' => $trabajo,
                'total' => $totalTrabajo,
                'aCuenta' => $totalPagado,
                'saldo' => $saldo,
                'servicios' => $trabajo->detallesTrabajo->map(function($detalle) {
                    return [
                        'nombre' => $detalle->servicio->nombreServicio ?? 'Servicio no especificado',
                        'cantidad' => $detalle->cantidad ?? 1,
                        'precio' => $detalle->servicio->precioReferencial ?? 0,
                        'descuento' => $detalle->descuento ?? 0,
                        'subtotal' => (($detalle->servicio->precioReferencial ?? 0) - ($detalle->descuento ?? 0)) * ($detalle->cantidad ?? 1)
                    ];
                })
            ];

            // Generar PDF
            $pdf = Pdf::loadView('recibos.trabajo', $datosRecibo);
            $pdf->setPaper('A6', 'portrait'); // Tamaño pequeño para recibo

            // Log de la operación
            Log::info('✅ Recibo generado exitosamente:', [
                'trabajo_id' => $trabajo->id,
                'cliente' => $trabajo->cliente->nombre ?? 'Sin cliente',
                'total' => $totalTrabajo,
                'aCuenta' => $totalPagado,
                'saldo' => $saldo
            ]);

            return $pdf->download('recibo_trabajo_' . $trabajo->id . '.pdf');

        } catch (\Exception $e) {
            Log::error('❌ Error al generar recibo:', [
                'error' => $e->getMessage(),
                'trabajo_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al generar el recibo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ ARQUITECTURA MVC - Vista previa del recibo (para mostrar en modal)
     */
    public function vistaPrevia($id)
    {
        try {
            // Obtener el trabajo con todas sus relaciones
            $trabajo = Trabajos::with([
                'cliente',
                'responsable.rol',
                'estado',
                'estadoPago',
                'detallesTrabajo.servicio',
                'pagos'
            ])->findOrFail($id);

            // Calcular totales
            $totalTrabajo = $trabajo->detallesTrabajo->sum(function ($detalle) {
                $precioOriginal = $detalle->servicio->precioReferencial ?? 0;
                $cantidad = $detalle->cantidad ?? 1;
                $descuento = $detalle->descuento ?? 0;
                
                $subtotalBruto = $precioOriginal * $cantidad;
                $subtotalFinal = $subtotalBruto - $descuento;
                
                return $subtotalFinal;
            });

            // Obtener datos del último pago
            $ultimoPago = $trabajo->pagos()->latest('idPago')->first();
            $totalPagado = $ultimoPago ? $ultimoPago->aCuenta : 0;
            $saldo = $totalTrabajo - $totalPagado;

            // Transformar datos para el frontend
            $trabajoData = [
                'id' => $trabajo->id,
                'fechaRegistro' => $trabajo->fechaRegistro,
                'fechaEntrega' => $trabajo->fechaEntrega,
                'total' => $totalTrabajo,
                'aCuenta' => $totalPagado,
                'saldo' => $saldo,
                'cliente' => [
                    'nombre' => $trabajo->cliente->nombre ?? 'Sin cliente',
                    'apellido' => $trabajo->cliente->apellido ?? ''
                ],
                'estadoPago' => [
                    'nombre' => $trabajo->estadoPago->nombre ?? 'Sin estado'
                ],
                'servicios' => $trabajo->detallesTrabajo->map(function($detalle) {
                    return [
                        'id' => $detalle->id,
                        'nombreServicio' => $detalle->servicio->nombreServicio ?? 'Servicio no especificado',
                        'cantidad' => $detalle->cantidad ?? 1,
                        'precio' => $detalle->servicio->precioReferencial ?? 0,
                        'descuento' => $detalle->descuento ?? 0,
                        'subtotal' => (($detalle->servicio->precioReferencial ?? 0) - ($detalle->descuento ?? 0)) * ($detalle->cantidad ?? 1)
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'trabajo' => $trabajoData
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error al obtener vista previa del recibo:', [
                'error' => $e->getMessage(),
                'trabajo_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos del recibo: ' . $e->getMessage()
            ], 500);
        }
    }
}