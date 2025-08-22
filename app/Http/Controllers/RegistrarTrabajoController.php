<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            'servicio', 
            'detalleTrabajo', 
            'estado', 
            'pagos.estadoPago'
        ])->orderBy('fechaRegistro', 'desc')->get();
        
        // Forzar la serialización manual para asegurar que las relaciones se carguen
        $trabajosSerializados = $trabajos->map(function ($trabajo) {
            return [
                'id' => $trabajo->id,
                'slug' => $trabajo->slug, // Agregar el campo slug
                'idCliente' => $trabajo->idCliente,
                'idServicio' => $trabajo->idServicio,
                'idUsuario' => $trabajo->idUsuario,
                'fechaRegistro' => $trabajo->fechaRegistro,
                'fechaEntrega' => $trabajo->fechaEntrega,
                'idEstado' => $trabajo->idEstado,
                'cliente' => $trabajo->cliente,
                'servicio' => $trabajo->servicio,
                'estado' => $trabajo->estado,
                'detalleTrabajo' => $trabajo->detalleTrabajo,
                'pagos' => $trabajo->pagos->map(function ($pago) {
                    return [
                        'idPago' => $pago->idPago,
                        'idTrabajo' => $pago->idTrabajo,
                        'total' => $pago->total,
                        'aCuenta' => $pago->aCuenta,
                        'saldo' => $pago->saldo,
                        'idEstadoPago' => $pago->idEstadoPago,
                        'estadoPago' => $pago->estadoPago ? [
                            'id' => $pago->estadoPago->id,
                            'nombre' => $pago->estadoPago->nombre
                        ] : null
                    ];
                })
            ];
        });
        
        $clientes = Clientes::orderBy('nombre')->get(['id', 'nombre', 'apellido', 'telefono']);
        $servicios = Servicios::where('estado', true)->get(['id', 'nombreServicio', 'precioReferencial']);
        $estadosTrabajo = EstadosTrabajo::all();
        $estadosPago = EstadoPago::all();
        
        // Debug: Log de datos que se envían a Inertia
        $datosParaInertia = [
            'trabajos' => $trabajosSerializados,
            'clientes' => $clientes,
            'estadosTrabajo' => $estadosTrabajo,
            'estadosPago' => $estadosPago,
        ];
        
        Log::info('Datos que se envían a Inertia:', [
            'trabajos_count' => $trabajosSerializados->count(),
            'primer_trabajo_pagos' => $trabajosSerializados->first() ? $trabajosSerializados->first()['pagos'] : 'NO HAY TRABAJOS',
            'primer_pago_estado' => $trabajosSerializados->first() && $trabajosSerializados->first()['pagos']->first() ? 
                $trabajosSerializados->first()['pagos']->first()['estadoPago'] : 'NO HAY ESTADO'
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
        
        // Si viene un cliente_id, obtener la información del cliente
        $clientePreSeleccionado = null;
        if ($request->has('cliente_id')) {
            $clientePreSeleccionado = Clientes::find($request->cliente_id);
        }
        
        return Inertia::render('RegistrarTrabajos/Create', [
            'clientes' => $clientes,
            'servicios' => $servicios,
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
        
        // Validar datos del formulario
        $validator = Validator::make($request->all(), [
            'cliente' => 'required|exists:clientes,id',
            'servicio' => 'required|exists:servicios,id',
            'detalles.cantidad' => 'required|integer|min:1',
            'fechaEntrega' => 'required|date',
            'estadoTrabajo' => 'required|exists:estados_trabajo,id',
            'aCuenta' => 'required|numeric|min:0', // Cambiar a 'required' para aceptar 0
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
                    'idServicio' => $request->servicio,
                    'idUsuario' => 1, // Usuario por defecto
                    'fechaRegistro' => now(),
                    'fechaEntrega' => $request->fechaEntrega,
                    'idEstado' => $request->estadoTrabajo,
                ]);
                Log::info('Trabajo creado con ID: ' . $trabajo->getKey());

                // 2. Crear el detalle del trabajo
                Log::info('Creando detalle del trabajo...');
                Log::info('Datos del detalle:', $request->detalles);
                
                $detalle = DetalleTrabajo::create([
                    'idTrabajo' => $trabajo->getKey(),
                    'descripcion' => $request->detalles['descripcion'] ?? null,
                    'tamano' => $request->detalles['tamano'] ?? null,
                    'color' => $request->detalles['color'] ?? null,
                    'modelo' => $request->detalles['modelo'] ?? null,
                    'cantidad' => $request->detalles['cantidad'],
                    'tipoDocumento' => $request->detalles['tipoDocumento'] ?? null,
                    'tipoEvento' => $request->detalles['tipoEvento'] ?? null,
                ]);
                Log::info('Detalle del trabajo creado con ID: ' . $detalle->getKey());

                // 3. Calcular el pago automáticamente
                Log::info('Calculando pago...');
                $servicio = Servicios::find($request->servicio);
                Log::info('Servicio encontrado:', $servicio ? $servicio->toArray() : 'NO ENCONTRADO');
                
                $total = $servicio->precioReferencial * $request->detalles['cantidad'];
                $saldo = $total - $request->aCuenta;
                Log::info("Total: $total, A Cuenta: {$request->aCuenta}, Saldo: $saldo");

                // 4. Determinar el estado del pago
                $estadoPago = $this->determinarEstadoPago($saldo, $request->aCuenta);
                Log::info('Estado del pago determinado: ' . $estadoPago);
                Log::info('Estado del pago del formulario: ' . $request->input('idEstadoPago'));

                // 5. Crear el pago
                Log::info('Creando pago...');
                $pago = Pagos::create([
                    'idTrabajo' => $trabajo->getKey(),
                    'total' => $total,
                    'aCuenta' => $request->aCuenta,
                    'saldo' => $saldo,
                    'idEstadoPago' => $request->input('idEstadoPago') ?: $estadoPago, // Usar el del formulario o el calculado
                ]);
                Log::info('Pago creado con ID: ' . $pago->getKey() . ' y estado: ' . $pago->idEstadoPago);
                
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
            'servicio', 
            'pagos.estadoPago',
            'estado'
        ])->where('slug', $slug)->firstOrFail();

        // Cargar el detalle del trabajo de manera explícita
        $detalleTrabajo = DetalleTrabajo::where('idTrabajo', $trabajo->id)->first();
        
        // Cargar el estado del pago de manera explícita
        $pagos = $trabajo->pagos;
        $pagosData = [];
        if ($pagos && $pagos->count() > 0) {
            foreach ($pagos as $pago) {
                $pagoArray = $pago->toArray();
                if ($pago->idEstadoPago) {
                    $estadoPago = EstadoPago::find($pago->idEstadoPago);
                    $pagoArray['estadoPago'] = $estadoPago ? $estadoPago->toArray() : null;
                }
                $pagosData[] = $pagoArray;
            }
        }
        
        // Crear un array con todos los datos del trabajo
        $trabajoData = $trabajo->toArray();
        $trabajoData['detalleTrabajo'] = $detalleTrabajo ? $detalleTrabajo->toArray() : null;
        $trabajoData['pagos'] = $pagosData;

        // Debug: Log de datos que se envían a Inertia
        Log::info('Datos del trabajo para Show:', [
            'trabajo_id' => $trabajo->id,
            'cliente' => $trabajo->cliente ? $trabajo->cliente->toArray() : 'NO HAY CLIENTE',
            'servicio' => $trabajo->servicio ? $trabajo->servicio->toArray() : 'NO HAY SERVICIO',
            'detalleTrabajo' => $detalleTrabajo ? $detalleTrabajo->toArray() : 'NO HAY DETALLE',
            'pagos' => $pagosData,
            'estado' => $trabajo->estado ? $trabajo->estado->toArray() : 'NO HAY ESTADO'
        ]);

        // Obtener otros trabajos del mismo cliente
        $otrosTrabajosCliente = Trabajos::with(['servicio', 'detalleTrabajo'])
            ->where('idCliente', $trabajo->idCliente)
            ->where('id', '!=', $trabajo->id)
            ->get();

        return Inertia::render('RegistrarTrabajos/Show', [
            'trabajo' => $trabajoData,
            'otrosTrabajosCliente' => $otrosTrabajosCliente,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $trabajo = Trabajos::with([
            'cliente',
            'servicio',
            'pagos.estadoPago',
            'estado'
        ])->where('slug', $slug)->firstOrFail();

        // Cargar el detalle del trabajo de manera explícita
        $detalleTrabajo = DetalleTrabajo::where('idTrabajo', $trabajo->id)->first();
        
        // Crear un array con todos los datos del trabajo
        $trabajoData = $trabajo->toArray();
        $trabajoData['detalleTrabajo'] = $detalleTrabajo ? $detalleTrabajo->toArray() : null;

        // Debug: Log de datos que se envían a Inertia
        Log::info('Datos del trabajo para Edit:', [
            'trabajo_id' => $trabajo->id,
            'cliente' => $trabajo->cliente ? $trabajo->cliente->toArray() : 'NO HAY CLIENTE',
            'servicio' => $trabajo->servicio ? $trabajo->servicio->toArray() : 'NO HAY SERVICIO',
            'detalleTrabajo' => $detalleTrabajo ? $detalleTrabajo->toArray() : 'NO HAY DETALLE',
            'pagos' => $trabajo->pagos ? $trabajo->pagos->toArray() : 'NO HAY PAGOS',
            'estado' => $trabajo->estado ? $trabajo->estado->toArray() : 'NO HAY ESTADO'
        ]);

        $clientes = Clientes::all();
        $servicios = Servicios::where('estado', true)->get();
        $estadosTrabajo = EstadosTrabajo::all();
        $estadosPago = EstadoPago::all();

        return Inertia::render('RegistrarTrabajos/Edit', [
            'trabajo' => $trabajoData,
            'clientes' => $clientes,
            'servicios' => $servicios,
            'estadosTrabajo' => $estadosTrabajo,
            'estadosPago' => $estadosPago,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $trabajo = Trabajos::where('slug', $slug)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'cliente' => 'required|exists:clientes,id',
            'servicio' => 'required|exists:servicios,id',
            'fechaEntrega' => 'required|date',
            'estadoTrabajo' => 'required|exists:estados_trabajo,id',
            'detalles.tamano' => 'nullable|string|max:255',
            'detalles.color' => 'nullable|string|max:255',
            'detalles.modelo' => 'nullable|string|max:255',
            'detalles.cantidad' => 'required|integer|min:1',
            'detalles.tipoDocumento' => 'nullable|string|max:255',
            'detalles.tipoEvento' => 'nullable|string|max:255',
            'detalles.descripcion' => 'nullable|string|max:255',
            'aCuenta' => 'required|numeric|min:0',
            'idEstadoPago' => 'required|exists:estados_pago,id',
            'comentarioCambios' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $trabajo) {
                // Actualizar trabajo
                $trabajo->update([
                    'idCliente' => $request->input('cliente'),
                    'idServicio' => $request->input('servicio'),
                    'fechaEntrega' => $request->input('fechaEntrega'),
                    'idEstado' => $request->input('estadoTrabajo'),
                ]);

                // Actualizar o crear detalle del trabajo
                if ($trabajo->detalleTrabajo) {
                    $trabajo->detalleTrabajo->update([
                        'tamano' => $request->input('detalles.tamano'),
                        'color' => $request->input('detalles.color'),
                        'modelo' => $request->input('detalles.modelo'),
                        'cantidad' => $request->input('detalles.cantidad'),
                        'tipoDocumento' => $request->input('detalles.tipoDocumento'),
                        'tipoEvento' => $request->input('detalles.tipoEvento'),
                        'descripcion' => $request->input('detalles.descripcion'),
                    ]);
                } else {
                    DetalleTrabajo::create([
                        'idTrabajo' => $trabajo->id,
                        'tamano' => $request->input('detalles.tamano'),
                        'color' => $request->input('detalles.color'),
                        'modelo' => $request->input('detalles.modelo'),
                        'cantidad' => $request->input('detalles.cantidad'),
                        'tipoDocumento' => $request->input('detalles.tipoDocumento'),
                        'tipoEvento' => $request->input('detalles.tipoEvento'),
                        'descripcion' => $request->input('detalles.descripcion'),
                    ]);
                }

                // Calcular total y saldo
                $servicio = Servicios::find($request->input('servicio'));
                $cantidad = $request->input('detalles.cantidad');
                $total = $servicio->precioReferencial * $cantidad;
                $aCuenta = $request->input('aCuenta');
                $saldo = $total - $aCuenta;

                // Actualizar o crear pago
                if ($trabajo->pagos->count() > 0) {
                    $pago = $trabajo->pagos->first();
                    $pago->update([
                        'total' => $total,
                        'aCuenta' => $aCuenta,
                        'saldo' => $saldo,
                        'idEstadoPago' => $request->input('idEstadoPago') ?: $this->determinarEstadoPago($aCuenta, $saldo),
                    ]);
                } else {
                    Pagos::create([
                        'idTrabajo' => $trabajo->id,
                        'total' => $total,
                        'aCuenta' => $aCuenta,
                        'saldo' => $saldo,
                        'idEstadoPago' => $request->input('idEstadoPago') ?: $this->determinarEstadoPago($aCuenta, $saldo),
                    ]);
                }

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
}
