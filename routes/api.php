<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta para verificar duplicados de usuarios
Route::post('/usuarios/verificar-duplicados', [App\Http\Controllers\UsuarioController::class, 'verificarDuplicados']);

// Ruta para verificar campos únicos de usuarios
Route::post('/usuarios/verificar-campo', [App\Http\Controllers\UsuarioController::class, 'verificarCampo']);

// Ruta para verificar duplicados de clientes
Route::post('/clientes/verificar-duplicados', [App\Http\Controllers\ClienteController::class, 'verificarDuplicados']);

// Ruta para verificar campos únicos de clientes
Route::post('/clientes/verificar-campo', [App\Http\Controllers\ClienteController::class, 'verificarCampo']);

// Cálculos de trabajos
Route::post('/trabajos/calcular-totales', [App\Http\Controllers\TrabajoCalculoController::class, 'calcularTotales']);
Route::get('/trabajos/{id}/calcular-totales', [App\Http\Controllers\TrabajoCalculoController::class, 'calcularTotalesTrabajo']);
Route::post('/trabajos/validar-descuento', [App\Http\Controllers\TrabajoCalculoController::class, 'validarDescuento']);

// ✅ ARQUITECTURA MVC - Cambiar estado del trabajo (con autenticación web)
Route::patch('/trabajos/{id}/estado', [App\Http\Controllers\RegistrarTrabajoController::class, 'cambiarEstado'])->middleware('web');

// ✅ ARQUITECTURA MVC - Cancelar trabajo con observaciones (con autenticación web)
Route::patch('/trabajos/{id}/cancelar', [App\Http\Controllers\RegistrarTrabajoController::class, 'cancelarTrabajo'])->middleware('web');

// ✅ ARQUITECTURA MVC - Procesar pagos parciales (cuotas)
Route::post('/cuotas', [App\Http\Controllers\CuotaController::class, 'store'])->middleware('web');

// ✅ ARQUITECTURA MVC - Generar recibos
Route::get('/recibos/{id}/vista-previa', [App\Http\Controllers\ReciboController::class, 'vistaPrevia'])->middleware('web');
Route::get('/recibos/{id}/pdf', [App\Http\Controllers\ReciboController::class, 'generarRecibo'])->middleware('web');

