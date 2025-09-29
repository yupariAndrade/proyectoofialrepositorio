<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id'); // Obtener el ID del usuario desde la ruta
        
        // Debug: Log para verificar que el ID se está obteniendo correctamente
        \Log::info('UpdateUsuarioRequest - ID obtenido: ' . $id);
        
        return [
            'nombre' => 'required|string|max:100',
            'apellidoPaterno' => 'nullable|string|max:100',
            'apellidoMaterno' => 'nullable|string|max:100',
            'ci' => 'required|string|max:20|unique:usuarios,ci,' . $id . '|regex:/^[0-9]+$/',
            'telefono' => 'required|string|max:20|unique:usuarios,telefono,' . $id . '|regex:/^[0-9]+$/',
            'direccion' => 'nullable|string|max:255',
            'email' => 'required|email|max:150|unique:usuarios,email,' . $id,
            'password' => 'nullable|string|min:6|max:255',
            'fechaIngreso' => 'nullable|date',
            'fechaFinal' => 'nullable|date',
            'estado' => 'required|boolean',
            'idRol' => 'required|exists:roles,id'
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'apellidoPaterno.max' => 'El apellido paterno no puede tener más de 100 caracteres.',
            'apellidoMaterno.max' => 'El apellido materno no puede tener más de 100 caracteres.',
            'ci.required' => 'El CI es obligatorio.',
            'ci.max' => 'El CI no puede tener más de 20 caracteres.',
            'ci.unique' => 'Ya existe un usuario con este CI.',
            'ci.regex' => 'El CI solo puede contener números.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'telefono.unique' => 'Ya existe un usuario con este teléfono.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.max' => 'El email no puede tener más de 150 caracteres.',
            'email.unique' => 'Ya existe un usuario con este email.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.max' => 'La contraseña no puede tener más de 255 caracteres.',
            'fechaIngreso.date' => 'La fecha de ingreso debe ser una fecha válida.',
            'fechaFinal.date' => 'La fecha final debe ser una fecha válida.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.boolean' => 'El estado debe ser verdadero o falso.',
            'idRol.required' => 'El rol es obligatorio.',
            'idRol.exists' => 'El rol seleccionado no existe.'
        ];
    }
}
