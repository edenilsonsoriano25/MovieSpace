<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamos';

    protected $fillable = [
        'id_usuario',
        'id_trabajador',
        'fecha_salida',
        'fecha_limite',
        'fecha_entrega_real',
        'estado_prestamo',
        'multa_total'
    ];

    protected $casts = [
        'fecha_salida' => 'date',
        'fecha_limite' => 'date',
        'fecha_entrega_real' => 'date',
    ];

    // Relación con el usuario (Cliente)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Relación con el trabajador (Puede ser NULL)
    public function trabajador()
    {
        return $this->belongsTo(User::class, 'id_trabajador');
    }

    // Relación con los detalles
    public function detalles()
    {
        return $this->hasMany(DetallePrestamo::class, 'id_prestamo');
    }

    // Relación con pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_prestamo');
    }

    // Verificar si está retrasado
    public function estaRetrasado()
    {
        return $this->estado_prestamo === 'activo' && Carbon::now()->gt($this->fecha_limite);
    }

    // Calcular días de retraso
    public function diasRetraso()
    {
        if (!$this->estaRetrasado()) {
            return 0;
        }
        return Carbon::now()->diffInDays($this->fecha_limite);
    }
}