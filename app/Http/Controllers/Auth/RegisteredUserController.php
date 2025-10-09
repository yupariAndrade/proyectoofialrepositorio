<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuarios;
use App\Models\Roles;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:usuarios,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Buscar el rol de Cliente por defecto para nuevos registros
        $rolCliente = Roles::where('nombre', 'Cliente')->first();
        
        if (!$rolCliente) {
            // Si no existe el rol Cliente, usar el primer rol disponible
            $rolCliente = Roles::first();
        }

        // Crear usuario - el modelo Usuarios hashea automáticamente en boot()
        $usuario = Usuarios::create([
            'nombre' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // El boot() del modelo Usuarios lo hashea
            'estado' => true,
            'idRol' => $rolCliente ? $rolCliente->id : 1,
            'fechaIngreso' => now(),
        ]);

        event(new Registered($usuario));

        Auth::login($usuario);

        return to_route('dashboard');
    }
}
