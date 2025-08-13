<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
      use HasFactory;

    protected $table = 'usuarios';

    protected $primaryKey = 'id'; // Laravel ya asume 'id' por defecto, pero lo puedes declarar si gustas

    protected $fillable = [
        'nombre',
        'apellidoPaterno',
        'apellidoMaterno',
        'ci',
        'telefono',
        'direccion',
        'email',
        'fechaIngreso',
        'fechaFinal',
        'estado',
        'idRol'
    ];

    public $timestamps = true;

    // 🔗 Relación: Un usuarios pertenece a roles
    public function rol()
    {
        return $this->belongsTo(Roles::class, 'idRol');
    }
}
