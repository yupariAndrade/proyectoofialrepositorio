<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Roles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Especificar la tabla personalizada
     */
    protected $table = 'usuarios';

    /**
     * Especificar la clave primaria personalizada
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
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

    /**
     * Obtener el nombre completo del usuario
     */
    public function getFullNameAttribute()
    {
        return trim($this->nombre . ' ' . ($this->apellidoPaterno ?? '') . ' ' . ($this->apellidoMaterno ?? ''));
    }

    /**
     * Relación con el rol
     */
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
}
