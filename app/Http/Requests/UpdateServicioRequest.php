<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServicioRequest extends FormRequest
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
        $id = $this->route('id'); // Obtener el ID del servicio desde la ruta
        
        return [
            'nombreServicio' => [
                'required',
                'string',
                'max:50',
                'min:2',
                Rule::unique('servicios', 'nombreServicio')->ignore($id)
            ],
            'precioReferencial' => 'required|numeric|min:0|max:999999.99|regex:/^\d{1,6}(\.\d{1,2})?$/',
            'descripcion' => 'nullable|string|max:100|min:5',
            'estado' => 'boolean',
            'imagenReferencia' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'nombreServicio.required' => 'El nombre del servicio es obligatorio.',
            'nombreServicio.string' => 'El nombre del servicio debe ser texto.',
            'nombreServicio.max' => 'El nombre del servicio no puede tener más de 50 caracteres.',
            'nombreServicio.min' => 'El nombre del servicio debe tener al menos 2 caracteres.',
            'nombreServicio.unique' => 'Ya existe un servicio con este nombre.',
            'precioReferencial.required' => 'El precio referencial es obligatorio.',
            'precioReferencial.numeric' => 'El precio referencial debe ser un número.',
            'precioReferencial.min' => 'El precio referencial debe ser mayor o igual a 0.',
            'precioReferencial.max' => 'El precio referencial no puede ser mayor a 999,999.99.',
            'precioReferencial.regex' => 'El precio referencial debe tener máximo 6 dígitos enteros y 2 decimales.',
            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no puede tener más de 100 caracteres.',
            'descripcion.min' => 'La descripción debe tener al menos 5 caracteres.',
            'estado.boolean' => 'El estado debe ser verdadero o falso.',
            'imagenReferencia.image' => 'El archivo debe ser una imagen.',
            'imagenReferencia.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'imagenReferencia.max' => 'La imagen no puede ser mayor a 10MB.'
        ];
    }
}
