<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\EstadoTrabajoController;
use App\Http\Controllers\EstadoPagoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\DetalleTrabajoController;
use App\Http\Controllers\AsignacionTrabajoController;
use App\Http\Controllers\BitacoraTrabajoController;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Rutas para el sistema de fotoEstudio
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $servicios = \App\Models\Servicios::whereNotNull('imagenReferencia')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get(['id','nombreServicio','precioReferencial','imagenReferencia']);
        return Inertia::render('Dashboard', [
            'servicios' => $servicios,
        ]);
    })->name('dashboard');

    // Usuarios
    Route::controller(UsuarioController::class)->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
        Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{id}', [UsuarioController::class, 'show'])->name('usuarios.show');
        Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    });

    // Servicios
    Route::controller(ServicioController::class)->group(function () {
        Route::get('/servicios', function () {
            return Inertia::render('Servicios/Index', [
                'servicios' => \App\Models\Servicios::with('usuario')->orderBy('created_at', 'desc')->get()
            ]);
        })->name('servicios');
        Route::get('/servicios/create', function () {
            return Inertia::render('Servicios/Create', [
                'usuarios' => \App\Models\Usuarios::where('estado', true)->get()
            ]);
        })->name('servicios.create');
        Route::get('/servicios/{id}/edit', function ($id) {
            $servicio = \App\Models\Servicios::findOrFail($id);
            return Inertia::render('Servicios/Edit', [
                'servicio' => $servicio,
                'usuarios' => \App\Models\Usuarios::where('estado', true)->get()
            ]);
        })->name('servicios.edit');
        Route::get('/servicios/{id}', function ($id) {
            $servicio = \App\Models\Servicios::findOrFail($id);
            return Inertia::render('Servicios/Show', [
                'servicio' => $servicio,
                'usuario' => \App\Models\Usuarios::findOrFail($servicio->idUsuario)
            ]);
        })->name('servicios.show');
        Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');
        Route::put('/servicios/{id}', [ServicioController::class, 'update'])->name('servicios.update');
        Route::delete('/servicios/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');
    });

    // Roles
    Route::controller(RolesController::class)->group(function () {
        Route::get('/roles', [RolesController::class, 'index'])->name('roles');
        Route::get('/roles/create', [RolesController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RolesController::class, 'store'])->name('roles.store');
        Route::get('/roles/{id}', [RolesController::class, 'show'])->name('roles.show');
        Route::get('/roles/{id}/edit', [RolesController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{id}', [RolesController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}', [RolesController::class, 'destroy'])->name('roles.destroy');
    });

    // Clientes
    Route::controller(ClienteController::class)->group(function () {
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes');
        Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.show');
        Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
        Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    });

    // Trabajos
    Route::controller(TrabajoController::class)->group(function () {
        Route::get('/trabajos', [TrabajoController::class, 'index'])->name('trabajos');
        Route::get('/trabajos/create', [TrabajoController::class, 'create'])->name('trabajos.create');
        Route::post('/trabajos', [TrabajoController::class, 'store'])->name('trabajos.store');
        Route::get('/trabajos/{id}', [TrabajoController::class, 'show'])->name('trabajos.show');
        Route::get('/trabajos/{id}/edit', [TrabajoController::class, 'edit'])->name('trabajos.edit');
        Route::put('/trabajos/{id}', [TrabajoController::class, 'update'])->name('trabajos.update');
        Route::delete('/trabajos/{id}', [TrabajoController::class, 'destroy'])->name('trabajos.destroy');
        Route::get('/trabajos/estado/{estadoId}', [TrabajoController::class, 'porEstado'])->name('trabajos.porEstado');
        Route::get('/trabajos/cliente/{clienteId}', [TrabajoController::class, 'porCliente'])->name('trabajos.porCliente');
    });

    // Estados de Trabajo
    Route::controller(EstadoTrabajoController::class)->group(function () {
        Route::get('/estados-trabajo', [EstadoTrabajoController::class, 'index'])->name('estados-trabajo');
        Route::get('/estados-trabajo/create', [EstadoTrabajoController::class, 'create'])->name('estados-trabajo.create');
        Route::post('/estados-trabajo', [EstadoTrabajoController::class, 'store'])->name('estados-trabajo.store');
        Route::get('/estados-trabajo/{id}', [EstadoTrabajoController::class, 'show'])->name('estados-trabajo.show');
        Route::get('/estados-trabajo/{id}/edit', [EstadoTrabajoController::class, 'edit'])->name('estados-trabajo.edit');
        Route::put('/estados-trabajo/{id}', [EstadoTrabajoController::class, 'update'])->name('estados-trabajo.update');
        Route::delete('/estados-trabajo/{id}', [EstadoTrabajoController::class, 'destroy'])->name('estados-trabajo.destroy');
    });

    // Estados de Pago
    Route::controller(EstadoPagoController::class)->group(function () {
        Route::get('/estado-pagos', [EstadoPagoController::class, 'index'])->name('estado-pagos');
        Route::get('/estado-pagos/create', [EstadoPagoController::class, 'create'])->name('estado-pagos.create');
        Route::post('/estado-pagos', [EstadoPagoController::class, 'store'])->name('estado-pagos.store');
        Route::get('/estado-pagos/{id}', [EstadoPagoController::class, 'show'])->name('estado-pagos.show');
        Route::get('/estado-pagos/{id}/edit', [EstadoPagoController::class, 'edit'])->name('estado-pagos.edit');
        Route::put('/estado-pagos/{id}', [EstadoPagoController::class, 'update'])->name('estado-pagos.update');
        Route::delete('/estado-pagos/{id}', [EstadoPagoController::class, 'destroy'])->name('estado-pagos.destroy');
    });

    // Detalles de Trabajo
    Route::controller(DetalleTrabajoController::class)->group(function () {
        Route::get('/detalle-trabajo', [DetalleTrabajoController::class, 'index'])->name('detalle-trabajo');
        Route::get('/detalle-trabajo/create', [DetalleTrabajoController::class, 'create'])->name('detalle-trabajo.create');
        Route::post('/detalle-trabajo', [DetalleTrabajoController::class, 'store'])->name('detalle-trabajo.store');
        Route::get('/detalle-trabajo/{id}', [DetalleTrabajoController::class, 'show'])->name('detalle-trabajo.show');
        Route::get('/detalle-trabajo/{id}/edit', [DetalleTrabajoController::class, 'edit'])->name('detalle-trabajo.edit');
        Route::put('/detalle-trabajo/{id}', [DetalleTrabajoController::class, 'update'])->name('detalle-trabajo.update');
        Route::delete('/detalle-trabajo/{id}', [DetalleTrabajoController::class, 'destroy'])->name('detalle-trabajo.destroy');
        Route::get('/detalle-trabajo/trabajo/{trabajoId}', [DetalleTrabajoController::class, 'porTrabajo'])->name('detalle-trabajo.porTrabajo');
    });

    // Bitácora de Trabajos
    Route::controller(BitacoraTrabajoController::class)->group(function () {
        Route::get('/bitacora-trabajos', [BitacoraTrabajoController::class, 'index'])->name('bitacora-trabajos');
        Route::get('/bitacora-trabajos/create', [BitacoraTrabajoController::class, 'create'])->name('bitacora-trabajos.create');
        Route::post('/bitacora-trabajos', [BitacoraTrabajoController::class, 'store'])->name('bitacora-trabajos.store');
        Route::get('/bitacora-trabajos/{id}', [BitacoraTrabajoController::class, 'show'])->name('bitacora-trabajos.show');
        Route::get('/bitacora-trabajos/{id}/edit', [BitacoraTrabajoController::class, 'edit'])->name('bitacora-trabajos.edit');
        Route::put('/bitacora-trabajos/{id}', [BitacoraTrabajoController::class, 'update'])->name('bitacora-trabajos.update');
        Route::delete('/bitacora-trabajos/{id}', [BitacoraTrabajoController::class, 'destroy'])->name('bitacora-trabajos.destroy');
    });

    // Asignaciones de Trabajo
    Route::controller(AsignacionTrabajoController::class)->group(function () {
        Route::get('/asignaciones-trabajos', [AsignacionTrabajoController::class, 'index'])->name('asignaciones-trabajos');
        Route::get('/asignaciones-trabajos/create', [AsignacionTrabajoController::class, 'create'])->name('asignaciones-trabajos.create');
        Route::post('/asignaciones-trabajos', [AsignacionTrabajoController::class, 'store'])->name('asignaciones-trabajos.store');
        Route::get('/asignaciones-trabajos/{id}', [AsignacionTrabajoController::class, 'show'])->name('asignaciones-trabajos.show');
        Route::get('/asignaciones-trabajos/{id}/edit', [AsignacionTrabajoController::class, 'edit'])->name('asignaciones-trabajos.edit');
        Route::put('/asignaciones-trabajos/{id}', [AsignacionTrabajoController::class, 'update'])->name('asignaciones-trabajos.update');
        Route::delete('/asignaciones-trabajos/{id}', [AsignacionTrabajoController::class, 'destroy'])->name('asignaciones-trabajos.destroy');
    });

    // Pagos
    Route::controller(PagoController::class)->group(function () {
        Route::get('/pagos', [PagoController::class, 'index'])->name('pagos');
        Route::get('/pagos/create', [PagoController::class, 'create'])->name('pagos.create');
        Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');
        Route::get('/pagos/{id}', [PagoController::class, 'show'])->name('pagos.show');
        Route::get('/pagos/{id}/edit', [PagoController::class, 'edit'])->name('pagos.edit');
        Route::put('/pagos/{id}', [PagoController::class, 'update'])->name('pagos.update');
        Route::delete('/pagos/{id}', [PagoController::class, 'destroy'])->name('pagos.destroy');
        Route::get('/pagos/trabajo/{trabajoId}', [PagoController::class, 'porTrabajo'])->name('pagos.porTrabajo');
        Route::get('/pagos/estado/{estadoId}', [PagoController::class, 'porEstado'])->name('pagos.porEstado');
        // Checkout múltiple
        Route::get('/pagos/checkout', [PagoController::class, 'checkout'])->name('pagos.checkout');
        Route::post('/pagos/multiple', [PagoController::class, 'storeMultiple'])->name('pagos.storeMultiple');
    });
});

// 🔗 Rutas API para el sistema de fotoEstudio
Route::prefix('api')->as('api.')->middleware(['auth', 'verified'])->group(function () {
    // Roles
    Route::apiResource('roles', RolesController::class);
    
    // Usuarios
    Route::apiResource('usuarios', UsuarioController::class);
    Route::get('usuarios/exportar/pdf', [UsuarioController::class, 'exportarPDF']);
    
    // Servicios
    Route::apiResource('servicios', ServicioController::class);
    
    // Clientes
    Route::apiResource('clientes', ClienteController::class);
    
    // Estados de Trabajo
    Route::apiResource('estados-trabajo', EstadoTrabajoController::class);
    
    // Estados de Pago
    Route::apiResource('estados-pago', EstadoPagoController::class);
    
    // Trabajos
    Route::apiResource('trabajos', TrabajoController::class);
    Route::get('trabajos/estado/{estadoId}', [TrabajoController::class, 'porEstado']);
    Route::get('trabajos/cliente/{clienteId}', [TrabajoController::class, 'porCliente']);
    
    // Detalles de Trabajo
    Route::apiResource('detalles-trabajo', DetalleTrabajoController::class);
    Route::get('detalles-trabajo/trabajo/{trabajoId}', [DetalleTrabajoController::class, 'porTrabajo']);
    
    // Asignaciones de Trabajo
    Route::apiResource('asignaciones-trabajo', AsignacionTrabajoController::class);
    Route::get('asignaciones-trabajo/trabajo/{trabajoId}', [AsignacionTrabajoController::class, 'porTrabajo']);
    Route::get('asignaciones-trabajo/usuario/{usuarioId}', [AsignacionTrabajoController::class, 'porUsuario']);
    
    // Bitácora de Trabajos
    Route::apiResource('bitacora-trabajos', BitacoraTrabajoController::class);
    Route::get('bitacora-trabajos/trabajo/{trabajoId}', [BitacoraTrabajoController::class, 'porTrabajo']);
    Route::get('bitacora-trabajos/usuario/{usuarioId}', [BitacoraTrabajoController::class, 'porUsuario']);
    
    // Pagos
    Route::apiResource('pagos', PagoController::class);
    Route::get('pagos/trabajo/{trabajoId}', [PagoController::class, 'porTrabajo']);
    Route::get('pagos/estado/{estadoId}', [PagoController::class, 'porEstado']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
