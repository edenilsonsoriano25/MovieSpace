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

    // Verificar si está retrasado basado únicamente en días calendario enteros
    public function estaRetrasado()
    {
        return $this->estado_prestamo === 'activo' && Carbon::now()->startOfDay()->gt($this->fecha_limite);
    }

    // Calcular días de retraso absolutos forzando enteros positivos sin residuos horarios
    public function diasRetraso()
    {
        if (!$this->estaRetrasado()) {
            return 0;
        }
        return Carbon::now()->startOfDay()->diffInDays($this->fecha_limite, true);
    }

    /**
     * Accessor dinámico para multa_total
     * Calcula de forma automática 1.00 dolar por cada día de retraso exacto.
     */
    public function getMultaTotalAttribute()
    {
        // Si el préstamo ya no está activo (ej. devuelto), devolvemos el valor estático de la base de datos
        if ($this->estado_prestamo !== 'activo') {
            return $this->attributes['multa_total'] ?? 0;
        }

        // Si está activo pero aún no ha pasado la fecha límite, la multa es cero
        if (!$this->estaRetrasado()) {
            return 0;
        }

        // 1 dolar por cada día de retraso entero
        $costoPorDia = 1.00;
        return $this->diasRetraso() * $costoPorDia;
    }
}
