<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
       use HasFactory;
    protected $table = 'pagos';
    protected $primaryKey = 'idPago';

    protected $fillable = [
        'idTrabajo',
        'monto',
        'fecha',
        'comentario',
        'idEstadoPago',
    ];

    public $timestamps = true;

    // 🔗 Relación: Un pago pertenece a un trabajo
    public function trabajo()
    {
        return $this->belongsTo(Trabajos::class, 'idTrabajo');
    }

    // 🔗 Relación: Un pago tiene un estado de pago
    public function estadoPago()
    {
        return $this->belongsTo(EstadoPago::class, 'idEstadoPago');
    }
}
