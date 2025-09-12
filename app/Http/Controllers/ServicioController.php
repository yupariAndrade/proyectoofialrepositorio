<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;


class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        return Servicios::with('usuario')->get();
    }

    public function store(Request $request)
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();
        
        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para crear servicios');
        }

        // Usar las reglas del modelo
        $validated = $request->validate(Servicios::rules($usuario->id));

        // Verificación manual adicional para duplicados usando el método del modelo
        if (Servicios::existeDuplicado($validated['nombreServicio'])) {
            return redirect()->back()
                           ->withInput()
                           ->withErrors(['nombreServicio' => 'Ya existe un servicio con este nombre en el sistema']);
        }

        // Asignar automáticamente el usuario autenticado
        $validated['idUsuario'] = $usuario->id;

        if ($request->hasFile('imagenReferencia')) {
            $path = $request->file('imagenReferencia')->store('servicios', 'public');
            $validated['imagenReferencia'] = $path;
        }

        Servicios::create($validated);

        return redirect()->back()->with('success', 'Servicio registrado exitosamente');
    }

    /**
     * Verificar si existe un servicio con el mismo nombre para el mismo usuario
     */
    public function verificarDuplicado(Request $request)
    {
        $usuario = Auth::user();
        
        if (!$usuario) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $nombreServicio = $request->input('nombreServicio');
        $existe = Servicios::where('nombreServicio', $nombreServicio)->exists();

        return response()->json([
            'existe' => $existe,
            'mensaje' => $existe ? 'Ya existe un servicio con este nombre' : 'Nombre disponible'
        ]);
    }

    /**
     * Limpiar duplicados existentes (método de mantenimiento)
     */
    public function limpiarDuplicados()
    {
        $usuario = Auth::user();
        
        if (!$usuario || !$usuario->isAdmin) {
            return redirect()->back()->with('error', 'Solo administradores pueden limpiar duplicados');
        }

        // Encontrar duplicados por nombre y usuario
        $duplicados = Servicios::select('nombreServicio', 'idUsuario')
                              ->groupBy('nombreServicio', 'idUsuario')
                              ->havingRaw('COUNT(*) > 1')
                              ->get();

        $eliminados = 0;
        foreach ($duplicados as $duplicado) {
            // Mantener el más reciente y eliminar los demás
            $servicios = Servicios::where('nombreServicio', $duplicado->nombreServicio)
                                ->where('idUsuario', $duplicado->idUsuario)
                                ->orderBy('created_at', 'desc')
                                ->get();

            // Eliminar todos excepto el más reciente
            for ($i = 1; $i < $servicios->count(); $i++) {
                $servicios[$i]->delete();
                $eliminados++;
            }
        }

        return redirect()->back()->with('success', "Se eliminaron {$eliminados} servicios duplicados");
    }

    public function show($id)
    {
        return Servicios::with('usuario')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicios::findOrFail($id);
        $usuario = Auth::user()->load('rol');
        
        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para editar servicios');
        }

        // Verificar que solo el usuario que creó el servicio, empleados o administradores puedan editarlo
        if ($servicio->idUsuario !== $usuario->id && !$usuario->isAdmin && !$usuario->isEmpleado && !$usuario->isGerente) {
            return redirect()->route('servicios')->with('error', 'No tiene permisos para editar este servicio');
        }

        // Usar las reglas del modelo con exclusión del ID actual
        $validated = $request->validate(Servicios::rules($usuario->id, $id));

        // Verificación manual adicional para duplicados usando el método del modelo
        if (Servicios::existeDuplicado($validated['nombreServicio'], null, $id)) {
            return redirect()->back()
                           ->withInput()
                           ->withErrors(['nombreServicio' => 'Ya existe un servicio con este nombre en el sistema']);
        }

        // Mantener el usuario original que creó el servicio
        $validated['idUsuario'] = $servicio->idUsuario;

        if ($request->hasFile('imagenReferencia')) {
            // Eliminar imagen anterior si existe
            if ($servicio->imagenReferencia) {
                Storage::disk('public')->delete($servicio->imagenReferencia);
            }
            $path = $request->file('imagenReferencia')->store('servicios', 'public');
            $validated['imagenReferencia'] = $path;
        } else {
            // Mantener la imagen existente si no se sube una nueva
            $validated['imagenReferencia'] = $servicio->imagenReferencia;
        }

        $servicio->update($validated);

        return redirect()->back()->with('success', 'Servicio actualizado exitosamente');
    }

    public function destroy($id)
    {
        $servicio = Servicios::findOrFail($id);
        $usuario = Auth::user()->load('rol');
        
        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para eliminar servicios');
        }

        // Debug para ver qué está pasando
        Log::info('Usuario autenticado:', [
            'id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'isAdmin' => $usuario->isAdmin,
            'rol' => $usuario->rol ? $usuario->rol->nombre : 'Sin rol'
        ]);

        // Verificar que solo un administrador pueda eliminar servicios
        if (!$usuario->isAdmin) {
            return redirect()->route('servicios')->with('error', 'Solo los administradores pueden eliminar servicios');
        }

        if ($servicio->imagenReferencia) {
            Storage::disk('public')->delete($servicio->imagenReferencia);
        }
        $servicio->delete();

        return redirect()->route('servicios')->with('success', 'Servicio eliminado exitosamente');
    }
}
