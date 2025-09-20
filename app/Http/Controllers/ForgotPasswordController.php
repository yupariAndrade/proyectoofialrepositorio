<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /**
     * Mostrar el formulario de recuperación de contraseña
     */
    public function show()
    {
        return view('auth.forgot-password');
    }

    /**
     * Actualizar la contraseña del usuario
     */
    public function updatePassword(Request $request)
    {

        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:usuarios,email',
            'password' => [
                'required',
                'string',
                'min:6',
                'max:255',
                'confirmed'
            ],
            'password_confirmation' => 'required|string|min:6'
        ], [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.exists' => 'No existe un usuario con este email.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.max' => 'La contraseña no puede tener más de 255 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password_confirmation.required' => 'La confirmación de contraseña es obligatoria.',
            'password_confirmation.min' => 'La confirmación de contraseña debe tener al menos 6 caracteres.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Buscar el usuario por email con su rol
            $usuario = Usuarios::with('rol')->where('email', $request->email)->first();

            if (!$usuario) {
                return redirect()->back()
                    ->withErrors(['email' => 'No existe un usuario con este email.'])
                    ->withInput();
            }

            // 🔐 VALIDACIONES DE SEGURIDAD ADICIONALES
            
            // 1. Verificar que el usuario esté ACTIVO
            if (!$usuario->estado) {
                return redirect()->back()
                    ->withErrors(['email' => 'Tu cuenta está inactiva. Contacta al administrador.'])
                    ->withInput();
            }

            // 2. Verificar que tenga un ROL válido
            if (!$usuario->rol) {
                return redirect()->back()
                    ->withErrors(['email' => 'Tu cuenta no tiene un rol asignado. Contacta al administrador.'])
                    ->withInput();
            }

            // 3. Verificar que la nueva contraseña sea diferente a la actual
            if (Hash::check($request->password, $usuario->password)) {
                return redirect()->back()
                    ->withErrors(['password' => 'La nueva contraseña debe ser diferente a la actual.'])
                    ->withInput();
            }

            // Actualizar solo la contraseña
            $usuario->update([
                'password' => $request->password // El modelo Usuarios ya hashea automáticamente
            ]);

            return redirect()->back()->with('success', 'Contraseña actualizada exitosamente. Ahora puedes iniciar sesión con tu nueva contraseña.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al actualizar contraseña: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Ocurrió un error al actualizar la contraseña. Por favor, intenta nuevamente.'])
                ->withInput();
        }
    }
}