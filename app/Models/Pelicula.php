<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    use HasFactory;

    protected $table = 'peliculas';


    protected $fillable = [
        'titulo',
        'sinopsis',
        'genero',
        'año',
        'director',
        'portada',
        'precio_alquiler',
        'copias_totales',
        'copias_en_estante',
        'estado'
    ];

    // Relación con detalle_prestamos
    public function detallePrestamos()
    {
        return $this->hasMany(DetallePrestamo::class, 'id_pelicula');
    }
}
