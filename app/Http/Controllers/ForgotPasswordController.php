<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

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
            // Buscar el usuario por email
            $usuario = Usuarios::where('email', $request->email)->first();

            if (!$usuario) {
                return redirect()->back()
                    ->withErrors(['email' => 'No existe un usuario con este email.'])
                    ->withInput();
            }

            // Actualizar solo la contraseña
            $usuario->update([
                'password' => $request->password // El modelo Usuarios ya hashea automáticamente en boot()
            ]);

            return redirect()->back()->with('success', 'Contraseña actualizada exitosamente. Ahora puedes iniciar sesión con tu nueva contraseña.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Ocurrió un error al actualizar la contraseña. Por favor, intenta nuevamente.'])
                ->withInput();
        }
    }
}
