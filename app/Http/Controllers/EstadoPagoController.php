<?php

namespace App\Http\Controllers;

use App\Models\EstadoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class EstadoPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estados = EstadoPago::all();
        
        return Inertia::render('EstadoPagos/Index', [
            'estados' => $estados
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('EstadoPagos/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:40|unique:estados_pago,nombre',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        EstadoPago::create($request->only('nombre'));

        return redirect()->route('estado-pagos')->with('success', 'Estado de pago registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $estado = EstadoPago::findOrFail($id);
        
        return Inertia::render('EstadoPagos/Show', [
            'estado' => $estado
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $estado = EstadoPago::findOrFail($id);
        
        return Inertia::render('EstadoPagos/Edit', [
            'estado' => $estado
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $estado = EstadoPago::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:40|unique:estados_pago,nombre,' . $id,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $estado->update($request->only('nombre'));

        return redirect()->route('estado-pagos')->with('success', 'Estado de pago actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $estado = EstadoPago::findOrFail($id);
        $estado->delete();

        return redirect()->route('estado-pagos')->with('success', 'Estado de pago eliminado correctamente');
    }
}
