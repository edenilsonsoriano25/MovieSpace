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
    
    // Relación con usuario (cliente)
    public function usuario()
    {
         if (auth()->user()->rol === 'cliente') {
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
        return $this->belongsTo(User::class, 'id_usuario');
    }
    
    // Relación con trabajador
    public function trabajador()
    {
         if (auth()->user()->rol === 'cliente') {
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
        return $this->belongsTo(User::class, 'id_trabajador');
    }
    
    // Relación con detalles
    public function detalles()
    {
         if (auth()->user()->rol === 'cliente') {
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
        return $this->hasMany(DetallePrestamo::class, 'id_prestamo');
    }
    
    // Relación con pagos
    public function pagos()
    {
         if (auth()->user()->rol === 'cliente') {
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
        return $this->hasMany(Pago::class, 'id_prestamo');
    }
    
    // Verificar si está retrasado
    public function estaRetrasado()
    {
         if (auth()->user()->rol === 'cliente') {
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
        return $this->estado_prestamo === 'activo' && Carbon::now()->gt($this->fecha_limite);
    }
    
    // Calcular días de retraso
    public function diasRetraso()
    {
         if (auth()->user()->rol === 'cliente') {
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
        if (!$this->estaRetrasado()) {
            return 0;
        }
        return Carbon::now()->diffInDays($this->fecha_limite);
    }
}