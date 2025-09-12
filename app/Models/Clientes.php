<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Clientes extends Model
{
       use HasFactory;
    protected $table = 'clientes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'apellido',
        'ci',
        'telefono',
        'correoElectronico',
        'idUsuario',
    ];

    public $timestamps = true;

    // 🔗 Relación: Un cliente pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'idUsuario');
    }

    // 📋 Reglas de validación para el modelo
    public static function rules($excludeId = null)
    {
        $rules = [
            'nombre' => [
                'required',
                'string',
                'max:50',
                'min:2',
                Rule::unique('clientes', 'nombre')->ignore($excludeId)
            ],
            'apellido' => [
                'required',
                'string',
                'max:50',
                'min:2',
                Rule::unique('clientes', 'apellido')->ignore($excludeId)
            ],
            'ci' => [
                'required',
                'string',
                'max:20',
                'min:5',
                'regex:/^[0-9]+$/',
                Rule::unique('clientes', 'ci')->ignore($excludeId)
            ],
            'telefono' => [
                'required',
                'string',
                'max:20',
                'min:7',
                'regex:/^[0-9+\-\s()]+$/'
            ],
            'correoElectronico' => [
                'required',
                'email',
                'max:150',
                'min:5'
            ],
        ];

        return $rules;
    }

    // 🔍 Método para verificar duplicados
    public static function existeDuplicado($nombre, $apellido, $ci, $excludeId = null)
    {
        $query = self::where(function ($q) use ($nombre, $apellido, $ci) {
            $q->where('nombre', $nombre)
              ->orWhere('apellido', $apellido)
              ->orWhere('ci', $ci);
        });
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
}
