<?php

namespace App\Http\Controllers;

use App\Models\EstadosTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class EstadoTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estados = EstadosTrabajo::all();
        
        return Inertia::render('EstadosTrabajo/Index', [
            'estados' => $estados
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('EstadosTrabajo/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:40|unique:estados_trabajo,nombre',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        EstadosTrabajo::create($request->only('nombre'));

        return redirect()->route('estados-trabajo.index')->with('success', 'Estado de trabajo creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $estado = EstadosTrabajo::findOrFail($id);
        
        return Inertia::render('EstadosTrabajo/Show', [
            'estado' => $estado
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $estado = EstadosTrabajo::findOrFail($id);
        
        return Inertia::render('EstadosTrabajo/Edit', [
            'estado' => $estado
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $estado = EstadosTrabajo::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:40|unique:estados_trabajo,nombre,' . $id,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $estado->update($request->only('nombre'));

        return redirect()->route('estados-trabajo.index')->with('success', 'Estado de trabajo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $estado = EstadosTrabajo::findOrFail($id);
        $estado->delete();

        return redirect()->route('estados-trabajo.index')->with('success', 'Estado de trabajo eliminado exitosamente');
    }
}
