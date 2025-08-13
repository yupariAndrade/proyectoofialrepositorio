<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
