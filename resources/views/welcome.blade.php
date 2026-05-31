@extends('layouts.app')

@section('title', 'Bienvenido a MovieSpace')

@section('content')
<div class="container">
    <div class="hero" style="text-align: center; color: white; padding: 4rem 0;">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">
            🎬 MovieSpace
        </h1>
        <p style="font-size: 1.2rem; margin-bottom: 2rem;">
            Tu tienda de alquiler de películas físicas de confianza
        </p>
        <a href="{{ route('peliculas.index') }}" class="btn-register" style="font-size: 1.2rem;">
            Ver Catálogo
        </a>
    </div>
    
    <h2 style="color: white; text-align: center; margin: 2rem 0;">Películas Destacadas</h2>
    
    <div class="movies-grid">
        @foreach($destacadas as $pelicula)
        <div class="movie-card">
            <div class="movie-image">
                <i class="fas fa-film"></i>
            </div>
            <div class="movie-info">
                <h3 class="movie-title">{{ $pelicula->titulo }}</h3>
                <div class="movie-genre">{{ $pelicula->genero }}</div>
                <div class="movie-price">${{ number_format($pelicula->precio_alquiler, 2) }}</div>
                <a href="{{ route('peliculas.index') }}" class="btn-alquilar">Ver más</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection