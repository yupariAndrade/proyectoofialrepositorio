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
        if (Clientes::existeDuplicado($validated['ci'], $validated['telefono'], $validated['correoElectronico'])) {
            return redirect()->back()->withInput()->withErrors([
                'ci' => 'Ya existe un cliente con este CI',
                'telefono' => 'Ya existe un cliente con este teléfono',
                'correoElectronico' => 'Ya existe un cliente con este correo electrónico'
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
        if (Clientes::existeDuplicado($validated['ci'], $validated['telefono'], $validated['correoElectronico'], $id)) {
            return redirect()->back()->withInput()->withErrors([
                'ci' => 'Ya existe un cliente con este CI',
                'telefono' => 'Ya existe un cliente con este teléfono',
                'correoElectronico' => 'Ya existe un cliente con este correo electrónico'
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

    /**
     * Verificar si un campo específico ya existe en otro cliente
     */
    public function verificarCampo(Request $request)
    {
        try {
            $request->validate([
                'campo' => 'required|string|in:nombre,ci',
                'valor' => 'required|string',
                'id' => 'nullable|integer' // Para excluir el cliente actual en edición
            ]);

            $campo = $request->campo;
            $valor = trim($request->valor);
            $idExcluir = $request->id;

            if (empty($valor)) {
                return response()->json([
                    'existe' => false,
                    'mensaje' => 'Campo vacío'
                ]);
            }

            $query = Clientes::query();
            
            // Buscar por el campo específico
            $query->where($campo, $valor);
            
            // Excluir el cliente actual si se está editando
            if ($idExcluir) {
                $query->where('id', '!=', $idExcluir);
            }

            $existe = $query->exists();

            if ($existe) {
                return response()->json([
                    'existe' => true,
                    'mensaje' => "Ya existe un cliente con este {$campo}"
                ]);
            }

            return response()->json([
                'existe' => false,
                'mensaje' => "{$campo} disponible"
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('=== ERROR en verificarCampo Cliente ===', [
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
     * Verificar duplicados por nombre y CI
     */
    public function verificarDuplicados(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string',
                'ci' => 'nullable|string',
                'id' => 'nullable|integer' // Para excluir el cliente actual en edición
            ]);

            $nombre = trim($request->nombre);
            $ci = trim($request->ci);
            $idExcluir = $request->id;

            $query = Clientes::query();
            $conditions = [];

            // Verificar combinaciones: nombre + CI
            if (!empty($ci)) {
                $conditions[] = function($q) use ($nombre, $ci) {
                    $q->where('nombre', $nombre)->where('ci', $ci);
                };
            }

            // Verificar solo nombre (si hay otros con el mismo nombre)
            $conditions[] = function($q) use ($nombre) {
                $q->where('nombre', $nombre);
            };

            if (!empty($conditions)) {
                $query->where(function($q) use ($conditions) {
                    foreach ($conditions as $condition) {
                        $q->orWhere($condition);
                    }
                });
            }

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
                    if ($duplicado->nombre === $nombre) {
                        $motivos[] = 'nombre';
                    }
                    $mensajes[] = "Cliente '{$duplicado->nombre}' (ID: {$duplicado->id}) - Coincide en: " . implode(', ', $motivos);
                }

                return response()->json([
                    'existe' => true,
                    'duplicados' => $duplicados,
                    'mensaje' => 'Se encontraron clientes similares: ' . implode('; ', $mensajes),
                    'motivos' => $mensajes
                ]);
            }

            return response()->json([
                'existe' => false,
                'mensaje' => 'No se encontraron clientes duplicados'
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('=== ERROR en verificarDuplicados Cliente ===', [
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
