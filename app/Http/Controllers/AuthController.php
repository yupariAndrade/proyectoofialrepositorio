<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLogin()
    {
        return Inertia::render('auth/Login', [
            'canResetPassword' => true
        ]);
    }

    /**
     * Procesar el login
     */
    public function login(Request $request)
    {
        // Debug temporal - ver qué datos llegan
        \Log::info('Datos recibidos en login:', $request->all());
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // Buscar usuario por email
        $usuario = Usuarios::where('email', $request->email)->first();

        if (!$usuario) {
            return back()->withErrors([
                'email' => 'El email no está registrado.'
            ]);
        }

        // Verificar contraseña
        if (!Hash::check($request->password, $usuario->password)) {
            return back()->withErrors([
                'password' => 'La contraseña es incorrecta.'
            ]);
        }

        // Verificar que el usuario esté activo
        if (!$usuario->estado) {
            return back()->withErrors([
                'email' => 'Tu cuenta está desactivada.'
            ]);
        }

        // Crear sesión del usuario
        Auth::login($usuario);

        // Redirigir según el rol
        return $this->redirectBasedOnRole($usuario);
    }

    /**
     * Redirigir según el rol del usuario
     */
    private function redirectBasedOnRole($usuario)
    {
        switch ($usuario->rol->nombre) {
            case 'administrador':
                return redirect()->route('dashboard.admin');
            case 'empleado':
                return redirect()->route('dashboard.admin');
            case 'gerente':
                return redirect()->route('dashboard.admin');
            default:
                return redirect()->route('dashboard.admin');
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }

    /**
     * Mostrar dashboard según el rol
     */
    public function dashboard()
    {
        $usuario = Auth::user();
        
        if (!$usuario) {
            return redirect()->route('login');
        }

        // Obtener estadísticas del sistema
        $stats = $this->getSystemStats();
        
        // Obtener trabajos recientes
        $trabajosRecientes = $this->getRecentWorks();

        return Inertia::render('AuthenticatedDashboard', [
            'usuario' => $usuario->load('rol'),
            'stats' => $stats,
            'trabajosRecientes' => $trabajosRecientes
        ]);
    }

    /**
     * Obtener menús según el rol
     */
    private function getMenusByRole($rolNombre)
    {
        $menus = [
            'administrador' => [
                ['nombre' => 'Dashboard', 'ruta' => 'dashboard.admin', 'icono' => 'grid'],
                ['nombre' => 'Usuarios', 'ruta' => 'usuarios', 'icono' => 'users'],
                ['nombre' => 'Registrar Trabajos', 'ruta' => 'registrar-trabajos', 'icono' => 'clipboard'],
                ['nombre' => 'Clientes', 'ruta' => 'clientes', 'icono' => 'people'],
                ['nombre' => 'Servicios', 'ruta' => 'servicios', 'icono' => 'checklist'],
                ['nombre' => 'Trabajos', 'ruta' => 'trabajos', 'icono' => 'briefcase'],
                ['nombre' => 'Pagos', 'ruta' => 'pagos', 'icono' => 'credit-card'],
                ['nombre' => 'Reportes', 'ruta' => 'reportes.usuarios', 'icono' => 'chart-bar'],
                ['nombre' => 'Roles', 'ruta' => 'roles', 'icono' => 'settings']
            ],
            'empleado' => [
                ['nombre' => 'Dashboard', 'ruta' => 'dashboard.admin', 'icono' => 'grid'],
                ['nombre' => 'Registrar Trabajos', 'ruta' => 'registrar-trabajos', 'icono' => 'clipboard'],
                ['nombre' => 'Clientes', 'ruta' => 'clientes', 'icono' => 'people'],
                ['nombre' => 'Servicios', 'ruta' => 'servicios', 'icono' => 'checklist'],
                ['nombre' => 'Reportes', 'ruta' => 'reportes.usuarios', 'icono' => 'chart-bar']
            ],
            'gerente' => [
                ['nombre' => 'Dashboard', 'ruta' => 'dashboard.admin', 'icono' => 'grid'],
                ['nombre' => 'Registrar Trabajos', 'ruta' => 'registrar-trabajos', 'icono' => 'clipboard'],
                ['nombre' => 'Clientes', 'ruta' => 'clientes', 'icono' => 'people'],
                ['nombre' => 'Servicios', 'ruta' => 'servicios', 'icono' => 'checklist'],
                ['nombre' => 'Trabajos', 'ruta' => 'trabajos', 'icono' => 'briefcase'],
                ['nombre' => 'Reportes', 'ruta' => 'reportes.usuarios', 'icono' => 'chart-bar']
            ]
        ];

        return $menus[$rolNombre] ?? $menus['empleado'];
    }

    /**
     * Obtener estadísticas del sistema
     */
    private function getSystemStats()
    {
        // Importar los modelos necesarios
        $servicios = \App\Models\Servicios::count();
        $serviciosActivos = \App\Models\Servicios::where('estado', true)->count();
        $serviciosInactivos = \App\Models\Servicios::where('estado', false)->count();
        
        $clientes = \App\Models\Clientes::count();
        
        $trabajosPendientes = \App\Models\Trabajos::whereHas('estado', function($query) {
            $query->where('nombre', 'Pendiente');
        })->count();

        $trabajosEnProceso = \App\Models\Trabajos::whereHas('estado', function($query) {
            $query->where('nombre', 'En Proceso');
        })->count();

        $trabajosCompletados = \App\Models\Trabajos::whereHas('estado', function($query) {
            $query->where('nombre', 'Completado');
        })->count();

        $trabajosCancelados = \App\Models\Trabajos::whereHas('estado', function($query) {
            $query->where('nombre', 'Cancelado');
        })->count();

        $usuarios = \App\Models\Usuarios::count();
        $usuariosActivos = \App\Models\Usuarios::where('estado', true)->count();

        return [
            'servicios' => [
                'total' => $servicios,
                'activos' => $serviciosActivos,
                'inactivos' => $serviciosInactivos
            ],
            'clientes' => [
                'total' => $clientes
            ],
            'trabajos' => [
                'pendientes' => $trabajosPendientes,
                'enProceso' => $trabajosEnProceso,
                'completados' => $trabajosCompletados,
                'cancelados' => $trabajosCancelados,
                'total' => $trabajosPendientes + $trabajosEnProceso + $trabajosCompletados + $trabajosCancelados
            ],
            'usuarios' => [
                'total' => $usuarios,
                'activos' => $usuariosActivos
            ]
        ];
    }

    /**
     * Obtener trabajos recientes
     */
    private function getRecentWorks()
    {
        return \App\Models\Trabajos::with(['cliente', 'estado', 'responsable'])
            ->orderBy('fechaRegistro', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($trabajo) {
                return [
                    'id' => $trabajo->id,
                    'cliente' => $trabajo->cliente ? $trabajo->cliente->nombre . ' ' . $trabajo->cliente->apellido : 'Sin cliente',
                    'estado' => $trabajo->estado ? $trabajo->estado->nombre : 'Sin estado',
                    'fechaRegistro' => $trabajo->fechaRegistro ? (is_string($trabajo->fechaRegistro) ? $trabajo->fechaRegistro : $trabajo->fechaRegistro->format('d/m/Y')) : 'Sin fecha',
                    'responsable' => $trabajo->responsable ? $trabajo->responsable->nombre : 'Sin responsable'
                ];
            });
    }
}
