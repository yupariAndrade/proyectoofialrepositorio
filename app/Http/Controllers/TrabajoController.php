<?php

namespace App\Http\Controllers;

use App\Models\Trabajos;
use App\Models\Clientes;
use App\Models\Servicios;
use App\Models\Usuarios;
use App\Models\EstadosTrabajo;
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
        $trabajos = Trabajos::with(['cliente', 'servicio', 'usuario', 'estado'])->get();
        
        return Inertia::render('Trabajos/Index', [
            'trabajos' => $trabajos
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
            'idUsuario' => 'required|exists:usuarios,id',
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
            'idUsuario' => 'required|exists:usuarios,id',
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
