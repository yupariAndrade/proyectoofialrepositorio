<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Trabajos extends Model
{
    use HasFactory;
    protected $table = 'trabajos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idCliente',
        'idResponsable',
        'fechaRegistro',
        'fechaEntrega',
        'idEstado',
        'idEstadoPago',
        'observaciones',
        'slug',
    ];

    public $timestamps = true;

    protected $casts = [
        'fechaRegistro' => 'date',
        'fechaEntrega' => 'date',
    ];

    // 🎯 Método para generar slug automáticamente
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($trabajo) {
            if (empty($trabajo->slug)) {
                $trabajo->slug = $trabajo->generateSlug();
            }
        });
        
        static::updating(function ($trabajo) {
            if (empty($trabajo->slug)) {
                $trabajo->slug = $trabajo->generateSlug();
            }
        });
    }

    // 🔧 Método para generar slug único
    public function generateSlug()
    {
        $cliente = $this->cliente;
        
        $baseSlug = Str::slug(
            ($cliente ? $cliente->nombre . ' ' . $cliente->apellido : 'trabajo') . 
            '-trabajo-' . 
            $this->id
        );
        
        $slug = $baseSlug;
        $counter = 1;
        
        // Asegurar que el slug sea único
        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    // 🔗 Relación: Un trabajo pertenece a un cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Clientes::class, 'idCliente');
    }

    // 🔗 Relación: Un trabajo pertenece a un usuario responsable
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Usuarios::class, 'idResponsable');
    }


    // 🔗 Relación: Un trabajo tiene un estado
    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadosTrabajo::class, 'idEstado');
    }

    // 🔗 Relación: Un trabajo tiene un estado de pago
    public function estadoPago(): BelongsTo
    {
        return $this->belongsTo(EstadoPago::class, 'idEstadoPago');
    }

    // 🔗 Relación: Un trabajo puede tener múltiples detalles (servicios)
    public function detallesTrabajo(): HasMany
    {
        return $this->hasMany(DetalleTrabajo::class, 'idTrabajo');
    }

    // 🔗 Relación: Un trabajo puede tener múltiples pagos
    public function pagos()
    {
        return $this->hasMany(Pagos::class, 'idTrabajo');
    }

    // 💰 MÉTODOS DE CÁLCULO (Nuevos - Compatibles con frontend existente)
    
    /**
     * Calcular el total del trabajo (suma de todos los subtotales)
     */
    public function calcularTotal(): float
    {
        return $this->detallesTrabajo->sum(function($detalle) {
            return $detalle->calcularSubtotal();
        });
    }
    
    /**
     * Calcular el total pagado (suma de todos los pagos)
     */
    public function calcularTotalPagado(): float
    {
        return $this->pagos->sum('monto');
    }
    
    /**
     * Calcular el saldo pendiente
     */
    public function calcularSaldo(): float
    {
        $total = $this->calcularTotal();
        $pagado = $this->calcularTotalPagado();
        
        return max(0, $total - $pagado);
    }
    
    /**
     * Determinar el estado de pago basado en el saldo
     */
    public function determinarEstadoPago(): int
    {
        $saldo = $this->calcularSaldo();
        $pagado = $this->calcularTotalPagado();
        
        if ($saldo == 0) {
            return 3; // Completado
        } elseif ($pagado == 0) {
            return 4; // Cancelado
        } else {
            return 2; // Parcial
        }
    }
    
    /**
     * Verificar si el trabajo está completamente pagado
     */
    public function estaCompletamentePagado(): bool
    {
        return $this->calcularSaldo() == 0;
    }
    
    /**
     * Obtener el porcentaje de pago
     */
    public function getPorcentajePago(): float
    {
        $total = $this->calcularTotal();
        $pagado = $this->calcularTotalPagado();
        
        if ($total == 0) {
            return 0;
        }
        
        return ($pagado / $total) * 100;
    }
}
