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
            // Log básico para empezar
            \Illuminate\Support\Facades\Log::info('=== INICIO verificarCampo ===');
            \Illuminate\Support\Facades\Log::info('Request recibido:', $request->all());
            
            // Validación básica
            $request->validate([
                'campo' => 'required|string',
                'valor' => 'required|string',
                'excludeId' => 'nullable|integer'
            ]);
            
            \Illuminate\Support\Facades\Log::info('Validación pasó correctamente');
            
            $campo = $request->campo;
            $valor = $request->valor;
            $excludeId = $request->excludeId;
            
            \Illuminate\Support\Facades\Log::info('Parámetros extraídos:', compact('campo', 'valor', 'excludeId'));
            
            // Solo validar campos que realmente deben ser únicos
            $camposUnicos = ['ci', 'telefono', 'email'];
            
            if (!in_array($campo, $camposUnicos)) {
                // Si no es un campo único, siempre retornar que NO existe duplicado
                \Illuminate\Support\Facades\Log::info('Campo no es único, permitiendo duplicado:', ['campo' => $campo]);
                return response()->json([
                    'existe' => false,
                    'campo' => $campo,
                    'valor' => $valor,
                    'excludeId' => $excludeId
                ]);
            }
            
            \Illuminate\Support\Facades\Log::info('Campo validado correctamente');
            
            // Crear query básica
            \Illuminate\Support\Facades\Log::info('Creando query...');
            $query = Usuarios::where($campo, $valor);
            \Illuminate\Support\Facades\Log::info('Query creada exitosamente');
            
            if ($excludeId) {
                \Illuminate\Support\Facades\Log::info('Agregando excludeId:', ['excludeId' => $excludeId]);
                $query->where('id', '!=', $excludeId);
            }
            
            \Illuminate\Support\Facades\Log::info('Ejecutando exists()...');
            $existe = $query->exists();
            \Illuminate\Support\Facades\Log::info('Exists ejecutado:', ['existe' => $existe]);
            
            \Illuminate\Support\Facades\Log::info('=== FIN verificarCampo ===');
            
            return response()->json([
                'existe' => $existe,
                'campo' => $campo,
                'valor' => $valor,
                'excludeId' => $excludeId
            ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('=== ERROR en verificarCampo ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar si existe un usuario duplicado por nombre, CI y apellido paterno
     */
    public function verificarDuplicados(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string',
                'ci' => 'nullable|string',
                'apellidoPaterno' => 'nullable|string',
                'id' => 'nullable|integer' // Para excluir el usuario actual en edición
            ]);

            $nombre = trim($request->nombre);
            $ci = trim($request->ci);
            $apellidoPaterno = trim($request->apellidoPaterno);
            $idExcluir = $request->id;

            // Construir la consulta
            $query = Usuarios::query();

            // Condiciones para considerar duplicado
            $conditions = [];

            // 1. Mismo nombre + mismo CI (si CI no está vacío)
            if (!empty($ci)) {
                $conditions[] = function($q) use ($nombre, $ci) {
                    $q->where('nombre', $nombre)
                      ->where('ci', $ci);
                };
            }

            // 2. Mismo nombre + mismo apellido paterno (si apellido paterno no está vacío)
            if (!empty($apellidoPaterno)) {
                $conditions[] = function($q) use ($nombre, $apellidoPaterno) {
                    $q->where('nombre', $nombre)
                      ->where('apellidoPaterno', $apellidoPaterno);
                };
            }

            // 3. Mismo CI + mismo apellido paterno (si ambos no están vacíos)
            if (!empty($ci) && !empty($apellidoPaterno)) {
                $conditions[] = function($q) use ($ci, $apellidoPaterno) {
                    $q->where('ci', $ci)
                      ->where('apellidoPaterno', $apellidoPaterno);
                };
            }

            // Si hay condiciones, aplicar OR
            if (!empty($conditions)) {
                $query->where(function($q) use ($conditions) {
                    foreach ($conditions as $condition) {
                        $q->orWhere($condition);
                    }
                });
            }

            // Excluir el usuario actual si se está editando
            if ($idExcluir) {
                $query->where('id', '!=', $idExcluir);
            }

            $duplicados = $query->get();

            if ($duplicados->count() > 0) {
                $mensajes = [];
                foreach ($duplicados as $duplicado) {
                    $motivos = [];
                    if (!empty($ci) && $duplicado->ci === $ci) {
                        $motivos[] = 'CI';
                    }
                    if (!empty($apellidoPaterno) && $duplicado->apellidoPaterno === $apellidoPaterno) {
                        $motivos[] = 'apellido paterno';
                    }
                    if ($duplicado->nombre === $nombre) {
                        $motivos[] = 'nombre';
                    }
                    
                    $mensajes[] = "Usuario '{$duplicado->nombre} {$duplicado->apellidoPaterno}' (ID: {$duplicado->id}) - Coincide en: " . implode(', ', $motivos);
                }

                return response()->json([
                    'existe' => true,
                    'duplicados' => $duplicados,
                    'mensaje' => 'Se encontraron usuarios similares: ' . implode('; ', $mensajes),
                    'motivos' => $mensajes
                ]);
            }

            return response()->json([
                'existe' => false,
                'mensaje' => 'No se encontraron usuarios duplicados'
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('=== ERROR en verificarDuplicados ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
