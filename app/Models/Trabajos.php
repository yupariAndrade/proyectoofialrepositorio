<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajos extends Model
{
    use HasFactory;
    protected $table = 'trabajos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idCliente',
        'idServicio',
        'idUsuario',
        'fechaRegistro',
        'fechaEntrega',
        'idEstado',
    ];

    public $timestamps = true;

    // 🔗 Relación: Un trabajo pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'idCliente');
    }

    // 🔗 Relación: Un trabajo pertenece a un servicio
    public function servicio()
    {
        return $this->belongsTo(Servicios::class, 'idServicio');
    }

    // 🔗 Relación: Un trabajo pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'idUsuario');
    }

    // 🔗 Relación: Un trabajo tiene un estado
    public function estado()
    {
        return $this->belongsTo(EstadosTrabajo::class, 'idEstado');
    }
}
