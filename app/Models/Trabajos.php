<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

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
        $servicio = $this->servicio;
        
        $baseSlug = Str::slug(
            ($cliente ? $cliente->nombre . ' ' . $cliente->apellido : 'trabajo') . 
            '-' . 
            ($servicio ? $servicio->nombreServicio : 'servicio') . 
            '-' . 
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

    // 🔗 Relación: Un trabajo pertenece a un servicio
    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicios::class, 'idServicio');
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

    // 🔗 Relación: Un trabajo tiene un detalle
    public function detalleTrabajo(): HasOne
    {
        return $this->hasOne(DetalleTrabajo::class, 'idTrabajo');
    }

    // 🔗 Relación: Un trabajo puede tener múltiples pagos
    public function pagos()
    {
        return $this->hasMany(Pagos::class, 'idTrabajo');
    }
}
