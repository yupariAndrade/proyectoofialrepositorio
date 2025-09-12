<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Ya no necesitamos pasar usuarios al frontend
        return Inertia::render('Clientes/Create');
    }

    public function store(Request $request)
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();
        
        // Validar usando las reglas del modelo
        $validated = $request->validate(Clientes::rules());
        
        // Verificar duplicados manualmente para mensajes más específicos
        if (Clientes::existeDuplicado($validated['nombre'], $validated['apellido'], $validated['ci'])) {
            return redirect()->back()->withInput()->withErrors([
                'nombre' => 'Ya existe un cliente con este nombre',
                'apellido' => 'Ya existe un cliente con este apellido',
                'ci' => 'Ya existe un cliente con este CI'
            ]);
        }
        
        // Asignar automáticamente el usuario autenticado
        $validated['idUsuario'] = $usuario->id;
        
        Clientes::create($validated);
        return redirect()->back()->with('success', 'Cliente registrado exitosamente');
    }

    public function show(string $id)
    {
        $cliente = Clientes::with('usuario')->findOrFail($id);
        return Inertia::render('Clientes/Show', ['cliente' => $cliente]);
    }

    public function edit(string $id)
    {
        $cliente = Clientes::findOrFail($id);
        // Ya no necesitamos pasar usuarios al frontend
        return Inertia::render('Clientes/Edit', ['cliente' => $cliente]);
    }

    public function update(Request $request, string $id)
    {
        $cliente = Clientes::findOrFail($id);
        
        // Validar usando las reglas del modelo con exclusión del ID actual
        $validated = $request->validate(Clientes::rules($id));
        
        // Verificar duplicados manualmente para mensajes más específicos
        if (Clientes::existeDuplicado($validated['nombre'], $validated['apellido'], $validated['ci'], $id)) {
            return redirect()->back()->withInput()->withErrors([
                'nombre' => 'Ya existe un cliente con este nombre',
                'apellido' => 'Ya existe un cliente con este apellido',
                'ci' => 'Ya existe un cliente con este CI'
            ]);
        }
        
        // Mantener el usuario original que creó el cliente
        $validated['idUsuario'] = $cliente->idUsuario;
        
        $cliente->update($validated);
        return redirect()->back()->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(string $id)
    {
        $cliente = Clientes::findOrFail($id);
        $cliente->delete();
        return redirect()->route('clientes')->with('success', 'Cliente eliminado exitosamente');
    }
}
