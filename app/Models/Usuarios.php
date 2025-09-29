<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Validation\Rule;

class Usuarios extends Authenticatable
{
      use HasFactory;

    protected $table = 'usuarios';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'apellidoPaterno',
        'apellidoMaterno',
        'ci',
        'telefono',
        'direccion',
        'email',
        'password',
        'fechaIngreso',
        'fechaFinal',
        'estado',
        'idRol',
        'idResponsable'
    ];

    public $timestamps = true;

    // 🔗 Relación: Un usuarios pertenece a roles
    public function rol()
    {
        return $this->belongsTo(Roles::class, 'idRol');
    }

    // 🔗 Relación: Un usuario puede ser responsable de otros usuarios
    public function responsable()
    {
        return $this->belongsTo(Usuarios::class, 'idResponsable');
    }

    // 🔗 Relación: Un usuario puede tener usuarios bajo su responsabilidad
    public function usuariosResponsables()
    {
        return $this->hasMany(Usuarios::class, 'idResponsable');
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole($role)
    {
        return $this->rol && $this->rol->nombre === $role;
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function getIsAdminAttribute()
    {
        return $this->rol && $this->rol->nombre === 'Administrador';
    }

    /**
     * Verificar si el usuario es empleado
     */
    public function getIsEmpleadoAttribute()
    {
        return $this->rol && $this->rol->nombre === 'Empleado';
    }

    /**
     * Verificar si el usuario es gerente
     */
    public function getIsGerenteAttribute()
    {
        return $this->rol && $this->rol->nombre === 'Gerente';
    }

    /**
     * Verificar si el usuario es encargado
     */
    public function getIsEncargadoAttribute()
    {
        return $this->rol && $this->rol->nombre === 'Encargado';
    }

    /**
     * Reglas de validación para evitar duplicados
     */
    public static function getValidationRules($userId = null)
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('usuarios')->ignore($userId)
            ],
            'apellidoPaterno' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('usuarios')->ignore($userId)
            ],
            'apellidoMaterno' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('usuarios')->ignore($userId)
            ],
            'ci' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('usuarios')->ignore($userId)
            ],
            'telefono' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('usuarios')->ignore($userId)
            ],
            'direccion' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('usuarios')->ignore($userId)
            ],
            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('usuarios')->ignore($userId)
            ],
            'password' => [
                'nullable',
                'string',
                'min:6',
                'max:255'
            ],
            'fechaIngreso' => [
                'nullable',
                'date',
                Rule::unique('usuarios')->ignore($userId)
            ],
            'fechaFinal' => 'nullable|date', // Este campo NO es único
            'estado' => 'required|boolean',
            'idRol' => 'required|exists:roles,id'
        ];
    }

    /**
     * Verificar si existe un usuario con cualquier campo duplicado
     */
    public static function verificarDuplicados($data, $excludeId = null)
    {
        $campos = ['nombre', 'apellidoPaterno', 'apellidoMaterno', 'ci', 'telefono', 'direccion', 'email', 'fechaIngreso'];
        $duplicados = [];
        
        foreach ($campos as $campo) {
            if (!empty($data[$campo])) {
                $query = self::where($campo, $data[$campo]);
                if ($excludeId) {
                    $query->where('id', '!=', $excludeId);
                }
                
                if ($query->exists()) {
                    $duplicados[$campo] = "El campo {$campo} ya existe en otro usuario";
                }
            }
        }
        
        return $duplicados;
    }

    /**
     * Hash de la contraseña antes de guardar
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($usuario) {
            if (!empty($usuario->password)) {
                $usuario->password = bcrypt($usuario->password);
            }
        });
        
        static::updating(function ($usuario) {
            // Siempre hashear si se proporciona una nueva contraseña
            if (!empty($usuario->password)) {
                $usuario->password = bcrypt($usuario->password);
            }
        });
    }
}
