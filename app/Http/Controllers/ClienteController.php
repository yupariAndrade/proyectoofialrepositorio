<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Clientes::with('usuario')->get();
        return Inertia::render('Clientes/Index', ['clientes' => $clientes]);
    }

    public function create()
    {
        $usuarios = Usuarios::all();
        return Inertia::render('Clientes/Create', ['usuarios' => $usuarios]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apellido' => 'nullable|string|max:100',
            'ci' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'correoElectronico' => 'nullable|email|max:150',
            'idUsuario' => 'required|exists:usuarios,id'
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        Clientes::create($request->all());
        return redirect()->route('clientes')->with('success', 'Cliente creado exitosamente');
    }

    public function show(string $id)
    {
        $cliente = Clientes::with('usuario')->findOrFail($id);
        return Inertia::render('Clientes/Show', ['cliente' => $cliente]);
    }

    public function edit(string $id)
    {
        $cliente = Clientes::findOrFail($id);
        $usuarios = Usuarios::all();
        return Inertia::render('Clientes/Edit', ['cliente' => $cliente, 'usuarios' => $usuarios]);
    }

    public function update(Request $request, string $id)
    {
        $cliente = Clientes::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apellido' => 'nullable|string|max:100',
            'ci' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'correoElectronico' => 'nullable|email|max:150',
            'idUsuario' => 'required|exists:usuarios,id'
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        $cliente->update($request->all());
        return redirect()->route('clientes')->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(string $id)
    {
        $cliente = Clientes::findOrFail($id);
        $cliente->delete();
        return redirect()->route('clientes')->with('success', 'Cliente eliminado exitosamente');
    }
}
