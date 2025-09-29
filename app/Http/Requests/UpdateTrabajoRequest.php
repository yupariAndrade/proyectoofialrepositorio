<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrabajoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // ✅ Permitir a usuarios autenticados
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // ✅ Validaciones del cliente
            'cliente' => 'required|exists:clientes,id',
            
            // ✅ Validaciones de servicios
            'servicios' => 'required|array|min:1',
            'servicios.*.idServicio' => 'required|exists:servicios,id',
            'servicios.*.cantidad' => 'required|integer|min:1',
            'servicios.*.descuento' => 'nullable|numeric|min:0',
            
            // ✅ Validaciones de detalles del servicio
            'servicios.*.detalles.tamano' => 'nullable|string|max:50',
            'servicios.*.detalles.color' => 'nullable|string|max:50',
            'servicios.*.detalles.modelo' => 'nullable|string|max:50',
            'servicios.*.detalles.descripcion' => 'nullable|string|max:255',
            
            // ✅ Validaciones del trabajo
            'fechaEntrega' => 'required|date|after:today',
            'idResponsable' => 'nullable|exists:usuarios,id',
            'aCuenta' => 'nullable|numeric|min:0',
            'idEstadoPago' => 'nullable|exists:estados_pago,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'cliente.required' => 'Debe seleccionar un cliente',
            'cliente.exists' => 'El cliente seleccionado no existe',
            'servicios.required' => 'Debe agregar al menos un servicio',
            'servicios.min' => 'Debe agregar al menos un servicio',
            'servicios.*.idServicio.required' => 'Debe seleccionar un servicio',
            'servicios.*.idServicio.exists' => 'El servicio seleccionado no existe',
            'servicios.*.cantidad.required' => 'La cantidad es requerida',
            'servicios.*.cantidad.min' => 'La cantidad debe ser al menos 1',
            'servicios.*.descuento.min' => 'El descuento no puede ser negativo',
            'fechaEntrega.required' => 'La fecha de entrega es requerida',
            'fechaEntrega.after' => 'La fecha de entrega debe ser posterior a hoy',
            'idResponsable.exists' => 'El responsable seleccionado no existe',
            'aCuenta.min' => 'El monto a cuenta no puede ser negativo',
            'idEstadoPago.exists' => 'El estado de pago seleccionado no existe',
        ];
    }
}
