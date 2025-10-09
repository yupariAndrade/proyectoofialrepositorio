<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabajos;
use App\Models\Pagos;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CuotaController extends Controller
{
    /**
     * ✅ ARQUITECTURA MVC - Procesar pago parcial (cuota)
     * Toda la lógica de negocio está en el controlador
     */
    public function store(Request $request)
    {
        try {
            // ✅ Log de depuración
            Log::info('🔍 CuotaController: Datos recibidos:', [
                'idTrabajo' => $request->idTrabajo,
                'monto' => $request->monto,
                'todos_los_datos' => $request->all()
            ]);

            // ✅ ARQUITECTURA MVC - Validación en el backend
            $request->validate([
                'idTrabajo' => 'required|exists:trabajos,id',
                'monto' => 'required|numeric|min:0.01'
            ]);

            // ✅ ARQUITECTURA MVC - Lógica de negocio en el controlador
            $nuevoACuenta = 0;
            $nuevoSaldo = 0;
            
            DB::transaction(function () use ($request, &$nuevoACuenta, &$nuevoSaldo) {
                // 1. Obtener el trabajo
                $trabajo = Trabajos::findOrFail($request->idTrabajo);
                
                // 2. Obtener el último pago
                $ultimoPago = $trabajo->pagos()->latest('idPago')->first();
                
                if (!$ultimoPago) {
                    throw new \Exception('No se encontró registro de pago para este trabajo');
                }

                // 3. Validar que el monto no exceda el saldo pendiente
                if ($request->monto > $ultimoPago->saldo) {
                    throw new \Exception('El monto de la cuota no puede ser mayor al saldo pendiente');
                }

                // 4. Calcular nuevos valores
                $nuevoACuenta = $ultimoPago->aCuenta + $request->monto;
                $nuevoSaldo = $ultimoPago->total - $nuevoACuenta;
                
                // 5. Determinar nuevo estado de pago
                $nuevoEstadoPago = $this->determinarEstadoPago($nuevoSaldo, $nuevoACuenta, $ultimoPago->total);

            // 6. Crear nuevo registro de pago (cuota) - Cada cuota es un registro separado
            $nuevoPago = Pagos::create([
                'idTrabajo' => $trabajo->id,
                'total' => $ultimoPago->total, // Mantener el total original
                'aCuenta' => $nuevoACuenta,    // Suma acumulativa de TODOS los pagos
                'saldo' => $nuevoSaldo,        // Saldo restante
                'devoluciones' => 0
            ]);
            
            // Log de la cuota individual
            Log::info('✅ Cuota individual registrada:', [
                'idPago' => $nuevoPago->id,
                'montoCuota' => $request->monto,
                'fechaPago' => $nuevoPago->created_at
            ]);

                // 7. Actualizar estado del trabajo
                $trabajo->update(['idEstadoPago' => $nuevoEstadoPago]);

                // 8. Log de la operación
                Log::info('✅ Cuota procesada exitosamente:', [
                    'idTrabajo' => $trabajo->id,
                    'montoCuota' => $request->monto,
                    'aCuentaAnterior' => $ultimoPago->aCuenta,
                    'aCuentaNuevo' => $nuevoACuenta,
                    'saldoAnterior' => $ultimoPago->saldo,
                    'saldoNuevo' => $nuevoSaldo,
                    'estadoPagoAnterior' => $trabajo->idEstadoPago,
                    'estadoPagoNuevo' => $nuevoEstadoPago
                ]);
            });

            // 9. Recargar el trabajo con todas sus relaciones
            $trabajoActualizado = Trabajos::with([
                'cliente',
                'responsable.rol',
                'estado',
                'estadoPago',
                'detallesTrabajo.servicio',
                'pagos'
            ])->find($request->idTrabajo);

            // 10. Transformar datos para el frontend
            $trabajoData = $this->transformarTrabajoParaFrontend($trabajoActualizado);

            return response()->json([
                'success' => true,
                'message' => 'Cuota registrada exitosamente',
                'trabajo' => $trabajoData,
                'flash' => [
                    'success' => 'Cuota de ' . $request->monto . ' Bs registrada exitosamente. Saldo restante: ' . $nuevoSaldo . ' Bs'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error al procesar cuota:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'idTrabajo' => $request->idTrabajo,
                'monto' => $request->monto,
                'datos_recibidos' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la cuota: ' . $e->getMessage(),
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ ARQUITECTURA MVC - Lógica de negocio para determinar estado de pago
     */
    private function determinarEstadoPago($saldo, $aCuenta, $total)
    {
        // ✅ LÓGICA CORRECTA según la explicación del usuario:
        
        // 1. Si no se ha pagado nada (A Cuenta = 0) → Pendiente
        if ($aCuenta == 0) {
            return 2; // Pendiente
        }
        
        // 2. Si se ha pagado algo y el saldo es 0 → Pago completado
        elseif ($saldo == 0) {
            return 1; // Pago completado
        }
        
        // 3. Si se ha pagado algo pero aún queda saldo → Parcial
        else {
            return 3; // Parcial
        }
    }

    /**
     * ✅ ARQUITECTURA MVC - Transformar datos para el frontend
     * Misma lógica que en RegistrarTrabajoController
     */
    private function transformarTrabajoParaFrontend($trabajo)
    {
        // Calcular total del trabajo
        $totalTrabajo = $trabajo->detallesTrabajo->sum(function ($detalle) {
            $precioOriginal = $detalle->servicio->precioReferencial ?? 0;
            $cantidad = $detalle->cantidad ?? 1;
            $descuento = $detalle->descuento ?? 0;
            $subtotalBruto = $precioOriginal * $cantidad;
            $subtotalFinal = max(0, $subtotalBruto - $descuento);
            return $subtotalFinal;
        });

        // Obtener datos del último pago
        $ultimoPago = $trabajo->pagos()->latest('idPago')->first();
        $totalPagado = $ultimoPago ? $ultimoPago->aCuenta : 0;
        $saldo = $totalTrabajo - $totalPagado;

        // Transformar servicios
        $servicios = $trabajo->detallesTrabajo->map(function ($detalle) {
            return [
                'id' => $detalle->id,
                'nombreServicio' => $detalle->servicio->nombreServicio ?? 'Sin nombre',
                'precio' => $detalle->servicio->precioReferencial ?? 0,
                'cantidad' => $detalle->cantidad ?? 1,
                'descuento' => $detalle->descuento ?? 0,
                'subtotal' => (($detalle->servicio->precioReferencial ?? 0) - ($detalle->descuento ?? 0)) * ($detalle->cantidad ?? 1)
            ];
        });

        return [
            'id' => $trabajo->id,
            'idCliente' => $trabajo->idCliente,
            'idResponsable' => $trabajo->idResponsable,
            'fechaRegistro' => $trabajo->fechaRegistro,
            'fechaEntrega' => $trabajo->fechaEntrega,
            'idEstado' => $trabajo->idEstado,
            'idEstadoPago' => $trabajo->idEstadoPago,
            'observaciones' => $trabajo->observaciones ?? '',
            'slug' => $trabajo->slug ?? '',
            'total' => $totalTrabajo,
            'aCuenta' => $totalPagado,
            'saldo' => $saldo,
            'cliente' => [
                'id' => $trabajo->cliente->id ?? 0,
                'nombre' => $trabajo->cliente->nombre ?? 'Cliente no especificado',
                'apellido' => $trabajo->cliente->apellido ?? '',
                'email' => $trabajo->cliente->email ?? null,
                'telefono' => $trabajo->cliente->telefono ?? null,
                'direccion' => $trabajo->cliente->direccion ?? null,
            ],
            'estado' => [
                'id' => $trabajo->estado->id ?? 0,
                'nombre' => $trabajo->estado->nombre ?? 'Estado no especificado',
            ],
            'estadoPago' => [
                'id' => $trabajo->estadoPago->id ?? 0,
                'nombre' => $trabajo->estadoPago->nombre ?? 'Estado de pago no especificado',
            ],
            'responsable' => $trabajo->responsable ? [
                'id' => $trabajo->responsable->id ?? 0,
                'nombre' => $trabajo->responsable->nombre ?? 'No asignado',
                'apellido' => $trabajo->responsable->apellidoPaterno ?? '',
                'rol' => $trabajo->responsable->rol ? $trabajo->responsable->rol->nombre : 'Sin rol'
            ] : null,
            'servicios' => $servicios,
            'totalTrabajo' => $totalTrabajo,
            'saldo' => $saldo,
            'pagado' => $totalPagado,
        ];
    }
}