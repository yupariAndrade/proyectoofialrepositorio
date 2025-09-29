<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $usuario = Auth::user();
        
        // Verificar si el usuario está activo
        if (!$usuario->estado) {
            abort(403, 'Tu cuenta está inactiva. Contacta al administrador.');
        }
        
        // Verificar rol directamente
        if (!$usuario->rol || $usuario->rol->nombre !== $role) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }
}
