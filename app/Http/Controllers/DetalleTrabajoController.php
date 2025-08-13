<?php

namespace App\Http\Controllers;

use App\Models\DetalleTrabajo;
use App\Models\Trabajos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class DetalleTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detalles = DetalleTrabajo::with(['trabajo.servicio', 'trabajo.cliente'])->orderBy('created_at', 'desc')->get();
        
        return Inertia::render('DetalleTrabajo/Index', [
            'detalles' => $detalles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trabajos = Trabajos::with(['cliente', 'servicio'])->orderBy('created_at', 'desc')->get(['id','idCliente','idServicio','fechaRegistro']);
        
        return Inertia::render('DetalleTrabajo/Create', [
            'trabajos' => $trabajos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idTrabajo' => 'required|exists:trabajos,id',
            'descripcion' => 'nullable|string|max:255',
            'tamano' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'modelo' => 'nullable|string|max:50',
            'cantidad' => 'required|integer|min:1',
            'tipoDocumento' => 'nullable|string|max:100',
            'tipoEvento' => 'nullable|string|max:100',
            'otros' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DetalleTrabajo::create($request->all());

        return redirect()->route('detalle-trabajo')->with('success', 'Detalle de trabajo registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $detalle = DetalleTrabajo::with(['trabajo.servicio', 'trabajo.cliente'])->findOrFail($id);
        
        return Inertia::render('DetalleTrabajo/Show', [
            'detalleTrabajo' => $detalle
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detalle = DetalleTrabajo::findOrFail($id);
        $trabajos = Trabajos::with(['cliente', 'servicio'])->orderBy('created_at', 'desc')->get(['id','idCliente','idServicio','fechaRegistro']);
        
        return Inertia::render('DetalleTrabajo/Edit', [
            'detalleTrabajo' => $detalle,
            'trabajos' => $trabajos
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $detalle = DetalleTrabajo::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'idTrabajo' => 'required|exists:trabajos,id',
            'descripcion' => 'nullable|string|max:255',
            'tamano' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'modelo' => 'nullable|string|max:50',
            'cantidad' => 'required|integer|min:1',
            'tipoDocumento' => 'nullable|string|max:100',
            'tipoEvento' => 'nullable|string|max:100',
            'otros' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $detalle->update($request->all());

        return redirect()->route('detalle-trabajo')->with('success', 'Detalle de trabajo actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $detalle = DetalleTrabajo::findOrFail($id);
        $detalle->delete();

        return redirect()->route('detalle-trabajo')->with('success', 'Detalle de trabajo eliminado correctamente');
    }

    /**
     * Obtener detalles por trabajo
     */
    public function porTrabajo($trabajoId)
    {
        $detalles = DetalleTrabajo::with(['trabajo.servicio', 'trabajo.cliente'])
            ->where('idTrabajo', $trabajoId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return Inertia::render('DetalleTrabajo/Index', [
            'detalles' => $detalles,
            'filtroTrabajo' => $trabajoId
        ]);
    }
}
