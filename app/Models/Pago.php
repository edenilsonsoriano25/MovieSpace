<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    
    protected $table = 'pagos';
    
    protected $fillable = [
        'id_prestamo',
        'id_usuario',
        'monto',
        'concepto',
        'metodo_pago',
        'fecha_pago'
    ];
    
    protected $casts = [
        'fecha_pago' => 'date'
    ];
    
    // Relación con préstamo
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'id_prestamo');
    }
    
    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}