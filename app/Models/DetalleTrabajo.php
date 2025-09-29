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

    // 💰 MÉTODOS DE CÁLCULO (Nuevos - Compatibles con frontend existente)
    
    /**
     * Calcular el subtotal bruto (precio × cantidad)
     */
    public function calcularSubtotalBruto(): float
    {
        if (!$this->servicio) {
            return 0;
        }
        
        $precio = (float) $this->servicio->precioReferencial;
        $cantidad = (int) $this->cantidad;
        
        return $precio * $cantidad;
    }
    
    /**
     * Calcular el subtotal final (después del descuento)
     */
    public function calcularSubtotal(): float
    {
        $subtotalBruto = $this->calcularSubtotalBruto();
        $descuento = (float) ($this->descuento ?? 0);
        
        return max(0, $subtotalBruto - $descuento);
    }
    
    /**
     * Validar que el descuento no exceda el subtotal bruto
     */
    public function validarDescuento(): void
    {
        $subtotalBruto = $this->calcularSubtotalBruto();
        $descuento = (float) ($this->descuento ?? 0);
        
        if ($descuento >= $subtotalBruto && $subtotalBruto > 0) {
            $this->descuento = $subtotalBruto - 0.01;
        }
    }
    
    /**
     * Obtener el precio unitario del servicio
     */
    public function getPrecioUnitario(): float
    {
        if (!$this->servicio) {
            return 0;
        }
        
        return (float) $this->servicio->precioReferencial;
    }
}
