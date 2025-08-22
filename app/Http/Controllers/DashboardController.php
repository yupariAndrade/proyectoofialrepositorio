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
        $trabajosRecientes = Trabajos::with(['cliente', 'servicio', 'estado'])
            ->latest()
            ->take(5)
            ->get();

        // Servicios para el carrusel (con imágenes)
        $servicios = Servicios::whereNotNull('imagenReferencia')
            ->where('estado', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get(['id', 'nombreServicio', 'precioReferencial', 'imagenReferencia', 'estado']);

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
