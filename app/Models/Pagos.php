<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pagos extends Model
{
    use HasFactory;
    
    protected $table = 'pagos';
    protected $primaryKey = 'idPago';
    
    // Desactivar timestamps ya que la tabla no tiene created_at y updated_at
    public $timestamps = false;
    
    protected $fillable = [
        'idTrabajo',
        'total',
        'aCuenta', 
        'saldo',
        'idEstadoPago'
    ];

    // 🔗 Relación: Un pago pertenece a un trabajo
    public function trabajo(): BelongsTo
    {
        return $this->belongsTo(Trabajos::class, 'idTrabajo');
    }

    // 🔗 Relación: Un pago tiene un estado de pago
    public function estadoPago(): BelongsTo
    {
        return $this->belongsTo(EstadoPago::class, 'idEstadoPago');
    }

    /**
     * Calcular el pago y determinar el estado automáticamente
     */
    public static function calcularPago($idTrabajo, $montoPagado)
    {
        // Obtener el trabajo con sus relaciones
        $trabajo = Trabajos::with(['servicio', 'detalleTrabajo'])->find($idTrabajo);
        
        if (!$trabajo) {
            throw new \Exception('Trabajo no encontrado');
        }

        // Calcular el total del trabajo: precioReferencial × cantidad
        $total = $trabajo->servicio->precioReferencial * $trabajo->detalleTrabajo->cantidad;
        
        // Obtener pagos anteriores del mismo trabajo
        $pagosAnteriores = self::where('idTrabajo', $idTrabajo)->sum('aCuenta');
        
        // Calcular el nuevo saldo
        $nuevoSaldo = $total - $pagosAnteriores - $montoPagado;
        
        // Determinar el estado automáticamente
        $estado = self::determinarEstado($nuevoSaldo, $montoPagado);
        
        return [
            'total' => $total,
            'aCuenta' => $montoPagado,
            'saldo' => $nuevoSaldo,
            'idEstadoPago' => $estado
        ];
    }

    /**
     * Determinar el estado del pago basado en el saldo y monto pagado
     */
    private static function determinarEstado($saldo, $montoPagado)
    {
        if ($saldo == 0) {
            return 3; // Completado
        } elseif ($montoPagado == 0) {
            return 4; // Cancelado
        } else {
            return 2; // Parcial
        }
    }

    /**
     * Verificar si el pago está completo
     */
    public function getPagoCompletoAttribute(): bool
    {
        return $this->saldo == 0;
    }

    /**
     * Obtener el saldo pendiente del trabajo
     */
    public static function obtenerSaldoPendiente($idTrabajo): float
    {
        $trabajo = Trabajos::with(['servicio', 'detalleTrabajo'])->find($idTrabajo);
        
        if (!$trabajo) {
            return 0;
        }

        $total = $trabajo->servicio->precioReferencial * $trabajo->detalleTrabajo->cantidad;
        $pagosRealizados = self::where('idTrabajo', $idTrabajo)->sum('aCuenta');
        
        return $total - $pagosRealizados;
    }
}
