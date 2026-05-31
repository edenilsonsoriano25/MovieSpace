<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelicula;

class PeliculasMasivasSeeder extends Seeder
{
    public function run()
    {
        $peliculas = [
            // ACCIÓN
            ['titulo' => 'John Wick 4', 'sinopsis' => 'El legendario asesino a sueldo regresa para vengarse', 'genero' => 'Acción', 'anio' => 2023, 'director' => 'Chad Stahelski', 'precio_alquiler' => 3.50, 'copias_totales' => 5, 'copias_en_estante' => 5, 'estado' => 'disponible'],
            ['titulo' => 'Misión Imposible: Sentencia Mortal', 'sinopsis' => 'Ethan Hunt y su equipo enfrentan su misión más peligrosa', 'genero' => 'Acción', 'anio' => 2023, 'director' => 'Christopher McQuarrie', 'precio_alquiler' => 3.50, 'copias_totales' => 4, 'copias_en_estante' => 4, 'estado' => 'disponible'],
            ['titulo' => 'Fast X', 'sinopsis' => 'La décima entrega de la saga Rápidos y Furiosos', 'genero' => 'Acción', 'anio' => 2023, 'director' => 'Louis Leterrier', 'precio_alquiler' => 3.00, 'copias_totales' => 6, 'copias_en_estante' => 6, 'estado' => 'disponible'],
            ['titulo' => 'The Dark Knight', 'sinopsis' => 'Batman enfrenta al Joker', 'genero' => 'Acción', 'anio' => 2008, 'director' => 'Christopher Nolan', 'precio_alquiler' => 2.50, 'copias_totales' => 8, 'copias_en_estante' => 8, 'estado' => 'disponible'],
            ['titulo' => 'Gladiador', 'sinopsis' => 'Un general romano busca venganza', 'genero' => 'Acción', 'anio' => 2000, 'director' => 'Ridley Scott', 'precio_alquiler' => 2.50, 'copias_totales' => 5, 'copias_en_estante' => 5, 'estado' => 'disponible'],
            
            // COMEDIA
            ['titulo' => 'Barbie', 'sinopsis' => 'Barbie vive en Barbieland y tiene una crisis existencial', 'genero' => 'Comedia', 'anio' => 2023, 'director' => 'Greta Gerwig', 'precio_alquiler' => 3.50, 'copias_totales' => 7, 'copias_en_estante' => 7, 'estado' => 'disponible'],
            ['titulo' => 'Super Mario Bros', 'sinopsis' => 'Mario y Luigi salvan el Reino Champiñón', 'genero' => 'Comedia', 'anio' => 2023, 'director' => 'Aaron Horvath', 'precio_alquiler' => 3.00, 'copias_totales' => 8, 'copias_en_estante' => 8, 'estado' => 'disponible'],
            ['titulo' => 'Son como niños', 'sinopsis' => 'Un grupo de amigos se reencuentra después de años', 'genero' => 'Comedia', 'anio' => 2010, 'director' => 'Dennis Dugan', 'precio_alquiler' => 2.00, 'copias_totales' => 4, 'copias_en_estante' => 4, 'estado' => 'disponible'],
            
            // DRAMA
            ['titulo' => 'Oppenheimer', 'sinopsis' => 'La historia del padre de la bomba atómica', 'genero' => 'Drama', 'anio' => 2023, 'director' => 'Christopher Nolan', 'precio_alquiler' => 3.50, 'copias_totales' => 6, 'copias_en_estante' => 6, 'estado' => 'disponible'],
            ['titulo' => 'El Padrino', 'sinopsis' => 'La historia de la familia Corleone', 'genero' => 'Drama', 'anio' => 1972, 'director' => 'Francis Ford Coppola', 'precio_alquiler' => 2.50, 'copias_totales' => 5, 'copias_en_estante' => 5, 'estado' => 'disponible'],
            ['titulo' => 'Cadena Perpetua', 'sinopsis' => 'Un banquero es condenado a cadena perpetua', 'genero' => 'Drama', 'anio' => 1994, 'director' => 'Frank Darabont', 'precio_alquiler' => 2.50, 'copias_totales' => 4, 'copias_en_estante' => 4, 'estado' => 'disponible'],
            ['titulo' => 'La La Land', 'sinopsis' => 'Un músico y una actriz se enamoran en Los Ángeles', 'genero' => 'Drama', 'anio' => 2016, 'director' => 'Damien Chazelle', 'precio_alquiler' => 2.00, 'copias_totales' => 4, 'copias_en_estante' => 4, 'estado' => 'disponible'],
            
            // CIENCIA FICCIÓN
            ['titulo' => 'Dune 2', 'sinopsis' => 'Paul Atreides se une a los Fremen para vengar a su familia', 'genero' => 'Ciencia Ficción', 'anio' => 2024, 'director' => 'Denis Villeneuve', 'precio_alquiler' => 4.00, 'copias_totales' => 6, 'copias_en_estante' => 6, 'estado' => 'disponible'],
            ['titulo' => 'Avatar: El Camino del Agua', 'sinopsis' => 'Los Na'vi enfrentan una nueva amenaza', 'genero' => 'Ciencia Ficción', 'anio' => 2022, 'director' => 'James Cameron', 'precio_alquiler' => 3.50, 'copias_totales' => 7, 'copias_en_estante' => 7, 'estado' => 'disponible'],
            ['titulo' => 'Interestelar', 'sinopsis' => 'Un grupo de astronautas viaja por un agujero de gusano', 'genero' => 'Ciencia Ficción', 'anio' => 2014, 'director' => 'Christopher Nolan', 'precio_alquiler' => 2.50, 'copias_totales' => 5, 'copias_en_estante' => 5, 'estado' => 'disponible'],
            ['titulo' => 'Matrix', 'sinopsis' => 'Un programador descubre la realidad', 'genero' => 'Ciencia Ficción', 'anio' => 1999, 'director' => 'Hermanas Wachowski', 'precio_alquiler' => 2.00, 'copias_totales' => 4, 'copias_en_estante' => 4, 'estado' => 'disponible'],
            
            // TERROR
            ['titulo' => 'Five Nights at Freddy\'s', 'sinopsis' => 'Un guardia de seguridad enfrenta animatrónicos poseídos', 'genero' => 'Terror', 'anio' => 2023, 'director' => 'Emma Tammi', 'precio_alquiler' => 3.00, 'copias_totales' => 5, 'copias_en_estante' => 5, 'estado' => 'disponible'],
            ['titulo' => 'El Conjuro', 'sinopsis' => 'Investigadores paranormales ayudan a una familia', 'genero' => 'Terror', 'anio' => 2013, 'director' => 'James Wan', 'precio_alquiler' => 2.00, 'copias_totales' => 4, 'copias_en_estante' => 4, 'estado' => 'disponible'],
            ['titulo' => 'IT', 'sinopsis' => 'Un payaso aterroriza a un grupo de niños', 'genero' => 'Terror', 'anio' => 2017, 'director' => 'Andy Muschietti', 'precio_alquiler' => 2.50, 'copias_totales' => 5, 'copias_en_estante' => 5, 'estado' => 'disponible'],
            
            // ROMANCE
            ['titulo' => 'Titanic', 'sinopsis' => 'Historia de amor en el barco más famoso', 'genero' => 'Romance', 'anio' => 1997, 'director' => 'James Cameron', 'precio_alquiler' => 2.00, 'copias_totales' => 6, 'copias_en_estante' => 6, 'estado' => 'disponible'],
            ['titulo' => 'Bajo la misma estrella', 'sinopsis' => 'Dos adolescentes con cáncer se enamoran', 'genero' => 'Romance', 'anio' => 2014, 'director' => 'Josh Boone', 'precio_alquiler' => 2.00, 'copias_totales' => 4, 'copias_en_estante' => 4, 'estado' => 'disponible'],
            
            // ANIMACIÓN
            ['titulo' => 'Spider-Man: Cruzando el Multiverso', 'sinopsis' => 'Miles Morales se encuentra con otros Spider-Mans', 'genero' => 'Animación', 'anio' => 2023, 'director' => 'Joaquim Dos Santos', 'precio_alquiler' => 3.50, 'copias_totales' => 8, 'copias_en_estante' => 8, 'estado' => 'disponible'],
            ['titulo' => 'Elementos', 'sinopsis' => 'Una historia de amor entre fuego y agua', 'genero' => 'Animación', 'anio' => 2023, 'director' => 'Peter Sohn', 'precio_alquiler' => 3.00, 'copias_totales' => 7, 'copias_en_estante' => 7, 'estado' => 'disponible'],
            ['titulo' => 'Toy Story 4', 'sinopsis' => 'Woody y Buzz emprenden una nueva aventura', 'genero' => 'Animación', 'anio' => 2019, 'director' => 'Josh Cooley', 'precio_alquiler' => 2.50, 'copias_totales' => 6, 'copias_en_estante' => 6, 'estado' => 'disponible'],
            ['titulo' => 'Coco', 'sinopsis' => 'Un niño viaja a la tierra de los muertos', 'genero' => 'Animación', 'anio' => 2017, 'director' => 'Lee Unkrich', 'precio_alquiler' => 2.50, 'copias_totales' => 5, 'copias_en_estante' => 5, 'estado' => 'disponible'],
            
            // SUPERHÉROES
            ['titulo' => 'Spider-Man: Sin Camino a Casa', 'sinopsis' => 'Peter Parker pide ayuda al Doctor Strange', 'genero' => 'Superhéroes', 'anio' => 2021, 'director' => 'Jon Watts', 'precio_alquiler' => 3.00, 'copias_totales' => 7, 'copias_en_estante' => 7, 'estado' => 'disponible'],
            ['titulo' => 'Avengers: Endgame', 'sinopsis' => 'Los Vengadores intentan revertir el chasquido de Thanos', 'genero' => 'Superhéroes', 'anio' => 2019, 'director' => 'Hermanos Russo', 'precio_alquiler' => 3.00, 'copias_totales' => 8, 'copias_en_estante' => 8, 'estado' => 'disponible'],
            ['titulo' => 'Black Panther', 'sinopsis' => 'TChalla regresa a Wakanda', 'genero' => 'Superhéroes', 'anio' => 2018, 'director' => 'Ryan Coogler', 'precio_alquiler' => 2.50, 'copias_totales' => 5, 'copias_en_estante' => 5, 'estado' => 'disponible'],
            ['titulo' => 'Joker', 'sinopsis' => 'Origen del famoso villano', 'genero' => 'Superhéroes', 'anio' => 2019, 'director' => 'Todd Phillips', 'precio_alquiler' => 2.50, 'copias_totales' => 5, 'copias_en_estante' => 5, 'estado' => 'disponible'],
        ];
        
        foreach ($peliculas as $pelicula) {
            Pelicula::create($pelicula);
        }
        
        $this->command->info('🎬 ¡' . count($peliculas) . ' películas agregadas exitosamente!');
    }
}