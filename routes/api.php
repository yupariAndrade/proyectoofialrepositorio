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

