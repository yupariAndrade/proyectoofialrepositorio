<?php

namespace App\Http\Controllers;

use App\Models\Trabajos;
use App\Models\Clientes;
use App\Models\Servicios;
use App\Models\Usuarios;
use App\Models\EstadosTrabajo;
use App\Models\EstadoPago;
use App\Models\DetalleTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cargar trabajos con todas las relaciones necesarias
        $trabajos = Trabajos::with([
            'cliente', 
            'estado', 
            'estadoPago',
            'detallesTrabajo.servicio',
            'detallesTrabajo.pago'
        ])->get();

        // Transformar los datos para el frontend
        $trabajosTransformados = $trabajos->map(function ($trabajo) {
            // Calcular total del trabajo sumando todos los servicios
            $totalTrabajo = $trabajo->detallesTrabajo->sum(function ($detalle) {
                $precio = $detalle->servicio->precioReferencial ?? 0;
                $cantidad = $detalle->cantidad ?? 1;
                return $precio * $cantidad;
            });

            // Calcular total pagado
            $totalPagado = $trabajo->detallesTrabajo->sum(function ($detalle) {
                return $detalle->pago ? ($detalle->pago->aCuenta ?? 0) : 0;
            });

            // Calcular saldo
            $saldo = $totalTrabajo - $totalPagado;

            // Transformar servicios para el frontend
            $servicios = $trabajo->detallesTrabajo->map(function ($detalle) {
                return [
                    'id' => $detalle->id,
                    'nombreServicio' => $detalle->servicio->nombreServicio ?? 'Servicio no especificado',
                    'precio' => $detalle->servicio->precioReferencial ?? 0,
                    'cantidad' => $detalle->cantidad ?? 1,
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
                'idEstadoPago' => $trabajo->idEstadoPago,
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
                'servicios' => $servicios,
                'totalTrabajo' => $totalTrabajo,
                'saldo' => $saldo,
                'pagado' => $totalPagado,
            ];
        });

        // Cargar datos para los filtros
        $clientes = Clientes::select('id', 'nombre', 'apellido')->get();
        $estadosTrabajo = EstadosTrabajo::select('id', 'nombre')->get();
        $estadosPago = EstadoPago::select('id', 'nombre')->get();

        return Inertia::render('RegistrarTrabajos/Index', [
            'trabajos' => $trabajosTransformados,
            'clientes' => $clientes,
            'estadosTrabajo' => $estadosTrabajo,
            'estadosPago' => $estadosPago,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Clientes::all();
        $servicios = Servicios::all();
        $usuarios = Usuarios::all();
        $estados = EstadosTrabajo::all();
        
        return Inertia::render('Trabajos/Create', [
            'clientes' => $clientes,
            'servicios' => $servicios,
            'usuarios' => $usuarios,
            'estados' => $estados
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idCliente' => 'required|exists:clientes,id',
            'idServicio' => 'required|exists:servicios,id',
            'idResponsable' => 'nullable|exists:usuarios,id',
            'fechaRegistro' => 'required|date',
            'fechaEntrega' => 'nullable|date|after_or_equal:fechaRegistro',
            'idEstado' => 'required|exists:estados_trabajo,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Trabajos::create($request->all());

        return redirect()->route('trabajos')->with('success', 'Trabajo creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trabajo = Trabajos::with(['cliente', 'servicio', 'usuario', 'estado'])->findOrFail($id);
        
        return Inertia::render('Trabajos/Show', [
            'trabajo' => $trabajo
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $trabajo = Trabajos::findOrFail($id);
        $clientes = Clientes::all();
        $servicios = Servicios::all();
        $usuarios = Usuarios::all();
        $estados = EstadosTrabajo::all();
        
        return Inertia::render('Trabajos/Edit', [
            'trabajo' => $trabajo,
            'clientes' => $clientes,
            'servicios' => $servicios,
            'usuarios' => $usuarios,
            'estados' => $estados
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $trabajo = Trabajos::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'idCliente' => 'required|exists:clientes,id',
            'idServicio' => 'required|exists:servicios,id',
            'idResponsable' => 'nullable|exists:usuarios,id',
            'fechaRegistro' => 'required|date',
            'fechaEntrega' => 'nullable|date|after_or_equal:fechaRegistro',
            'idEstado' => 'required|exists:estados_trabajo,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $trabajo->update($request->all());

        return redirect()->route('trabajos')->with('success', 'Trabajo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $trabajo = Trabajos::findOrFail($id);
        $trabajo->delete();

        return redirect()->route('trabajos')->with('success', 'Trabajo eliminado exitosamente');
    }

    /**
     * Obtener trabajos por estado
     */
    public function porEstado($estadoId)
    {
        $trabajos = Trabajos::with(['cliente', 'servicio', 'usuario', 'estado'])
            ->where('idEstado', $estadoId)
            ->get();
            
        return Inertia::render('Trabajos/Index', [
            'trabajos' => $trabajos,
            'filtroEstado' => $estadoId
        ]);
    }

    /**
     * Obtener trabajos por cliente
     */
    public function porCliente($clienteId)
    {
        $trabajos = Trabajos::with(['cliente', 'servicio', 'usuario', 'estado'])
            ->where('idCliente', $clienteId)
            ->get();
            
        return Inertia::render('Trabajos/Index', [
            'trabajos' => $trabajos,
            'filtroCliente' => $clienteId
        ]);
    }
}
