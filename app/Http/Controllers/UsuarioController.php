<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
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
        $roles = Roles::orderBy('nombre')->get();
        return Inertia::render('Usuarios/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100', // NO único (puede haber varios "Carlos")
            'apellidoPaterno' => 'nullable|string|max:100', // NO único (hermanos comparten apellido)
            'apellidoMaterno' => 'nullable|string|max:100', // NO único (hermanos comparten apellido)
            'ci' => 'nullable|string|max:20|unique:usuarios,ci', // ÚNICO (cada persona tiene CI único)
            'telefono' => 'nullable|string|max:20|unique:usuarios,telefono', // ÚNICO (cada persona tiene teléfono único)
            'direccion' => 'nullable|string|max:255', // NO único (muchas personas pueden vivir en el mismo lugar)
            'email' => 'nullable|email|max:150|unique:usuarios,email', // ÚNICO (cada persona tiene email único)
            'password' => 'nullable|string|min:6|max:255',
            'fechaIngreso' => 'nullable|date', // NO único (varios empleados pueden empezar el mismo día)
            'fechaFinal' => 'nullable|date', // Solo este campo acepta nulos
            'estado' => 'required|boolean',
            'idRol' => 'required|exists:roles,id'
        ]);

        $usuario = Usuarios::create($validated);

        return redirect()->back()->with('success', 'Usuario registrado exitosamente');
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
            'nombre' => 'required|string|max:100', // NO único (puede haber varios "Carlos")
            'apellidoPaterno' => 'nullable|string|max:100', // NO único (hermanos comparten apellido)
            'apellidoMaterno' => 'nullable|string|max:100', // NO único (hermanos comparten apellido)
            'ci' => 'nullable|string|max:20|unique:usuarios,ci,' . $id, // ÚNICO (cada persona tiene CI único)
            'telefono' => 'nullable|string|max:20|unique:usuarios,telefono,' . $id, // ÚNICO (cada persona tiene teléfono único)
            'direccion' => 'nullable|string|max:255', // NO único (muchas personas pueden vivir en el mismo lugar)
            'email' => 'nullable|email|max:150|unique:usuarios,email,' . $id, // ÚNICO (cada persona tiene email único)
            'password' => 'nullable|string|min:6|max:255',
            'fechaIngreso' => 'nullable|date', // NO único (varios empleados pueden empezar el mismo día)
            'fechaFinal' => 'nullable|date', // Solo este campo acepta nulos
            'estado' => 'required|boolean',
            'idRol' => 'required|exists:roles,id'
        ]);

        $usuario->update($validated);

        return redirect()->back()->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy($id)
    {
        $usuario = Usuarios::findOrFail($id);
        $usuario->delete();

        return redirect()->back()->with('success', 'Usuario eliminado exitosamente');
    }

    // Método para reporte PDF
    public function exportarPDF()
    {
        $usuarios = Usuarios::with('rol')->get();
        $pdf = Pdf::loadView('pdf.usuarios', compact('usuarios'));
        return $pdf->download('usuarios.pdf');
    }

    /**
     * Verificar si un campo específico ya existe
     */
    public function verificarCampo(Request $request)
    {
        try {
            $campo = $request->campo;
            $excludeId = $request->excludeId;
            
            // Validación especial para nombre completo
            if ($campo === 'nombre_completo') {
                $request->validate([
                    'nombre' => 'required|string',
                    'apellidoPaterno' => 'required|string',
                    'apellidoMaterno' => 'required|string',
                    'excludeId' => 'nullable|integer'
                ]);
                
                $query = Usuarios::where('nombre', $request->nombre)
                    ->where('apellidoPaterno', $request->apellidoPaterno)
                    ->where('apellidoMaterno', $request->apellidoMaterno);
                
                if ($excludeId) {
                    $query->where('id', '!=', $excludeId);
                }
                
                $existe = $query->exists();
                
                return response()->json([
                    'existe' => $existe,
                    'campo' => $campo,
                    'nombre' => $request->nombre,
                    'apellidoPaterno' => $request->apellidoPaterno,
                    'apellidoMaterno' => $request->apellidoMaterno,
                    'excludeId' => $excludeId
                ]);
            }
            
            // Validación básica para otros campos
            $request->validate([
                'campo' => 'required|string',
                'valor' => 'required|string',
                'excludeId' => 'nullable|integer'
            ]);
            
            $valor = $request->valor;
            
            // Solo validar campos que realmente deben ser únicos
            $camposUnicos = ['ci', 'telefono', 'email'];
            
            if (!in_array($campo, $camposUnicos)) {
                return response()->json([
                    'existe' => false,
                    'campo' => $campo,
                    'valor' => $valor,
                    'excludeId' => $excludeId
                ]);
            }
            
            // Crear query básica
            $query = Usuarios::where($campo, $valor);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            $existe = $query->exists();
            
            return response()->json([
                'existe' => $existe,
                'campo' => $campo,
                'valor' => $valor,
                'excludeId' => $excludeId
            ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error en verificarCampo:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
