<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Models\Trabajos;
use App\Models\DetalleTrabajo;
use App\Models\Pagos;
use App\Models\Clientes;
use App\Models\Servicios;
use App\Models\EstadosTrabajo;
use App\Models\EstadoPago;
use App\Models\Usuarios;
use App\Http\Requests\StoreTrabajoRequest;
use App\Http\Requests\UpdateTrabajoRequest;

class RegistrarTrabajoController extends Controller
{
    /**
     * Determinar el estado del pago basado en el saldo y monto pagado
     */
    private function determinarEstadoPago($saldo, $montoPagado, $total = null)
    {
        // Si no se ha pagado nada (A Cuenta = 0)
        if ($montoPagado == 0) {
            // Si el saldo es igual al total, significa que no hay deuda pendiente = Pago completado
            if ($total !== null && $saldo == $total) {
                return 1; // Pago completado
            }
            // Si no, es Pendiente
            return 2; // Pendiente
        }
        // Si el saldo es 0, está completamente pagado
        elseif ($saldo == 0) {
            return 1; // Pago completado
        }
        // Si se ha pagado algo pero aún queda saldo, es Parcial
        else {
            return 3; // Parcial
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $trabajos = Trabajos::with([
            'cliente', 
            'detallesTrabajo.servicio', 
            'detallesTrabajo.pago', 
            'estado', 
            'estadoPago',
            'responsable.rol', // Cargar la relación rol del responsable
            'pagos' // Agregar relación con pagos
        ])->orderBy('fechaRegistro', 'desc');
        
        // ✅ ARQUITECTURA MVC - Lógica de filtrado en el backend
        if ($request->has('busqueda') && !empty($request->busqueda)) {
            $busqueda = strtolower(trim($request->busqueda));
            
            $trabajos->where(function($query) use ($busqueda) {
                $query->whereHas('cliente', function($q) use ($busqueda) {
                    $q->whereRaw("LOWER(CONCAT(nombre, ' ', apellido)) LIKE ?", ["%{$busqueda}%"]);
                })
                ->orWhereHas('estado', function($q) use ($busqueda) {
                    $q->whereRaw("LOWER(nombre) LIKE ?", ["%{$busqueda}%"]);
                })
                ->orWhereHas('estadoPago', function($q) use ($busqueda) {
                    $q->whereRaw("LOWER(nombre) LIKE ?", ["%{$busqueda}%"]);
                })
                ->orWhereHas('detallesTrabajo.servicio', function($q) use ($busqueda) {
                    $q->whereRaw("LOWER(nombreServicio) LIKE ?", ["%{$busqueda}%"]);
                });
            });
        }
        
        $trabajos = $trabajos->get();
        
        // Debug: Verificar responsables cargados
        Log::info('🔍 Verificando responsables en index:', [
            'total_trabajos' => $trabajos->count(),
            'trabajos_con_responsable' => $trabajos->whereNotNull('idResponsable')->count(),
            'primeros_3_trabajos' => $trabajos->take(3)->map(function($t) {
                return [
                    'id' => $t->id,
                    'idResponsable' => $t->idResponsable,
                    'responsable_cargado' => $t->responsable ? 'SÍ' : 'NO',
                    'responsable_nombre' => $t->responsable ? $t->responsable->nombre : 'NO HAY'
                ];
            })
        ]);
        
        // Transformar los datos para el frontend en el formato correcto
        $trabajosSerializados = $trabajos->map(function ($trabajo) {
            // Calcular total CON descuentos aplicados correctamente
            $totalTrabajo = $trabajo->detallesTrabajo->sum(function ($detalle) {
                $precioOriginal = $detalle->servicio->precioReferencial ?? 0;
                $cantidad = $detalle->cantidad ?? 1;
                $descuento = $detalle->descuento ?? 0;
                
                // Calcular subtotal bruto (precio × cantidad)
                $subtotalBruto = $precioOriginal * $cantidad;
                
                // Aplicar descuento al subtotal bruto
                $subtotalFinal = $subtotalBruto - $descuento;
                
                // Debug: Log de cálculo por servicio
                Log::info('🔍 Cálculo por servicio en trabajo ' . $detalle->idTrabajo, [
                    'servicio' => $detalle->servicio->nombreServicio ?? 'Sin nombre',
                    'precioOriginal' => $precioOriginal,
                    'cantidad' => $cantidad,
                    'subtotalBruto' => $subtotalBruto,
                    'descuento' => $descuento,
                    'subtotalFinal' => $subtotalFinal
                ]);
                
                return $subtotalFinal;
            });

            // Obtener datos de la tabla pagos (último pago)
            $ultimoPago = $trabajo->pagos()->latest('idPago')->first();
            $totalPagado = $ultimoPago ? $ultimoPago->aCuenta : 0;
            $saldo = $totalTrabajo - $totalPagado;
            
            // Debug: Log detallado del pago
            Log::info('🔍 Debug pago para trabajo ' . $trabajo->id, [
                'trabajo_id' => $trabajo->id,
                'total_pagos' => $trabajo->pagos()->count(),
                'ultimo_pago' => $ultimoPago ? [
                    'id' => $ultimoPago->idPago,
                    'total' => $ultimoPago->total,
                    'aCuenta' => $ultimoPago->aCuenta,
                    'saldo' => $ultimoPago->saldo,
                    'created_at' => $ultimoPago->created_at
                ] : 'No hay pagos',
                'totalTrabajo' => $totalTrabajo,
                'totalPagado' => $totalPagado,
                'saldoCalculado' => $saldo
            ]);
            
            // Debug: Log de datos de pago
            Log::info('🔍 Datos de pago para trabajo ' . $trabajo->id, [
                'totalTrabajoCalculado' => $totalTrabajo,
                'totalPagado' => $totalPagado,
                'saldoCalculado' => $saldo,
                'detallesCount' => $trabajo->detallesTrabajo->count(),
                'tienePagos' => $trabajo->pagos()->count(),
                'servicios' => $trabajo->detallesTrabajo->map(function($detalle) {
                    return [
                        'servicio' => $detalle->servicio->nombreServicio ?? 'Sin nombre',
                        'precioOriginal' => $detalle->servicio->precioReferencial ?? 0,
                        'descuento' => $detalle->descuento ?? 0,
                        'cantidad' => $detalle->cantidad ?? 1,
                        'subtotal' => (($detalle->servicio->precioReferencial ?? 0) - ($detalle->descuento ?? 0)) * ($detalle->cantidad ?? 1)
                    ];
                })
            ]);

            // Transformar servicios para el frontend
            $servicios = $trabajo->detallesTrabajo->map(function ($detalle) {
                $precioOriginal = $detalle->servicio->precioReferencial ?? 0;
                $descuento = $detalle->descuento ?? 0;
                $precioFinal = $precioOriginal - $descuento;
                
                // Log para debug
                Log::info('🔍 Servicio transformado:', [
                    'id' => $detalle->id,
                    'nombreServicio' => $detalle->servicio->nombreServicio ?? 'Servicio no especificado',
                    'precioOriginal' => $precioOriginal,
                    'descuento' => $descuento,
                    'precioFinal' => $precioFinal,
                ]);
                
                return [
                    'id' => $detalle->id,
                    'nombreServicio' => $detalle->servicio->nombreServicio ?? 'Servicio no especificado',
                    'precio' => $precioOriginal,
                    'precioFinal' => $precioFinal,
                    'descuento' => $descuento,
                    'cantidad' => $detalle->cantidad ?? 1,
                    'subtotal' => $precioFinal * ($detalle->cantidad ?? 1),
                    'descripcion' => $detalle->descripcion,
                    'tamano' => $detalle->tamano,
                    'color' => $detalle->color,
                    'modelo' => $detalle->modelo,
                ];
            });

            return [
                'id' => $trabajo->id,
                'idCliente' => $trabajo->idCliente,
                'fechaRegistro' => $trabajo->fechaRegistro,
                'fechaEntrega' => $trabajo->fechaEntrega,
                'idEstado' => $trabajo->idEstado,
                'idEstadoPago' => $trabajo->estadoPago->id ?? 0,
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
        });
        
        $clientes = Clientes::orderBy('nombre')->get(['id', 'nombre', 'apellido', 'telefono']);
        $servicios = Servicios::where('estado', true)->get(['id', 'nombreServicio', 'precioReferencial']);
        $estadosTrabajo = EstadosTrabajo::all();
        $estadosPago = EstadoPago::all();
        
        // Preparar datos para Inertia
        $datosParaInertia = [
            'trabajos' => $trabajosSerializados,
            'clientes' => $clientes,
            'estadosTrabajo' => $estadosTrabajo,
            'estadosPago' => $estadosPago,
        ];
        
        Log::info('📤 Enviando datos a Inertia:', [
            'trabajos_count' => $trabajosSerializados->count(),
            'clientes_count' => $clientes->count(),
            'estados_trabajo_count' => $estadosTrabajo->count(),
            'estados_pago_count' => $estadosPago->count()
        ]);
        
        return Inertia::render('RegistrarTrabajos/Index', $datosParaInertia);
    }

    /**
     * Procesar cuota de pago para un trabajo específico.
     */
    public function procesarCuota(Request $request, $id)
    {
        $trabajo = Trabajos::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'monto' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::transaction(function () use ($request, $trabajo) {
                // Obtener o crear el detalle del trabajo
                $detalle = $trabajo->detallesTrabajo()->first();
                if (!$detalle) {
                    return response()->json(['error' => 'No se encontró detalle del trabajo'], 404);
                }

                // Obtener o crear el pago
                $pago = $detalle->pago;
                if (!$pago) {
                    $pago = Pagos::create([
                        'idTrabajo' => $trabajo->id,
                        'total' => 0,
                        'aCuenta' => 0,
                        'saldo' => 0,
                        'devoluciones' => 0,
                    ]);
                    $detalle->update(['idPago' => $pago->idPago]);
                }

                // Actualizar el pago con la nueva cuota
                $nuevoACuenta = $pago->aCuenta + $request->monto;
                $nuevoSaldo = $pago->total - $nuevoACuenta;

                $pago->update([
                    'aCuenta' => $nuevoACuenta,
                    'saldo' => max(0, $nuevoSaldo), // El saldo no puede ser negativo
                ]);

                // Actualizar el estado del pago
                $estadoPago = $this->determinarEstadoPago($nuevoSaldo, $nuevoACuenta, $nuevoACuenta + $nuevoSaldo);
                $trabajo->update(['idEstadoPago' => $estadoPago]);
            });

            return response()->json(['success' => true, 'message' => 'Cuota procesada exitosamente']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar la cuota: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clientes = Clientes::orderBy('nombre')->get(['id', 'nombre', 'apellido', 'telefono']);
        $servicios = Servicios::where('estado', true)->get(['id', 'nombreServicio', 'precioReferencial']);
        $estadosTrabajo = EstadosTrabajo::all();
        $estadosPago = EstadoPago::all();
        
        // Obtener usuarios activos para asignación de responsables
        $usuarios = Usuarios::where('estado', true)
            ->with('rol')
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'apellidoPaterno', 'apellidoMaterno', 'estado', 'idRol']);
        
        // Si viene un cliente_id, obtener la información del cliente
        $clientePreSeleccionado = null;
        if ($request->has('cliente_id')) {
            $clientePreSeleccionado = Clientes::find($request->cliente_id);
        }
        
        return Inertia::render('RegistrarTrabajos/Create', [
            'clientes' => $clientes,
            'servicios' => $servicios,
            'usuarios' => $usuarios, // Agregar lista de usuarios
            'estadosTrabajo' => $estadosTrabajo,
            'estadosPago' => $estadosPago,
            'clientePreSeleccionado' => $clientePreSeleccionado,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTrabajoRequest $request)
    {
        // Log para debugging
        Log::info('Datos recibidos en store:', $request->all());
        Log::info('Campo idEstadoPago recibido:', ['idEstadoPago' => $request->input('idEstadoPago')]);
        Log::info('Campo fechaEntrega recibido:', ['fechaEntrega' => $request->input('fechaEntrega')]);
        
        // Validar datos del formulario
        $validator = Validator::make($request->all(), [
            'cliente' => 'required|exists:clientes,id',
            'servicios' => 'required|array|min:1',
            'servicios.*.idServicio' => 'required|exists:servicios,id',
            'servicios.*.cantidad' => 'required|integer|min:1',
            'servicios.*.descuento' => 'nullable|numeric|min:0',
            'idResponsable' => 'nullable|exists:usuarios,id',
            'fechaEntrega' => 'required|date',
            'aCuenta' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            Log::error('Validación falló:', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        try {
            Log::info('Iniciando transacción de base de datos...');
            Log::info('Datos del request:', $request->all());
            
            DB::transaction(function () use ($request) {
                // 1. Crear el trabajo
                Log::info('Creando trabajo...');
                Log::info('🔍 idResponsable recibido:', [
                    'idResponsable' => $request->idResponsable,
                    'tipo' => gettype($request->idResponsable),
                    'es_null' => is_null($request->idResponsable),
                    'es_vacio' => empty($request->idResponsable)
                ]);
                // Convertir idResponsable a null si está vacío
                $idResponsable = $request->idResponsable;
                if (empty($idResponsable) || $idResponsable === '' || $idResponsable === '0') {
                    $idResponsable = null;
                } else {
                    $idResponsable = (int)$idResponsable;
                }
                
                Log::info('🔍 idResponsable procesado:', [
                    'original' => $request->idResponsable,
                    'procesado' => $idResponsable,
                    'tipo' => gettype($idResponsable)
                ]);
                
                $trabajo = Trabajos::create([
                    'idCliente' => $request->cliente,
                    'idResponsable' => $idResponsable, // Usuario responsable del trabajo
                    'fechaRegistro' => now(),
                    'fechaEntrega' => $request->fechaEntrega,
                    'idEstado' => 1, // ✅ ARQUITECTURA MVC - Siempre empieza en "Pendiente"
                ]);
                Log::info('Trabajo creado con ID: ' . $trabajo->getKey());
                Log::info('🔍 Trabajo creado con responsable:', [
                    'idTrabajo' => $trabajo->getKey(),
                    'idResponsable' => $trabajo->idResponsable,
                    'responsable_verificado' => $trabajo->fresh()->responsable ? $trabajo->fresh()->responsable->nombre : 'NO HAY RESPONSABLE'
                ]);

                // 2. Calcular el total general de todos los servicios
                $totalGeneral = 0;
                foreach ($request->servicios as $servicioData) {
                    $servicio = Servicios::find($servicioData['idServicio']);
                    if ($servicio) {
                        $subtotal = $servicio->precioReferencial * $servicioData['cantidad'];
                        $descuento = $servicioData['descuento'] ?? 0; // Descuento como monto fijo
                        $totalGeneral += $subtotal - $descuento;
                    }
                }
                
                // Convertir aCuenta a número para asegurar el tipo correcto
                $aCuenta = (float) $request->aCuenta;
                $saldo = $totalGeneral - $aCuenta;
                Log::info("Total General: $totalGeneral, A Cuenta: $aCuenta, Saldo: $saldo");

                // Los campos de pago se manejan en la tabla pagos, no en trabajos

                // 3. Determinar el estado del pago
                $estadoPago = $this->determinarEstadoPago($saldo, $aCuenta, $totalGeneral);
                Log::info('Estado del pago determinado: ' . $estadoPago);

                // 4. Crear el pago
                Log::info('Creando pago...');
                Log::info('🔍 Datos antes de crear pago:', [
                    'idTrabajo' => $trabajo->getKey(),
                    'total' => $totalGeneral,
                    'aCuenta_request' => $request->aCuenta,
                    'aCuenta_convertido' => $aCuenta,
                    'aCuenta_type' => gettype($aCuenta),
                    'saldo' => $saldo,
                    'idEstadoPago' => $estadoPago
                ]);
                
                $pago = Pagos::create([
                    'idTrabajo' => $trabajo->getKey(),
                    'total' => $totalGeneral,
                    'aCuenta' => $aCuenta,
                    'saldo' => $saldo,
                    'devoluciones' => 0
                ]);
                
                // Verificar que se guardó correctamente
                $pagoVerificado = Pagos::find($pago->getKey());
                Log::info('✅ Pago creado exitosamente:', [
                    'idPago' => $pago->getKey(),
                    'idTrabajo' => $trabajo->getKey(),
                    'total' => $totalGeneral,
                    'aCuenta_request' => $request->aCuenta,
                    'aCuenta_convertido' => $aCuenta,
                    'aCuenta_guardado' => $pagoVerificado->aCuenta,
                    'saldo' => $saldo,
                    'idEstadoPago' => $estadoPago,
                    'pago_verificado' => $pagoVerificado->toArray()
                ]);
                
                // 5. Crear los detalles del trabajo para cada servicio
                foreach ($request->servicios as $servicioData) {
                    Log::info('Creando detalle para servicio:', $servicioData);
                    
                    $detalle = DetalleTrabajo::create([
                        'idTrabajo' => $trabajo->getKey(),
                        'idServicio' => $servicioData['idServicio'],
                        'idPago' => $pago->getKey(),
                        'descripcion' => $servicioData['detalles']['descripcion'] ?? null,
                        'tamano' => $servicioData['detalles']['tamano'] ?? null,
                        'color' => $servicioData['detalles']['color'] ?? null,
                        'modelo' => $servicioData['detalles']['modelo'] ?? null,
                        'cantidad' => $servicioData['cantidad'],
                        'descuento' => $servicioData['descuento'] ?? 0,
                    ]);
                    Log::info('Detalle del trabajo creado con ID: ' . $detalle->getKey());
                }
                
                // 6. Actualizar el trabajo con el estado del pago
                $trabajo->update(['idEstadoPago' => $request->input('idEstadoPago') ?: $estadoPago]);
                
                Log::info('✅ Transacción completada exitosamente');
            });

            Log::info('Transacción completada exitosamente');
            return redirect()->route('registrar-trabajos')
                ->with('success', 'Trabajo registrado correctamente');

        } catch (\Exception $e) {
            Log::error('Error en la transacción: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('Request data: ' . json_encode($request->all()));
            return back()->withErrors(['error' => 'Error al registrar el trabajo: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $trabajo = Trabajos::with([
            'cliente',
            'detallesTrabajo.servicio', 
            'detallesTrabajo.pago',
            'estado',
            'estadoPago',
            'responsable'
        ])->where('slug', $slug)->firstOrFail();

        // ✅ ARQUITECTURA MVC - Calcular totales del trabajo
        $totalTrabajo = $trabajo->detallesTrabajo->sum(function ($detalle) {
            $precioOriginal = $detalle->servicio->precioReferencial ?? 0;
            $cantidad = $detalle->cantidad ?? 1;
            $descuento = $detalle->descuento ?? 0;
            
            $subtotalBruto = $precioOriginal * $cantidad;
            $subtotalFinal = $subtotalBruto - $descuento;
            
            return $subtotalFinal;
        });
        
        // Obtener datos de la tabla pagos (último pago)
        $ultimoPago = $trabajo->pagos()->latest('idPago')->first();
        $totalPagado = $ultimoPago ? $ultimoPago->aCuenta : 0;
        $saldo = $totalTrabajo - $totalPagado;

        // Crear un array estructurado para el frontend
        $trabajoData = [
            'id' => $trabajo->id,
            'slug' => $trabajo->slug,
            'idCliente' => $trabajo->idCliente,
            'idResponsable' => $trabajo->idResponsable,
            'fechaRegistro' => $trabajo->fechaRegistro,
            'fechaEntrega' => $trabajo->fechaEntrega,
            'idEstado' => $trabajo->idEstado,
            'idEstadoPago' => $trabajo->idEstadoPago,
            'observaciones' => $trabajo->observaciones ?? '',
            'total' => $totalTrabajo,
            'aCuenta' => $totalPagado,
            'saldo' => $saldo,
            'cliente' => $trabajo->cliente ? [
                'id' => $trabajo->cliente->id,
                'nombre' => $trabajo->cliente->nombre,
                'apellido' => $trabajo->cliente->apellido,
                'telefono' => $trabajo->cliente->telefono,
            ] : null,
            'responsable' => $trabajo->responsable ? [
                'id' => $trabajo->responsable->id,
                'nombre' => $trabajo->responsable->nombre,
                'apellidoPaterno' => $trabajo->responsable->apellidoPaterno,
                'apellidoMaterno' => $trabajo->responsable->apellidoMaterno,
                'rol' => $trabajo->responsable->rol ? $trabajo->responsable->rol->nombre : 'Sin rol'
            ] : null,
            'estado' => $trabajo->estado ? [
                'id' => $trabajo->estado->id,
                'nombre' => $trabajo->estado->nombre
            ] : null,
            'estadoPago' => $trabajo->estadoPago ? [
                'id' => $trabajo->estadoPago->id,
                'nombre' => $trabajo->estadoPago->nombre
            ] : null,
            'detallesTrabajo' => $trabajo->detallesTrabajo ? $trabajo->detallesTrabajo->map(function($detalle) {
                return [
                    'id' => $detalle->id,
                    'idServicio' => $detalle->idServicio,
                    'cantidad' => $detalle->cantidad,
                    'descuento' => $detalle->descuento ?? 0,
                    'descripcion' => $detalle->descripcion,
                    'tamano' => $detalle->tamano,
                    'color' => $detalle->color,
                    'modelo' => $detalle->modelo,
                    'servicio' => $detalle->servicio ? [
                        'id' => $detalle->servicio->id,
                        'nombreServicio' => $detalle->servicio->nombreServicio,
                        'precioReferencial' => $detalle->servicio->precioReferencial
                    ] : null,
                    'pago' => $detalle->pago ? [
                        'id' => $detalle->pago->id,
                        'monto' => $detalle->pago->monto,
                        'aCuenta' => $detalle->pago->aCuenta
                    ] : null
                ];
            })->toArray() : []
        ];
        
        // Debug: Log de datos que se envían a Inertia
        Log::info('Datos del trabajo para Show:', [
            'trabajo_id' => $trabajo->id,
            'cliente' => $trabajo->cliente ? $trabajo->cliente->toArray() : 'NO HAY CLIENTE',
            'detallesTrabajo' => $trabajo->detallesTrabajo ? $trabajo->detallesTrabajo->toArray() : 'NO HAY DETALLES',
            'estadoPago' => $trabajo->estadoPago ? $trabajo->estadoPago->toArray() : 'NO HAY ESTADO PAGO',
            'estado' => $trabajo->estado ? $trabajo->estado->toArray() : 'NO HAY ESTADO'
        ]);

        // Obtener otros trabajos del mismo cliente
        $otrosTrabajosCliente = Trabajos::with(['detallesTrabajo.servicio', 'detallesTrabajo'])
            ->where('idCliente', $trabajo->idCliente)
            ->where('id', '!=', $trabajo->id)
            ->get();

        // Obtener historial de pagos
        $historialPagos = Pagos::where('idTrabajo', $trabajo->id)
            ->orderBy('idPago', 'desc')
            ->get();

        // Obtener estados de pago para el modal
        $estadosPago = EstadoPago::all();

        return Inertia::render('RegistrarTrabajos/Show', [
            'trabajo' => $trabajoData,
            'otrosTrabajosCliente' => $otrosTrabajosCliente,
            'historialPagos' => $historialPagos,
            'estadosPago' => $estadosPago,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        Log::info('🚨🚨🚨 MÉTODO EDIT INICIADO 🚨🚨🚨');
        Log::info('🔄 MÉTODO EDIT LLAMADO - Slug:', ['slug' => $slug]);
        
        $trabajo = Trabajos::with([
            'cliente',
            'detallesTrabajo.servicio',
            'detallesTrabajo.pago',
            'estado',
            'estadoPago',
            'responsable'
        ])->where('slug', $slug)->firstOrFail();
        
        // Obtener historial de pagos
        $historialPagos = Pagos::where('idTrabajo', $trabajo->id)
            ->orderBy('idPago', 'desc')
            ->get();

        // ✅ ARQUITECTURA MVC - Calcular totales del trabajo
        $totalTrabajo = $trabajo->detallesTrabajo->sum(function ($detalle) {
            $precioOriginal = $detalle->servicio->precioReferencial ?? 0;
            $cantidad = $detalle->cantidad ?? 1;
            $descuento = $detalle->descuento ?? 0;
            
            $subtotalBruto = $precioOriginal * $cantidad;
            $subtotalFinal = $subtotalBruto - $descuento;
            
            return $subtotalFinal;
        });
        
        // Obtener datos de la tabla pagos (último pago)
        $ultimoPago = $trabajo->pagos()->latest('idPago')->first();
        $totalPagado = $ultimoPago ? $ultimoPago->aCuenta : 0;
        $saldo = $totalTrabajo - $totalPagado;
        
        // Debug: Log de datos que se envían a Inertia
        Log::info('Datos del trabajo para Edit:', [
            'trabajo_id' => $trabajo->id,
            'totalTrabajo' => $totalTrabajo,
            'totalPagado' => $totalPagado,
            'saldo' => $saldo,
            'idResponsable' => $trabajo->idResponsable,
            'cliente' => $trabajo->cliente ? $trabajo->cliente->toArray() : 'NO HAY CLIENTE',
            'detallesTrabajo' => $trabajo->detallesTrabajo ? $trabajo->detallesTrabajo->toArray() : 'NO HAY DETALLES',
            'estadoPago' => $trabajo->estadoPago ? $trabajo->estadoPago->toArray() : 'NO HAY ESTADO PAGO',
            'estado' => $trabajo->estado ? $trabajo->estado->toArray() : 'NO HAY ESTADO'
        ]);
        
        // Crear un array estructurado para el frontend
        $trabajoData = [
            'id' => $trabajo->id,
            'slug' => $trabajo->slug,
            'idCliente' => $trabajo->idCliente,
            'idResponsable' => $trabajo->idResponsable,
            'fechaRegistro' => $trabajo->fechaRegistro,
            'fechaEntrega' => $trabajo->fechaEntrega,
            'idEstado' => $trabajo->idEstado,
            'idEstadoPago' => $trabajo->idEstadoPago,
            'observaciones' => $trabajo->observaciones ?? '',
            'total' => $totalTrabajo,
            'aCuenta' => $totalPagado,
            'saldo' => $saldo,
            'cliente' => $trabajo->cliente ? [
                'id' => $trabajo->cliente->id,
                'nombre' => $trabajo->cliente->nombre,
                'apellido' => $trabajo->cliente->apellido,
                'telefono' => $trabajo->cliente->telefono,
            ] : null,
            'responsable' => $trabajo->responsable ? [
                'id' => $trabajo->responsable->id,
                'nombre' => $trabajo->responsable->nombre,
                'apellido' => $trabajo->responsable->apellidoPaterno,
                'rol' => $trabajo->responsable->rol ? $trabajo->responsable->rol->nombre : 'Sin rol'
            ] : null,
            'estado' => $trabajo->estado ? [
                'id' => $trabajo->estado->id,
                'nombre' => $trabajo->estado->nombre
            ] : null,
            'estadoPago' => $trabajo->estadoPago ? [
                'id' => $trabajo->estadoPago->id,
                'nombre' => $trabajo->estadoPago->nombre
            ] : null,
            'detallesTrabajo' => $trabajo->detallesTrabajo ? $trabajo->detallesTrabajo->map(function($detalle) {
                return [
                    'id' => $detalle->id,
                    'idServicio' => $detalle->idServicio,
                    'cantidad' => $detalle->cantidad,
                    'descuento' => $detalle->descuento ?? 0,
                    'descripcion' => $detalle->descripcion,
                    'tamano' => $detalle->tamano,
                    'color' => $detalle->color,
                    'modelo' => $detalle->modelo,
                    'servicio' => $detalle->servicio ? [
                        'id' => $detalle->servicio->id,
                        'nombreServicio' => $detalle->servicio->nombreServicio,
                        'precioReferencial' => $detalle->servicio->precioReferencial
                    ] : null,
                    'pago' => $detalle->pago ? [
                        'id' => $detalle->pago->id,
                        'monto' => $detalle->pago->monto,
                        'aCuenta' => $detalle->pago->aCuenta
                    ] : null
                ];
            })->toArray() : []
        ];

        $clientes = Clientes::all();
        $servicios = Servicios::where('estado', true)->get();
        $estadosTrabajo = EstadosTrabajo::all();
        $estadosPago = EstadoPago::all();
        
        // Obtener usuarios activos para asignación de responsables
        $responsables = Usuarios::where('estado', true)
            ->with('rol')
            ->orderBy('nombre')
            ->orderBy('apellidoPaterno')
            ->get(['id', 'nombre', 'apellidoPaterno', 'apellidoMaterno', 'estado', 'idRol']);

        return Inertia::render('RegistrarTrabajos/Edit', [
            'trabajo' => $trabajoData,
            'clientes' => $clientes,
            'servicios' => $servicios,
            'usuarios' => $responsables, // Cambiado a 'usuarios' para coincidir con Edit.vue
            'estadosTrabajo' => $estadosTrabajo,
            'estadosPago' => $estadosPago,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTrabajoRequest $request, $slug)
    {
        Log::info('🚨🚨🚨 MÉTODO UPDATE INICIADO 🚨🚨🚨');
        Log::info('🔄 MÉTODO UPDATE LLAMADO - Slug:', ['slug' => $slug]);
        Log::info('🔄 Método HTTP:', ['method' => $request->method()]);
        Log::info('🔄 URL completa:', ['url' => $request->fullUrl()]);
        Log::info('📦 Datos recibidos:', $request->all());
        
        $trabajo = Trabajos::where('slug', $slug)->firstOrFail();
        Log::info('✅ Trabajo encontrado:', ['id' => $trabajo->id, 'slug' => $trabajo->slug]);

        $validator = Validator::make($request->all(), [
            'cliente' => 'required|exists:clientes,id',
            'servicios' => 'required|array|min:1',
            'servicios.*.idServicio' => 'required|exists:servicios,id',
            'servicios.*.cantidad' => 'required|integer|min:1',
            'servicios.*.descuento' => 'nullable|numeric|min:0',
            'idResponsable' => 'nullable|exists:usuarios,id',
            'fechaEntrega' => 'required|date',
            // ✅ ARQUITECTURA MVC - Estado se maneja desde la lista, no desde el formulario
            'aCuenta' => 'required|numeric|min:0',
            'idEstadoPago' => 'required|exists:estados_pago,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $trabajo) {
                // Actualizar trabajo
                $trabajo->update([
                    'idCliente' => $request->input('cliente'),
                    'idResponsable' => $request->input('idResponsable'),
                    'fechaEntrega' => $request->input('fechaEntrega'),
                    // ✅ ARQUITECTURA MVC - Estado se maneja desde la lista, no desde el formulario
                ]);

                // Eliminar detalles existentes
                $trabajo->detallesTrabajo()->delete();

                // Calcular el total general de todos los servicios
                $totalGeneral = 0;
                foreach ($request->servicios as $servicioData) {
                    $servicio = Servicios::find($servicioData['idServicio']);
                    if ($servicio) {
                        $subtotal = $servicio->precioReferencial * $servicioData['cantidad'];
                        $descuento = $servicioData['descuento'] ?? 0;
                        $totalGeneral += $subtotal - $descuento;
                    }
                }
                
                // Convertir aCuenta a número para asegurar el tipo correcto
                $aCuenta = (float) $request->aCuenta;
                $saldo = $totalGeneral - $aCuenta;
                
                // Debug: Log de datos calculados
                Log::info('🔍 Datos calculados en update para trabajo ' . $trabajo->id, [
                    'totalGeneral' => $totalGeneral,
                    'aCuenta_request' => $request->aCuenta,
                    'aCuenta_convertido' => $aCuenta,
                    'saldo' => $saldo
                ]);

                // Los campos de pago se manejan en la tabla pagos, no en trabajos

                // Determinar el estado del pago
                $estadoPago = $this->determinarEstadoPago($saldo, $aCuenta, $totalGeneral);

                // Actualizar o crear pago existente
                $pagoExistente = Pagos::where('idTrabajo', $trabajo->id)->latest('idPago')->first();
                
                if ($pagoExistente) {
                    // Actualizar pago existente
                    $pagoExistente->update([
                        'total' => $totalGeneral,
                        'aCuenta' => $aCuenta,
                        'saldo' => $saldo,
                    ]);
                    $pago = $pagoExistente;
                    Log::info('✅ Pago existente actualizado para trabajo ' . $trabajo->id);
                } else {
                    // Crear nuevo pago si no existe
                    $pago = Pagos::create([
                        'idTrabajo' => $trabajo->id,
                        'total' => $totalGeneral,
                        'aCuenta' => $aCuenta,
                        'saldo' => $saldo,
                    ]);
                    Log::info('✅ Nuevo pago creado para trabajo ' . $trabajo->id);
                }

                // Crear los nuevos detalles del trabajo
                foreach ($request->servicios as $servicioData) {
                    DetalleTrabajo::create([
                        'idTrabajo' => $trabajo->id,
                        'idServicio' => $servicioData['idServicio'],
                        'idPago' => $pago->id,
                        'descripcion' => $servicioData['detalles']['descripcion'] ?? null,
                        'tamano' => $servicioData['detalles']['tamano'] ?? null,
                        'color' => $servicioData['detalles']['color'] ?? null,
                        'modelo' => $servicioData['detalles']['modelo'] ?? null,
                        'cantidad' => $servicioData['cantidad'],
                        'descuento' => $servicioData['descuento'] ?? 0,
                    ]);
                }
                
                // Actualizar el estado del pago en el trabajo
                $trabajo->update(['idEstadoPago' => $request->input('idEstadoPago') ?: $estadoPago]);

                // Generar nuevo slug si es necesario
                $trabajo->generateSlug();
                $trabajo->save();
            });

            return redirect()->route('registrar-trabajos')
                ->with('success', 'Trabajo actualizado correctamente');

        } catch (\Exception $e) {
            Log::error('Error al actualizar el trabajo: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al actualizar el trabajo: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        try {
            DB::transaction(function () use ($slug) {
                $trabajo = Trabajos::where('slug', $slug)->firstOrFail();
                
                Log::info('Eliminando trabajo Slug: ' . $slug . ' con cascada automática');
                
                // Con las restricciones de cascada configuradas, solo necesitamos eliminar el trabajo
                // Las tablas relacionadas se eliminarán automáticamente
                $trabajo->delete();
                
                Log::info('✅ Trabajo eliminado exitosamente con cascada');
            });

            return redirect()->route('registrar-trabajos')
                ->with('success', 'Trabajo eliminado correctamente');

        } catch (\Exception $e) {
            Log::error('Error al eliminar el trabajo: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->withErrors(['error' => 'Error al eliminar el trabajo: ' . $e->getMessage()]);
        }
    }

    /**
     * ✅ ARQUITECTURA MVC - Cambiar estado del trabajo
     */
    public function cambiarEstado(Request $request, $id)
    {
        // Verificar autenticación
        if (!auth()->check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        // Validar datos
        $request->validate([
            'idEstado' => 'required|exists:estados_trabajo,id'
        ]);

        // Buscar el trabajo
        $trabajo = Trabajos::findOrFail($id);
        
        // Guardar estado anterior para logging
        $estadoAnterior = $trabajo->idEstado;
        $estadoPagoAnterior = $trabajo->idEstadoPago; // ✅ Guardar estado de pago original
        
        DB::transaction(function () use ($trabajo, $request, $estadoAnterior, $estadoPagoAnterior) {
            // Actualizar estado del trabajo
            $trabajo->update([
                'idEstado' => $request->idEstado
            ]);

            // ✅ LÓGICA DE NEGOCIO: Sincronización de estados de pago
            if ($request->idEstado == 5) {
                // Si el estado es "Cancelado" (ID: 5), sincronizar estado de pago
                $estadoPagoCancelado = \App\Models\EstadoPago::where('nombre', 'Cancelado')->first();
                
                if ($estadoPagoCancelado) {
                    // Actualizar estado de pago del trabajo
                    $trabajo->update([
                        'idEstadoPago' => $estadoPagoCancelado->id
                    ]);

                    // Actualizar estado de pago en la tabla pagos
                    $pago = $trabajo->pagos()->latest('idPago')->first();
                    // El estado de pago se maneja en la tabla trabajos, no en pagos

                    Log::info('Estado de pago sincronizado automáticamente al cancelar trabajo', [
                        'trabajo_id' => $trabajo->id,
                        'estado_pago_anterior' => $estadoPagoAnterior,
                        'estado_pago_actualizado' => $estadoPagoCancelado->id,
                        'usuario' => auth()->user()->nombre ?? 'Usuario',
                        'fecha' => now()
                    ]);
                }
            } elseif ($estadoAnterior == 5 && $request->idEstado != 5) {
                // ✅ PROCESO INVERSO: Calcular estado de pago basado en total, aCuenta y saldo
                $pago = $trabajo->pagos()->latest('idPago')->first();
                if ($pago) {
                    $total = $pago->total ?? 0;
                    $aCuenta = $pago->aCuenta ?? 0;
                    $saldo = $pago->saldo ?? 0;
                    
                    // ✅ ARQUITECTURA MVC - Usar función centralizada para determinar estado de pago
                    $nuevoEstadoPago = $this->determinarEstadoPago($saldo, $aCuenta, $total);
                    
                    // Actualizar estado de pago del trabajo
                    $trabajo->update([
                        'idEstadoPago' => $nuevoEstadoPago
                    ]);

                    // El estado de pago se maneja en la tabla trabajos, no en pagos

                    Log::info('Estado de pago calculado al cambiar de Cancelado a otro estado', [
                        'trabajo_id' => $trabajo->id,
                        'estado_anterior' => $estadoAnterior,
                        'estado_nuevo' => $request->idEstado,
                        'total' => $total,
                        'aCuenta' => $aCuenta,
                        'saldo' => $saldo,
                        'estado_pago_calculado' => $nuevoEstadoPago,
                        'usuario' => auth()->user()->nombre ?? 'Usuario',
                        'fecha' => now()
                    ]);
                }
            }
        });

        // Log del cambio
        Log::info('Estado de trabajo cambiado', [
            'trabajo_id' => $trabajo->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $request->idEstado,
            'usuario' => auth()->user()->nombre ?? 'Usuario',
            'fecha' => now()
        ]);

        // Recargar el trabajo con todas sus relaciones para retornar datos completos
        $trabajoActualizado = Trabajos::with([
            'cliente',
            'responsable.rol', 
            'estado',
            'estadoPago',
            'detallesTrabajo.servicio',
            'pagos'
        ])->find($trabajo->id);

        // ✅ USAR EXACTAMENTE LA MISMA LÓGICA QUE EL MÉTODO INDEX
        // Calcular total CON descuentos aplicados correctamente (igual que en index)
        $totalTrabajo = $trabajoActualizado->detallesTrabajo->sum(function ($detalle) {
            $precioOriginal = $detalle->servicio->precioReferencial ?? 0;
            $cantidad = $detalle->cantidad ?? 1;
            $descuento = $detalle->descuento ?? 0;
            
            // Calcular subtotal bruto (precio × cantidad)
            $subtotalBruto = $precioOriginal * $cantidad;
            
            // Aplicar descuento al subtotal bruto
            $subtotalFinal = $subtotalBruto - $descuento;
            
            return $subtotalFinal;
        });

        // Obtener datos de la tabla pagos (último pago) - igual que en index
        $ultimoPago = $trabajoActualizado->pagos()->latest('idPago')->first();
        $totalPagado = $ultimoPago ? $ultimoPago->aCuenta : 0;
        $saldo = $totalTrabajo - $totalPagado;

        // Transformar servicios para el frontend (igual que en index)
        $servicios = $trabajoActualizado->detallesTrabajo->map(function ($detalle) {
            $precioOriginal = $detalle->servicio->precioReferencial ?? 0;
            $descuento = $detalle->descuento ?? 0;
            $precioFinal = $precioOriginal - $descuento;
            
            return [
                'id' => $detalle->id,
                'nombreServicio' => $detalle->servicio->nombreServicio ?? 'Servicio no especificado',
                'precio' => $precioOriginal,
                'precioFinal' => $precioFinal,
                'descuento' => $descuento,
                'cantidad' => $detalle->cantidad ?? 1,
                'subtotal' => $precioFinal * ($detalle->cantidad ?? 1),
                'descripcion' => $detalle->descripcion,
                'tamano' => $detalle->tamano,
                'color' => $detalle->color,
                'modelo' => $detalle->modelo,
            ];
        });

        // ✅ USAR EXACTAMENTE LA MISMA ESTRUCTURA QUE EL MÉTODO INDEX
        $trabajoData = [
            'id' => $trabajoActualizado->id,
            'idCliente' => $trabajoActualizado->idCliente,
            'fechaRegistro' => $trabajoActualizado->fechaRegistro,
            'fechaEntrega' => $trabajoActualizado->fechaEntrega,
            'idEstado' => $trabajoActualizado->idEstado,
            'idEstadoPago' => $trabajoActualizado->estadoPago->id ?? 0,
            'observaciones' => $trabajoActualizado->observaciones ?? '',
            'slug' => $trabajoActualizado->slug ?? '',
            'total' => $totalTrabajo,
            'aCuenta' => $totalPagado,
            'saldo' => $saldo,
            'cliente' => [
                'id' => $trabajoActualizado->cliente->id ?? 0,
                'nombre' => $trabajoActualizado->cliente->nombre ?? 'Cliente no especificado',
                'apellido' => $trabajoActualizado->cliente->apellido ?? '',
                'email' => $trabajoActualizado->cliente->email ?? null,
                'telefono' => $trabajoActualizado->cliente->telefono ?? null,
                'direccion' => $trabajoActualizado->cliente->direccion ?? null,
            ],
            'estado' => [
                'id' => $trabajoActualizado->estado->id ?? 0,
                'nombre' => $trabajoActualizado->estado->nombre ?? 'Sin estado',
            ],
            'estadoPago' => [
                'id' => $trabajoActualizado->estadoPago->id ?? 0,
                'nombre' => $trabajoActualizado->estadoPago->nombre ?? 'Sin estado',
            ],
            'responsable' => $trabajoActualizado->responsable ? [
                'id' => $trabajoActualizado->responsable->id ?? 0,
                'nombre' => $trabajoActualizado->responsable->nombre ?? 'Sin responsable',
                'apellido' => $trabajoActualizado->responsable->apellidoPaterno ?? '',
                'rol' => $trabajoActualizado->responsable->rol ? $trabajoActualizado->responsable->rol->nombre : 'Sin rol'
            ] : null,
            'servicios' => $servicios,
        ];

        // Debug: Verificar que el trabajo se cargó correctamente
        Log::info('Trabajo actualizado retornado', [
            'trabajo_id' => $trabajoActualizado->id,
            'estado_trabajo' => $trabajoActualizado->estado->nombre ?? 'Sin estado',
            'estado_pago' => $trabajoActualizado->estadoPago->nombre ?? 'Sin estado',
            'cliente' => $trabajoActualizado->cliente->nombre ?? 'Sin cliente',
            'servicios_count' => $trabajoActualizado->detallesTrabajo->count(),
            'ultimo_pago' => $ultimoPago ? [
                'total' => $ultimoPago->total,
                'aCuenta' => $ultimoPago->aCuenta,
                'saldo' => $ultimoPago->saldo
            ] : 'Sin pago',
            'totales_calculados' => [
                'totalTrabajo' => $totalTrabajo,
                'totalPagado' => $totalPagado,
                'saldo' => $saldo
            ],
            'servicios_originales' => $servicios->toArray(),
            'trabajo_data_completo' => $trabajoData
        ]);

        return response()->json([
            'success' => 'Estado actualizado exitosamente',
            'nuevoEstado' => $trabajoActualizado->estado->nombre ?? 'Sin estado',
            'trabajo' => $trabajoData // Retornar el trabajo transformado para el frontend
        ]);
    }

    /**
     * Agregar una cuota a un trabajo existente
     */
    public function agregarCuota(Request $request, $id)
    {
        try {
            $trabajo = Trabajos::findOrFail($id);
            
            // Validar el monto de la cuota
            $request->validate([
                'monto' => 'required|numeric|min:0.01',
                'nuevoEstadoPago' => 'nullable|exists:estados_pago,id'
            ]);
            
            $montoCuota = $request->monto;
            
            // Verificar que el monto no exceda el saldo pendiente
            if ($montoCuota > $trabajo->saldo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El monto de la cuota no puede exceder el saldo pendiente'
                ], 400);
            }
            
            // Actualizar el trabajo
            // Obtener el último pago para calcular los nuevos valores
            $ultimoPago = $trabajo->pagos()->latest('idPago')->first();
            $totalTrabajo = $ultimoPago ? $ultimoPago->total : 0;
            $nuevoACuenta = ($ultimoPago ? $ultimoPago->aCuenta : 0) + $montoCuota;
            $nuevoSaldo = $totalTrabajo - $nuevoACuenta;
            
            // Cambiar estado si se especifica
            if ($request->nuevoEstadoPago) {
                $trabajo->idEstadoPago = $request->nuevoEstadoPago;
                $trabajo->save();
            }
            
            // Crear registro en la tabla pagos para historial
            Pagos::create([
                'idTrabajo' => $trabajo->id,
                'total' => $totalTrabajo,
                'aCuenta' => $nuevoACuenta,
                'saldo' => $nuevoSaldo,
                'devoluciones' => 0
            ]);
            
            // Cargar relaciones para la respuesta
            $trabajo->load(['cliente', 'estadoPago', 'responsable']);
            
            return response()->json([
                'success' => true,
                'message' => 'Cuota procesada exitosamente',
                'trabajo' => $trabajo
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al procesar cuota: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la cuota: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancelar trabajo con observaciones y monto devuelto
     * ARQUITECTURA MVC - Lógica de negocio en el backend
     */
    public function cancelarTrabajo(Request $request, $id)
    {
        try {
            // Validación de datos (campos opcionales)
            $request->validate([
                'observaciones' => 'nullable|string|max:200',
                'montoDevuelto' => 'nullable|numeric|min:0'
            ]);

            $trabajo = Trabajos::findOrFail($id);
            
            // Verificar que el trabajo no esté ya cancelado
            if ($trabajo->idEstado == 5) { // 5 = Cancelado
                return response()->json([
                    'success' => false,
                    'message' => 'El trabajo ya está cancelado'
                ], 400);
            }

            DB::transaction(function () use ($trabajo, $request) {
                // 1. Actualizar trabajo
                $trabajo->update([
                    'idEstado' => 5, // Cancelado
                    'observaciones' => $request->observaciones
                ]);

                // 2. Actualizar pago con devoluciones (solo si se proporciona monto)
                $pago = $trabajo->pagos()->latest('idPago')->first();
                if ($pago) {
                    $updateData = [
                        'devoluciones' => $request->montoDevuelto ?? 0
                    ];
                    
                    // Solo actualizar devoluciones si se proporciona un monto
                    if ($request->montoDevuelto && $request->montoDevuelto > 0) {
                        $updateData['devoluciones'] = $request->montoDevuelto;
                    }
                    
                    $pago->update($updateData);
                }
                
                // 3. Actualizar también el estado de pago del trabajo
                $trabajo->update([
                    'idEstadoPago' => 4 // Estado de pago cancelado
                ]);

                // 4. Log de auditoría
                Log::info('Trabajo cancelado', [
                    'trabajo_id' => $trabajo->id,
                    'cliente' => $trabajo->cliente->nombre ?? 'Sin cliente',
                    'observaciones' => $request->observaciones,
                    'monto_devuelto' => $request->montoDevuelto,
                    'usuario' => auth()->user()->nombre ?? 'Usuario',
                    'fecha' => now()
                ]);
            });

            // Recargar el trabajo con todas sus relaciones para retornar datos completos
            $trabajoActualizado = Trabajos::with([
                'cliente',
                'responsable.rol', 
                'estado',
                'estadoPago',
                'detallesTrabajo.servicio',
                'pagos'
            ])->find($trabajo->id);

            // ✅ USAR EXACTAMENTE LA MISMA LÓGICA QUE EL MÉTODO INDEX
            // Calcular total CON descuentos aplicados correctamente (igual que en index)
            $totalTrabajo = $trabajoActualizado->detallesTrabajo->sum(function ($detalle) {
                $precioOriginal = $detalle->servicio->precioReferencial ?? 0;
                $cantidad = $detalle->cantidad ?? 1;
                $descuento = $detalle->descuento ?? 0;
                
                // Calcular subtotal bruto (precio × cantidad)
                $subtotalBruto = $precioOriginal * $cantidad;
                
                // Aplicar descuento al subtotal bruto
                $subtotalFinal = $subtotalBruto - $descuento;
                
                return $subtotalFinal;
            });

            // Obtener datos de la tabla pagos (último pago) - igual que en index
            $ultimoPago = $trabajoActualizado->pagos()->latest('idPago')->first();
            $totalPagado = $ultimoPago ? $ultimoPago->aCuenta : 0;
            $saldo = $totalTrabajo - $totalPagado;

            // Transformar servicios para el frontend (igual que en index)
            $servicios = $trabajoActualizado->detallesTrabajo->map(function ($detalle) {
                $precioOriginal = $detalle->servicio->precioReferencial ?? 0;
                $descuento = $detalle->descuento ?? 0;
                $precioFinal = $precioOriginal - $descuento;
                
                return [
                    'id' => $detalle->id,
                    'nombreServicio' => $detalle->servicio->nombreServicio ?? 'Servicio no especificado',
                    'precio' => $precioOriginal,
                    'precioFinal' => $precioFinal,
                    'descuento' => $descuento,
                    'cantidad' => $detalle->cantidad ?? 1,
                    'subtotal' => $precioFinal * ($detalle->cantidad ?? 1),
                    'descripcion' => $detalle->descripcion,
                    'tamano' => $detalle->tamano,
                    'color' => $detalle->color,
                    'modelo' => $detalle->modelo,
                ];
            });

            // ✅ USAR EXACTAMENTE LA MISMA ESTRUCTURA QUE EL MÉTODO INDEX
            $trabajoData = [
                'id' => $trabajoActualizado->id,
                'idCliente' => $trabajoActualizado->idCliente,
                'fechaRegistro' => $trabajoActualizado->fechaRegistro,
                'fechaEntrega' => $trabajoActualizado->fechaEntrega,
                'idEstado' => $trabajoActualizado->idEstado,
                'idEstadoPago' => $trabajoActualizado->estadoPago->id ?? 0,
                'observaciones' => $trabajoActualizado->observaciones ?? '',
                'slug' => $trabajoActualizado->slug ?? '',
                'total' => $totalTrabajo,
                'aCuenta' => $totalPagado,
                'saldo' => $saldo,
                'cliente' => [
                    'id' => $trabajoActualizado->cliente->id ?? 0,
                    'nombre' => $trabajoActualizado->cliente->nombre ?? 'Cliente no especificado',
                    'apellido' => $trabajoActualizado->cliente->apellido ?? '',
                    'email' => $trabajoActualizado->cliente->email ?? null,
                    'telefono' => $trabajoActualizado->cliente->telefono ?? null,
                    'direccion' => $trabajoActualizado->cliente->direccion ?? null,
                ],
                'estado' => [
                    'id' => $trabajoActualizado->estado->id ?? 0,
                    'nombre' => $trabajoActualizado->estado->nombre ?? 'Sin estado',
                ],
                'estadoPago' => [
                    'id' => $trabajoActualizado->estadoPago->id ?? 0,
                    'nombre' => $trabajoActualizado->estadoPago->nombre ?? 'Sin estado',
                ],
                'responsable' => $trabajoActualizado->responsable ? [
                    'id' => $trabajoActualizado->responsable->id ?? 0,
                    'nombre' => $trabajoActualizado->responsable->nombre ?? 'Sin responsable',
                    'apellido' => $trabajoActualizado->responsable->apellidoPaterno ?? '',
                    'rol' => $trabajoActualizado->responsable->rol ? $trabajoActualizado->responsable->rol->nombre : 'Sin rol'
                ] : null,
                'servicios' => $servicios,
            ];

            // Debug: Verificar que el trabajo se cargó correctamente
            Log::info('Trabajo cancelado retornado', [
                'trabajo_id' => $trabajoActualizado->id,
                'estado_trabajo' => $trabajoActualizado->estado->nombre ?? 'Sin estado',
                'estado_pago' => $trabajoActualizado->estadoPago->nombre ?? 'Sin estado',
                'cliente' => $trabajoActualizado->cliente->nombre ?? 'Sin cliente',
                'servicios_count' => $trabajoActualizado->detallesTrabajo->count()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trabajo cancelado exitosamente',
                'trabajo' => $trabajoData // Retornar el trabajo transformado para el frontend
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cancelar trabajo: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar el trabajo: ' . $e->getMessage()
            ], 500);
        }
    }
}