<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClienteRequest extends FormRequest
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
        return [
            'nombre' => [
                'required',
                'string',
                'max:50',
                'min:2'
            ],
            'apellido' => [
                'required',
                'string',
                'max:50',
                'min:2',
                Rule::unique('clientes', 'apellido')
            ],
            'ci' => [
                'required',
                'string',
                'max:20',
                'min:5',
                'regex:/^[0-9]+$/',
                Rule::unique('clientes', 'ci')
            ],
            'telefono' => [
                'required',
                'string',
                'max:20',
                'min:8',
                'regex:/^[0-9]+$/'
            ],
            'correoElectronico' => [
                'required',
                'email',
                'max:100',
                Rule::unique('clientes', 'correoElectronico')
            ]
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.string' => 'El apellido debe ser texto.',
            'apellido.max' => 'El apellido no puede tener más de 50 caracteres.',
            'apellido.min' => 'El apellido debe tener al menos 2 caracteres.',
            'apellido.unique' => 'Ya existe un cliente con este apellido.',
            'ci.required' => 'El CI es obligatorio.',
            'ci.string' => 'El CI debe ser texto.',
            'ci.max' => 'El CI no puede tener más de 20 caracteres.',
            'ci.min' => 'El CI debe tener al menos 5 caracteres.',
            'ci.regex' => 'El CI solo puede contener números.',
            'ci.unique' => 'Ya existe un cliente con este CI.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.string' => 'El teléfono debe ser texto.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'telefono.min' => 'El teléfono debe tener al menos 8 caracteres.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'correoElectronico.required' => 'El correo electrónico es obligatorio.',
            'correoElectronico.email' => 'El correo electrónico debe tener un formato válido.',
            'correoElectronico.max' => 'El correo electrónico no puede tener más de 100 caracteres.',
            'correoElectronico.unique' => 'Ya existe un cliente con este correo electrónico.'
        ];
    }
}
