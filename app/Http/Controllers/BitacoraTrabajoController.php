<?php

namespace App\Http\Controllers;

use App\Models\BitacoraTrabajo;
use App\Models\Trabajos;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class BitacoraTrabajoController extends Controller
{
    public function index()
    {
        $bitacoras = BitacoraTrabajo::with(['trabajo.cliente', 'trabajo.servicio', 'usuario'])->orderBy('fecha', 'desc')->get();
        return Inertia::render('BitacoraTrabajos/Index', [ 'bitacoras' => $bitacoras ]);
    }

    public function create()
    {
        $trabajos = Trabajos::with(['cliente', 'servicio'])->orderBy('created_at', 'desc')->get();
        $usuarios = Usuarios::orderBy('nombre')->get();
        return Inertia::render('BitacoraTrabajos/Create', [ 'trabajos' => $trabajos, 'usuarios' => $usuarios ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idTrabajo' => 'required|exists:trabajos,id',
            'idUsuario' => 'required|exists:usuarios,id',
            'accion' => 'required|string|max:50',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string'
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        BitacoraTrabajo::create($request->only(['idTrabajo','idUsuario','accion','fecha','descripcion']));
        return redirect()->route('bitacora-trabajos')->with('success','Registro creado');
    }

    public function show($id)
    {
        $bitacora = BitacoraTrabajo::with(['trabajo.cliente','trabajo.servicio','usuario'])->findOrFail($id);
        return Inertia::render('BitacoraTrabajos/Show', [ 'bitacora' => $bitacora ]);
    }

    public function edit($id)
    {
        $bitacora = BitacoraTrabajo::findOrFail($id);
        $trabajos = Trabajos::with(['cliente','servicio'])->get();
        $usuarios = Usuarios::orderBy('nombre')->get();
        return Inertia::render('BitacoraTrabajos/Edit', [ 'bitacora' => $bitacora, 'trabajos' => $trabajos, 'usuarios' => $usuarios ]);
    }

    public function update(Request $request, $id)
    {
        $bitacora = BitacoraTrabajo::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'idTrabajo' => 'required|exists:trabajos,id',
            'idUsuario' => 'required|exists:usuarios,id',
            'accion' => 'required|string|max:50',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string'
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $bitacora->update($request->only(['idTrabajo','idUsuario','accion','fecha','descripcion']));
        return redirect()->route('bitacora-trabajos')->with('success','Registro actualizado');
    }

    public function destroy($id)
    {
        $bitacora = BitacoraTrabajo::findOrFail($id);
        $bitacora->delete();
        return redirect()->route('bitacora-trabajos')->with('success','Registro eliminado');
    }
}
