<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Usuarios;
use App\Models\Roles;
use App\Models\Clientes;
use App\Models\Servicios;
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
            $fechaGeneracion = now()->format('d/m/Y H:i:s');
            
            // Generar el HTML para el PDF
            $html = view('reportes.usuarios', compact(
                'usuarios', 
                'fechaGeneracion'
            ))->render();
            
            // Generar el PDF
            $pdf = Pdf::loadHTML($html);
            
            // Descargar el PDF
            return $pdf->download('lista-usuarios-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            
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

    /**
     * Mostrar el reporte de clientes
     */
    public function reporteClientes()
    {
        $clientes = Clientes::with('usuario')->get();
        
        return Inertia::render('Reportes/Clientes/ReporteClientes', [
            'clientes' => $clientes
        ]);
    }

    /**
     * Generar PDF del reporte de clientes
     */
    public function generarPDFClientes()
    {
        try {
            $clientes = Clientes::with('usuario')->get();
            $fechaGeneracion = now()->format('d/m/Y H:i:s');
            
            // Generar el HTML para el PDF
            $html = view('reportes.clientes', compact(
                'clientes', 
                'fechaGeneracion'
            ))->render();
            
            // Generar el PDF
            $pdf = Pdf::loadHTML($html);
            
            // Descargar el PDF
            return $pdf->download('lista-clientes-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            
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

    /**
     * Generar PDF del reporte de servicios
     */
    public function generarPDFServicios()
    {
        try {
            $servicios = Servicios::with('usuario')->get();
            $fechaGeneracion = now()->format('d/m/Y H:i:s');
            
            // Generar el HTML para el PDF
            $html = view('reportes.servicios', compact(
                'servicios', 
                'fechaGeneracion'
            ))->render();
            
            // Generar el PDF
            $pdf = Pdf::loadHTML($html);
            
            // Descargar el PDF
            return $pdf->download('lista-servicios-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            
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
