<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadosTrabajo extends Model
{
    use HasFactory;

    protected $table = 'estados_trabajo'; 
    protected $fillable = ['nombre']; 

    
    public $timestamps = true;
}
