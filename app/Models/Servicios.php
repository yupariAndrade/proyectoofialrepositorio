<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Servicios extends Model
{
     use HasFactory;
    protected $table = 'servicios';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombreServicio',
        'precioReferencial',
        'descripcion',
        'estado',
        'imagenReferencia',
        'idUsuario',
    ];

    public $timestamps = true;

    // 🔗 Relación: Un servicio pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'idUsuario');
    }

    // 🔗 Relación: Un servicio puede estar en múltiples detalles de trabajo
    public function detallesTrabajo()
    {
        return $this->hasMany(DetalleTrabajo::class, 'idServicio');
    }

    // 📋 Reglas de validación para el modelo
    public static function rules($userId = null, $excludeId = null)
    {
        $rules = [
            'nombreServicio' => [
                'required',
                'string',
                'max:50',
                'min:2',
                Rule::unique('servicios', 'nombreServicio')->ignore($excludeId)
            ],
            'precioReferencial' => 'required|numeric|min:0|max:999999.99|regex:/^\d{1,6}(\.\d{1,2})?$/',
            'descripcion' => 'nullable|string|max:100|min:5',
            'estado' => 'boolean',
        ];

        // Si es creación (no hay excludeId), la imagen es requerida
        if (!$excludeId) {
            $rules['imagenReferencia'] = 'required|image|mimes:jpeg,png,jpg,gif|max:10240';
        } else {
            // Si es edición, la imagen es opcional
            $rules['imagenReferencia'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240';
        }

        return $rules;
    }

    // 🔍 Método para verificar duplicados
    public static function existeDuplicado($nombreServicio, $userId = null, $excludeId = null)
    {
        $query = self::where('nombreServicio', $nombreServicio);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
}
