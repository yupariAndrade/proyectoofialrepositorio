<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Servicios;
use App\Models\Clientes;
use App\Models\Trabajos;
use App\Models\Pagos;
use App\Models\Usuarios;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas de Servicios
        $totalServicios = Servicios::count();
        $serviciosActivos = Servicios::where('estado', true)->count();
        $serviciosInactivos = Servicios::where('estado', false)->count();

        // Estadísticas de Clientes
        $totalClientes = Clientes::count();

        // Estadísticas de Trabajos por Estado
        $trabajosPendientes = Trabajos::whereHas('estado', function($query) {
            $query->where('nombre', 'Pendiente');
        })->count();

        $trabajosEnProceso = Trabajos::whereHas('estado', function($query) {
            $query->where('nombre', 'En Proceso');
        })->count();

        $trabajosCompletados = Trabajos::whereHas('estado', function($query) {
            $query->where('nombre', 'Completado');
        })->count();

        $trabajosCancelados = Trabajos::whereHas('estado', function($query) {
            $query->where('nombre', 'Cancelado');
        })->count();

        // Estadísticas de Ingresos
        $ingresosHoy = Pagos::whereHas('trabajo', function($query) {
            $query->whereDate('fechaRegistro', Carbon::today());
        })->sum('aCuenta');
        
        $ingresosMes = Pagos::whereHas('trabajo', function($query) {
            $query->whereMonth('fechaRegistro', Carbon::now()->month);
        })->sum('aCuenta');
        
        $ingresosTotales = Pagos::sum('aCuenta');

        // Estadísticas de Usuarios
        $totalUsuarios = Usuarios::count();
        $usuariosActivos = Usuarios::where('estado', true)->count();

        // Trabajos recientes (últimos 5)
        $trabajosRecientes = Trabajos::with(['cliente', 'detalleTrabajo.servicio', 'estado', 'responsable'])
            ->latest()
            ->take(5)
            ->get();

        // Servicios para el carrusel (todos los activos, con o sin imagen)
        $servicios = Servicios::where('estado', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get(['id', 'nombreServicio', 'precioReferencial', 'imagenReferencia', 'estado']);

        // Si no hay servicios, crear algunos de ejemplo
        if ($servicios->isEmpty()) {
            $servicios = collect([
                (object) [
                    'id' => 1,
                    'nombreServicio' => 'Sesión de Retratos',
                    'precioReferencial' => 150.00,
                    'imagenReferencia' => null,
                    'estado' => true
                ],
                (object) [
                    'id' => 2,
                    'nombreServicio' => 'Fotografía de Eventos',
                    'precioReferencial' => 300.00,
                    'imagenReferencia' => null,
                    'estado' => true
                ],
                (object) [
                    'id' => 3,
                    'nombreServicio' => 'Fotografía Comercial',
                    'precioReferencial' => 250.00,
                    'imagenReferencia' => null,
                    'estado' => true
                ]
            ]);
        }

        return Inertia::render('Dashboard', [
            'stats' => [
                'servicios' => [
                    'total' => $totalServicios,
                    'activos' => $serviciosActivos,
                    'inactivos' => $serviciosInactivos,
                ],
                'clientes' => [
                    'total' => $totalClientes,
                ],
                'trabajos' => [
                    'pendientes' => $trabajosPendientes,
                    'enProceso' => $trabajosEnProceso,
                    'completados' => $trabajosCompletados,
                    'cancelados' => $trabajosCancelados,
                    'total' => $trabajosPendientes + $trabajosEnProceso + $trabajosCompletados + $trabajosCancelados,
                ],
                'ingresos' => [
                    'hoy' => $ingresosHoy,
                    'mes' => $ingresosMes,
                    'total' => $ingresosTotales,
                ],
                'usuarios' => [
                    'total' => $totalUsuarios,
                    'activos' => $usuariosActivos,
                ],
            ],
            'trabajosRecientes' => $trabajosRecientes,
            'servicios' => $servicios,
        ]);
    }
}
