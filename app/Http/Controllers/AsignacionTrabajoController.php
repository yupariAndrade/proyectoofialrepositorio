<?php

namespace App\Http\Controllers;

use App\Models\AsignacionesTrabajo;
use App\Models\Trabajos;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class AsignacionTrabajoController extends Controller
{
    public function index()
    {
        $asignaciones = AsignacionesTrabajo::with(['trabajo.cliente','trabajo.servicio','usuarioEncargado'])->orderBy('created_at','desc')->get();
        return Inertia::render('AsignacionesTrabajos/Index', ['asignaciones' => $asignaciones]);
    }

    public function create()
    {
        $trabajos = Trabajos::with(['cliente','servicio'])->get();
        $usuarios = Usuarios::orderBy('nombre')->get();
        return Inertia::render('AsignacionesTrabajos/Create', ['trabajos' => $trabajos, 'usuarios' => $usuarios]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idTrabajo' => 'required|exists:trabajos,id',
            'idUsuario' => 'required|exists:usuarios,id',
            'turno' => 'nullable|string|max:8',
            'fechaAsignacion' => 'required|date'
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        AsignacionesTrabajo::create($request->only(['idTrabajo','idUsuario','turno','fechaAsignacion']));
        return redirect()->route('asignaciones-trabajos')->with('success','Asignación creada');
    }

    public function show($id)
    {
        $asignacion = AsignacionesTrabajo::with(['trabajo.cliente','trabajo.servicio','usuarioEncargado'])->findOrFail($id);
        return Inertia::render('AsignacionesTrabajos/Show', ['asignacion' => $asignacion]);
    }

    public function edit($id)
    {
        $asignacion = AsignacionesTrabajo::findOrFail($id);
        $trabajos = Trabajos::with(['cliente','servicio'])->get();
        $usuarios = Usuarios::orderBy('nombre')->get();
        return Inertia::render('AsignacionesTrabajos/Edit', ['asignacion' => $asignacion, 'trabajos' => $trabajos, 'usuarios' => $usuarios]);
    }

    public function update(Request $request, $id)
    {
        $asignacion = AsignacionesTrabajo::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'idTrabajo' => 'required|exists:trabajos,id',
            'idUsuario' => 'required|exists:usuarios,id',
            'turno' => 'nullable|string|max:8',
            'fechaAsignacion' => 'required|date'
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $asignacion->update($request->only(['idTrabajo','idUsuario','turno','fechaAsignacion']));
        return redirect()->route('asignaciones-trabajos')->with('success','Asignación actualizada');
    }

    public function destroy($id)
    {
        $asignacion = AsignacionesTrabajo::findOrFail($id);
        $asignacion->delete();
        return redirect()->route('asignaciones-trabajos')->with('success','Asignación eliminada');
    }
}
