<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraTrabajo extends Model
{
       use HasFactory;

    protected $table = 'bitacora_trabajos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'idTrabajo',
        'idUsuario',
        'accion',
        'fecha',
        'descripcion',
    ];

    public $timestamps = true;

    // 🔗 Relación: La bitácora pertenece a un trabajo
    public function trabajo()
    {
        return $this->belongsTo(Trabajos::class, 'idTrabajo');
    }

    // 🔗 Relación: La bitácora pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'idUsuario');
    }
}
