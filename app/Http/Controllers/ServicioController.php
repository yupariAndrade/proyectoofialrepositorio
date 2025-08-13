<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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
        $validated = $request->validate([
            'nombreServicio' => 'required|string|max:100',
            'precioReferencial' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'required|boolean',
            'imagenReferencia' => 'nullable|image|max:10240', // 10MB max
            'idUsuario' => 'required|exists:usuarios,id'
        ]);

        if ($request->hasFile('imagenReferencia')) {
            $path = $request->file('imagenReferencia')->store('servicios', 'public');
            $validated['imagenReferencia'] = $path;
        }

        Servicios::create($validated);

        return to_route('servicios');
    }

    public function show($id)
    {
        return Servicios::with('usuario')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicios::findOrFail($id);

        $validated = $request->validate([
            'nombreServicio' => 'required|string|max:100',
            'precioReferencial' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'required|boolean',
            'imagenReferencia' => 'nullable|image|max:10240', // 10MB max
            'idUsuario' => 'required|exists:usuarios,id'
        ]);

        if ($request->hasFile('imagenReferencia')) {
            // Eliminar imagen anterior si existe
            if ($servicio->imagenReferencia) {
                Storage::disk('public')->delete($servicio->imagenReferencia);
            }
            $path = $request->file('imagenReferencia')->store('servicios', 'public');
            $validated['imagenReferencia'] = $path;
        }

        $servicio->update($validated);

        return to_route('servicios');
    }

    public function destroy($id)
    {
        $servicio = Servicios::findOrFail($id);
        if ($servicio->imagenReferencia) {
            Storage::disk('public')->delete($servicio->imagenReferencia);
        }
        $servicio->delete();

        return redirect()->route('servicios')->with('success', 'Servicio eliminado');
    }
}
