<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePrestamo extends Model
{
    use HasFactory;
    
    protected $table = 'detalle_prestamos';
    
    protected $fillable = [
        'id_prestamo',
        'id_pelicula',
        'precio_alquiler_momento',
        'monto_multa'
    ];
    
    // Relación con préstamo
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'id_prestamo');
    }
    
    // Relación con película
    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class, 'id_pelicula');
    }
}