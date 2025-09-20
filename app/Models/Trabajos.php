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
        'idUsuario',
        'idResponsable',
        'fechaRegistro',
        'fechaEntrega',
        'idEstado',
        'idEstadoPago',
        'slug',
    ];

    public $timestamps = true;

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

    // 🔗 Relación: Un trabajo pertenece a un usuario
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuarios::class, 'idUsuario');
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


    
}
