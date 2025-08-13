<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionesTrabajo extends Model
{
      use HasFactory;

    protected $table = 'asignaciones_trabajos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'idTrabajo',
        'idUsuario',
        'turno',
        'fechaAsignacion',
    ];

    public $timestamps = true;

    // 🔗 Relación: Una asignación pertenece a un trabajo
    public function trabajo()
    {
        return $this->belongsTo(Trabajos::class, 'idTrabajo');
    }

    // 🔗 Relación: Una asignación pertenece a un usuario
    public function usuarioEncargado()
    {
        return $this->belongsTo(Usuarios::class, 'idUsuarioEncargado');
    }
}
