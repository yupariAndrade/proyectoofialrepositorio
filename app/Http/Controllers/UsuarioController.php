<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Usuarios/Index', [
            'usuarios' => Usuarios::with('rol')->orderBy('created_at', 'desc')->get(),
            'roles' => Roles::orderBy('nombre')->get(),
        ]);
    }

    /** Mostrar formulario de creación */
    public function create()
    {
        return Inertia::render('Usuarios/Create', [
            'roles' => Roles::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidoPaterno' => 'nullable|string|max:100',
            'apellidoMaterno' => 'nullable|string|max:100',
            'ci' => 'nullable|string|max:20|unique:usuarios,ci',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:150|unique:usuarios,email',
            'fechaIngreso' => 'nullable|date',
            'fechaFinal' => 'nullable|date',
            'estado' => 'required|boolean',
            'idRol' => 'required|exists:roles,id'
        ]);

        Usuarios::create($validated);

        return redirect()->route('usuarios');
    }

    /** Mostrar detalles */
    public function show($id)
    {
        $usuario = Usuarios::with('rol')->findOrFail($id);
        return Inertia::render('Usuarios/Show', ['usuario' => $usuario]);
    }

    /** Mostrar formulario de edición */
    public function edit($id)
    {
        $usuario = Usuarios::findOrFail($id);
        $roles = Roles::orderBy('nombre')->get();
        return Inertia::render('Usuarios/Edit', [
            'usuario' => $usuario,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuarios::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidoPaterno' => 'nullable|string|max:100',
            'apellidoMaterno' => 'nullable|string|max:100',
            'ci' => 'nullable|string|max:20|unique:usuarios,ci,' . $id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:150|unique:usuarios,email,' . $id,
            'fechaIngreso' => 'nullable|date',
            'fechaFinal' => 'nullable|date',
            'estado' => 'required|boolean',
            'idRol' => 'required|exists:roles,id'
        ]);

        $usuario->update($validated);

        return redirect()->route('usuarios');
    }

    public function destroy($id)
    {
        $usuario = Usuarios::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios')->with('success', 'Usuario eliminado');
    }

    // Método para reporte PDF
    public function exportarPDF()
    {
        $usuarios = Usuarios::with('rol')->get();
        $pdf = Pdf::loadView('pdf.usuarios', compact('usuarios'));
        return $pdf->download('usuarios.pdf');
    }
}
