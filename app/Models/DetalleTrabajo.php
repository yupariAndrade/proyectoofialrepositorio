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
        'descripcion',
        'tamano',
        'color',
        'modelo',
        'cantidad',
        'tipoDocumento',
        'tipoEvento',
        'otros',
    ];

    public $timestamps = true;

    // 🔗 Relación: Cada detalle pertenece a un trabajo
    public function trabajo()
    {
        return $this->belongsTo(Trabajos::class, 'idTrabajo');
    }
}
