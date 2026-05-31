@extends('layouts.app')

@section('title', 'Catálogo de Películas')

@section('content')
<div class="container">
    <h1 style="color: white; margin-bottom: 2rem;">
        <i class="fas fa-film"></i> Catálogo de Películas
    </h1>
    
    <!-- Buscador en tiempo real -->
    <div class="search-box">
        <input type="text" id="search" placeholder="🔍 Buscar películas por título, género o director..." 
               onkeyup="buscarPeliculas()">
    </div>
    
    <div id="movies-container">
        <div class="movies-grid">
            @foreach($peliculas as $pelicula)
            <div class="movie-card">
                <div class="movie-image">
                    <i class="fas fa-video"></i>
                </div>
                <div class="movie-info">
                    <h3 class="movie-title">{{ $pelicula->titulo }}</h3>
                    <div class="movie-genre">
                        <i class="fas fa-tag"></i> {{ $pelicula->genero }}
                    </div>
                    <div class="movie-price">
                        ${{ number_format($pelicula->precio_alquiler, 2) }}
                    </div>
                    <div>
                        @if($pelicula->copias_en_estante > 0)
                            <span class="movie-stock stock-available">
                                <i class="fas fa-check"></i> {{ $pelicula->copias_en_estante }} copias disponibles
                            </span>
                        @else
                            <span class="movie-stock stock-out">
                                <i class="fas fa-times"></i> Agotado
                            </span>
                        @endif
                    </div>
                    
                    @auth
                        @if(auth()->user()->rol === 'cliente')
                            <a href="{{ route('alquilar', $pelicula) }}" class="btn-alquilar"
                               {{ $pelicula->copias_en_estante == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart"></i> Alquilar
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-alquilar">
                            <i class="fas fa-sign-in-alt"></i> Inicia sesión para alquilar
                        </a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
function buscarPeliculas() {
    let query = document.getElementById('search').value;
    
    fetch(`/buscar-peliculas?search=${query}`)
        .then(response => response.json())
        .then(data => {
            let container = document.getElementById('movies-container');
            container.innerHTML = '';
            
            if(data.length === 0) {
                container.innerHTML = '<p style="color: white; text-align: center;">No se encontraron películas</p>';
                return;
            }
            
            let html = '<div class="movies-grid">';
            data.forEach(pelicula => {
                html += `
                    <div class="movie-card">
                        <div class="movie-image">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="movie-info">
                            <h3 class="movie-title">${pelicula.titulo}</h3>
                            <div class="movie-genre">${pelicula.genero}</div>
                            <div class="movie-price">$${pelicula.precio_alquiler}</div>
                            <div>
                                ${pelicula.copias_en_estante > 0 ? 
                                    `<span class="movie-stock stock-available">${pelicula.copias_en_estante} disponibles</span>` :
                                    `<span class="movie-stock stock-out">Agotado</span>`
                                }
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            container.innerHTML = html;
        });
}
</script>
@endpush
@endsection