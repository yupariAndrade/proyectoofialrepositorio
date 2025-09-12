<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

class Usuarios extends Authenticatable
{
      use HasFactory, Notifiable;

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
        'idRol'
    ];

    public $timestamps = true;

    /**
     * Atributos que se agregan automáticamente al serializar
     */
    protected $appends = [
        'fullName',
        'isAdmin',
        'isEmpleado',
        'isGerente',
        'isEncargado'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'fechaIngreso' => 'date',
            'fechaFinal' => 'date',
            'estado' => 'boolean',
            'password' => 'hashed',
        ];
    }

    // 🔗 Relación: Un usuarios pertenece a roles
    public function rol()
    {
        return $this->belongsTo(Roles::class, 'idRol');
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole($role)
    {
        return $this->rol && strtolower($this->rol->nombre) === strtolower($role);
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function getIsAdminAttribute()
    {
        return $this->hasRole('administrador');
    }

    /**
     * Verificar si el usuario es empleado
     */
    public function getIsEmpleadoAttribute()
    {
        return $this->hasRole('empleado');
    }

    /**
     * Verificar si el usuario es gerente
     */
    public function getIsGerenteAttribute()
    {
        return $this->hasRole('gerente');
    }

    /**
     * Verificar si el usuario es encargado
     */
    public function getIsEncargadoAttribute()
    {
        return $this->hasRole('encargado');
    }

    /**
     * Obtener el nombre completo del usuario
     */
    public function getFullNameAttribute()
    {
        return trim($this->nombre . ' ' . ($this->apellidoPaterno ?? '') . ' ' . ($this->apellidoMaterno ?? ''));
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
     * Reglas de validación para evitar duplicados
     */
    public static function getValidationRules($userId = null)
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:50',
                'min:2',
                Rule::unique('usuarios')->ignore($userId)
            ],
            'apellidoPaterno' => [
                'nullable',
                'string',
                'max:50',
                'min:2'
            ],
            'apellidoMaterno' => [
                'nullable',
                'string',
                'max:50',
                'min:2'
            ],
            'ci' => [
                'nullable',
                'string',
                'max:20',
                'min:5',
                'regex:/^[0-9]+$/', // Solo números
                Rule::unique('usuarios')->ignore($userId)
            ],
            'telefono' => [
                'nullable',
                'string',
                'max:20',
                'min:7',
                'regex:/^[0-9+\-\s()]+$/', // Números, +, -, espacios, ()
                Rule::unique('usuarios')->ignore($userId)
            ],
            'direccion' => [
                'nullable',
                'string',
                'max:50',
                'min:5'
            ],
            'email' => [
                'nullable',
                'email',
                'max:150',
                'min:5',
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
}
