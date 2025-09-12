<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleTrabajo extends Model
{
    use HasFactory;

    protected $table = 'detalle_trabajo';

    protected $primaryKey = 'id';

    protected $fillable = [
        'idTrabajo',
        'idServicio',
        'idPago',
        'descripcion',
        'tamano',
        'color',
        'modelo',
        'cantidad',
        'descuento',
        'tipoDocumento',
        'tipoEvento',
    ];

    public $timestamps = true;

    // 🔗 Relación: Cada detalle pertenece a un trabajo
    public function trabajo()
    {
        return $this->belongsTo(Trabajos::class, 'idTrabajo');
    }

    // 🔗 Relación: Cada detalle pertenece a un servicio
    public function servicio()
    {
        return $this->belongsTo(Servicios::class, 'idServicio');
    }

    // 🔗 Relación: Cada detalle pertenece a un pago
    public function pago()
    {
        return $this->belongsTo(Pagos::class, 'idPago');
    }
}
