<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
