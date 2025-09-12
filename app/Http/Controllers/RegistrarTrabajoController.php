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

class RegistrarTrabajoController extends Controller
{
    /**
     * Determinar el estado del pago basado en el saldo y monto pagado
     */
    private function determinarEstadoPago($saldo, $montoPagado)
    {
        if ($saldo == 0) {
            return 3; // Completado
        } elseif ($montoPagado == 0) {
            return 4; // Cancelado
        } else {
            return 2; // Parcial
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trabajos = Trabajos::with([
            'cliente', 
            'detallesTrabajo.servicio', 
            'detallesTrabajo.pago', 
            'estado', 
            'estadoPago',
            'usuario',
            'responsable',
            'pagos' // Agregar relación con pagos
        ])->orderBy('fechaRegistro', 'desc')->get();
        
        // Transformar los datos para el frontend en el formato correcto
        $trabajosSerializados = $trabajos->map(function ($trabajo) {
            // Calcular total CON descuentos aplicados (en tiempo real)
            $totalTrabajo = $trabajo->detallesTrabajo->sum(function ($detalle) {
                $precioOriginal = $detalle->servicio->precioReferencial ?? 0;
                $descuento = $detalle->descuento ?? 0;
                $precioFinal = $precioOriginal - $descuento;
                $cantidad = $detalle->cantidad ?? 1;
                return $precioFinal * $cantidad;
            });

            // Obtener datos de la tabla pagos (último pago)
            $ultimoPago = $trabajo->pagos()->latest('idPago')->first();
            $totalPagado = $ultimoPago ? $ultimoPago->aCuenta : 0;
            $saldo = $totalTrabajo - $totalPagado;
            
            // Debug: Log de datos de pago
            Log::info('🔍 Datos de pago para trabajo ' . $trabajo->id, [
                'totalTrabajoCalculado' => $totalTrabajo,
                'totalPagado' => $totalPagado,
                'saldoCalculado' => $saldo,
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
                    'tipoDocumento' => $detalle->tipoDocumento,
                    'tipoEvento' => $detalle->tipoEvento,
                ];
            });

            return [
                'id' => $trabajo->id,
                'idCliente' => $trabajo->idCliente,
                'fechaRegistro' => $trabajo->fechaRegistro,
                'fechaEntrega' => $trabajo->fechaEntrega,
                'idEstado' => $trabajo->idEstado,
                'idEstadoPago' => $trabajo->estadoPago->id ?? 0,
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
    public function store(Request $request)
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
            'estadoTrabajo' => 'required|exists:estados_trabajo,id',
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
                $trabajo = Trabajos::create([
                    'idCliente' => $request->cliente,
                    'idUsuario' => Auth::id(), // Usuario que registra (sesión actual)
                    'idResponsable' => $request->idResponsable, // Usuario responsable del trabajo
                    'fechaRegistro' => now(),
                    'fechaEntrega' => $request->fechaEntrega,
                    'idEstado' => $request->estadoTrabajo,
                ]);
                Log::info('Trabajo creado con ID: ' . $trabajo->getKey());

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
                
                $saldo = $totalGeneral - $request->aCuenta;
                Log::info("Total General: $totalGeneral, A Cuenta: {$request->aCuenta}, Saldo: $saldo");

                // Los campos de pago se manejan en la tabla pagos, no en trabajos

                // 3. Determinar el estado del pago
                $estadoPago = $this->determinarEstadoPago($saldo, $request->aCuenta);
                Log::info('Estado del pago determinado: ' . $estadoPago);

                // 4. Crear el pago
                Log::info('Creando pago...');
                $pago = Pagos::create([
                    'idTrabajo' => $trabajo->getKey(),
                    'total' => $totalGeneral,
                    'aCuenta' => $request->aCuenta,
                    'saldo' => $saldo,
                    'idEstadoPago' => $estadoPago
                ]);
                Log::info('✅ Pago creado exitosamente:', [
                    'idPago' => $pago->getKey(),
                    'idTrabajo' => $trabajo->getKey(),
                    'total' => $totalGeneral,
                    'aCuenta' => $request->aCuenta,
                    'saldo' => $saldo,
                    'idEstadoPago' => $estadoPago
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
                        'tipoDocumento' => $servicioData['detalles']['tipoDocumento'] ?? null,
                        'tipoEvento' => $servicioData['detalles']['tipoEvento'] ?? null,
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
            'usuario',
            'responsable'
        ])->where('slug', $slug)->firstOrFail();

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
                    'tipoDocumento' => $detalle->tipoDocumento,
                    'tipoEvento' => $detalle->tipoEvento,
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
            'usuario',
            'responsable'
        ])->where('slug', $slug)->firstOrFail();
        
        // Obtener historial de pagos
        $historialPagos = Pagos::where('idTrabajo', $trabajo->id)
            ->orderBy('idPago', 'desc')
            ->get();

        // Crear un array con todos los datos del trabajo
        $trabajoData = $trabajo->toArray();

        // Debug: Log de datos que se envían a Inertia
        Log::info('Datos del trabajo para Edit:', [
            'trabajo_id' => $trabajo->id,
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
                    'tipoDocumento' => $detalle->tipoDocumento,
                    'tipoEvento' => $detalle->tipoEvento,
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
    public function update(Request $request, $slug)
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
            'estadoTrabajo' => 'required|exists:estados_trabajo,id',
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
                    'idEstado' => $request->input('estadoTrabajo'),
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
                
                $saldo = $totalGeneral - $request->aCuenta;
                
                // Debug: Log de datos calculados
                Log::info('🔍 Datos calculados en update para trabajo ' . $trabajo->id, [
                    'totalGeneral' => $totalGeneral,
                    'aCuenta' => $request->aCuenta,
                    'saldo' => $saldo
                ]);

                // Los campos de pago se manejan en la tabla pagos, no en trabajos

                // Determinar el estado del pago
                $estadoPago = $this->determinarEstadoPago($saldo, $request->aCuenta);

                // Actualizar o crear pago existente
                $pagoExistente = Pagos::where('idTrabajo', $trabajo->id)->latest('idPago')->first();
                
                if ($pagoExistente) {
                    // Actualizar pago existente
                    $pagoExistente->update([
                        'total' => $totalGeneral,
                        'aCuenta' => $request->aCuenta,
                        'saldo' => $saldo,
                    ]);
                    $pago = $pagoExistente;
                    Log::info('✅ Pago existente actualizado para trabajo ' . $trabajo->id);
                } else {
                    // Crear nuevo pago si no existe
                    $pago = Pagos::create([
                        'idTrabajo' => $trabajo->id,
                        'total' => $totalGeneral,
                        'aCuenta' => $request->aCuenta,
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
                        'tipoDocumento' => $servicioData['detalles']['tipoDocumento'] ?? null,
                        'tipoEvento' => $servicioData['detalles']['tipoEvento'] ?? null,
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
                'idEstadoPago' => $request->nuevoEstadoPago ?: $trabajo->idEstadoPago
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
}
