<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Usuarios;
use App\Models\Roles;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReporteController extends Controller
{
    /**
     * Mostrar el reporte de usuarios
     */
    public function reporteUsuarios()
    {
        $usuarios = Usuarios::with('rol')->get();
        
        return Inertia::render('Reportes/Usuarios/ReporteUsuarios', [
            'usuarios' => $usuarios
        ]);
    }

    /**
     * Generar PDF del reporte de usuarios
     */
    public function generarPDFUsuarios()
    {
        try {
            $usuarios = Usuarios::with('rol')->get();
            
            // Estadísticas
            $totalUsuarios = $usuarios->count();
            $usuariosActivos = $usuarios->where('estado', true)->count();
            $usuariosInactivos = $usuarios->where('estado', false)->count();
            $fechaGeneracion = now()->format('d/m/Y H:i:s');
            
            // Generar el HTML para el PDF
            $html = view('reportes.usuarios', compact(
                'usuarios', 
                'totalUsuarios', 
                'usuariosActivos', 
                'usuariosInactivos', 
                'fechaGeneracion'
            ))->render();
            
            // Generar el PDF
            $pdf = Pdf::loadHTML($html);
            
            // Descargar el PDF
            return $pdf->download('reporte-usuarios-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error generating PDF: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return error response
            return response()->json([
                'error' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
