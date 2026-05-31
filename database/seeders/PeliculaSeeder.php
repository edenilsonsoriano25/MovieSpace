<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelicula;

class PeliculaSeeder extends Seeder
{
    public function run()
    {
        Pelicula::create([
            'titulo' => 'El Padrino',
            'sinopsis' => 'La historia de la familia Corleone',
            'genero' => 'Drama',
            'anio' => 1972,
            'director' => 'Francis Ford Coppola',
            'precio_alquiler' => 2.50,
            'copias_totales' => 5,
            'copias_en_estante' => 3,
            'estado' => 'disponible'
        ]);
        
        Pelicula::create([
            'titulo' => 'Titanic',
            'sinopsis' => 'Historia de amor en el barco',
            'genero' => 'Romance',
            'anio' => 1997,
            'director' => 'James Cameron',
            'precio_alquiler' => 2.00,
            'copias_totales' => 3,
            'copias_en_estante' => 2,
            'estado' => 'disponible'
        ]);
        
        Pelicula::create([
            'titulo' => 'Inception',
            'sinopsis' => 'Viaje a través de los sueños',
            'genero' => 'Ciencia Ficción',
            'anio' => 2010,
            'director' => 'Christopher Nolan',
            'precio_alquiler' => 3.00,
            'copias_totales' => 4,
            'copias_en_estante' => 4,
            'estado' => 'disponible'
        ]);
    }
}