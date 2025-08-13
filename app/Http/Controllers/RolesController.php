<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Roles::orderBy('created_at', 'desc')->get();
        return Inertia::render('Roles/Index', ['roles' => $roles]);
    }

    public function create()
    {
        return Inertia::render('Roles/Create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:40|unique:roles,nombre',
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        Roles::create($request->only('nombre'));
        return redirect()->route('roles')->with('success', 'Rol creado');
    }

    public function show($id)
    {
        $rol = Roles::findOrFail($id);
        return Inertia::render('Roles/Show', ['rol' => $rol]);
    }

    public function edit($id)
    {
        $rol = Roles::findOrFail($id);
        return Inertia::render('Roles/Edit', ['rol' => $rol]);
    }

    public function update(Request $request, $id)
    {
        $rol = Roles::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:40|unique:roles,nombre,' . $rol->id,
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        $rol->update($request->only('nombre'));
        return redirect()->route('roles')->with('success', 'Rol actualizado');
    }

    public function destroy($id)
    {
        $rol = Roles::findOrFail($id);
        $rol->delete();
        return redirect()->route('roles')->with('success', 'Rol eliminado');
    }
}
