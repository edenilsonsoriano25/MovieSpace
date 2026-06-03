@extends('layouts.app')

@section('title', 'Catálogo de Películas')

@section('content')
<div class="catalog-dark-wrapper">
    <div class="container-fluid px-4 px-md-5">

        <div class="catalog-header">
            <h1 class="catalog-title">
                <i class="fas fa-film"></i> Catálogo de Películas
            </h1>
            <p class="catalog-subtitle">Busca tus títulos favoritos y verifica la disponibilidad de copias físicas en nuestra sucursal de Santa Tecla.</p>

            <div class="search-box-container">
                <span class="search-icon"><i class="fas fa-search"></i></span>
                <input type="text" id="search" placeholder="🔍 Buscar películas por título, género o director..." onkeyup="buscarPeliculas()">
            </div>
        </div>

        <div id="movies-container">
            <div class="movies-streaming-grid">
                @foreach($peliculas as $pelicula)
                <div class="movie-premium-card">

                    <div class="movie-premium-poster">
                        @if($pelicula->portada)
                        <img src="{{ $pelicula->portada }}" alt="Portada de {{ $pelicula->titulo }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 14px 14px 0 0;">
                        @else
                        <i class="fas fa-video"></i>
                        @endif
                        <span class="movie-premium-price">${{ number_format($pelicula->precio_alquiler, 2) }}</span>
                    </div>

                    <div class="movie-premium-body">
                        <span class="movie-premium-genre">
                            <i class="fas fa-tag"></i> {{ $pelicula->genero }}
                        </span>
                        <h3 class="movie-premium-title" title="{{ $pelicula->titulo }}">
                            {{ $pelicula->titulo }}
                        </h3>

                        <div class="movie-premium-stock-box">
                            @if($pelicula->copias_en_estante > 0)
                            <span class="stock-dot dot-available"></span>
                            <span class="stock-text text-available"><i class="fas fa-check"></i> {{ $pelicula->copias_en_estante }} disponibles</span>
                            @else
                            <span class="stock-dot dot-out"></span>
                            <span class="stock-text text-out"><i class="fas fa-times"></i> Agotado</span>
                            @endif
                        </div>

                        <div class="movie-premium-actions">
                            @auth
                            @if(auth()->user()->rol === 'cliente')
                            <a href="{{ route('alquilar', $pelicula) }}" class="btn-premium-action btn-rent {{ $pelicula->copias_en_estante == 0 ? 'disabled-action' : '' }}">
                                <i class="fas fa-shopping-cart"></i> Reservar
                            </a>
                            @endif
                            @else
                            <a href="{{ route('login') }}" class="btn-premium-action btn-guest">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión para Reservar
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<style>
    .catalog-dark-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem;
        /* Cancela márgenes del layout base anterior */
        padding: 3rem 0;
        color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .catalog-header {
        text-align: center;
        margin-bottom: 4rem;
    }

    .catalog-title {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        color: #ffffff;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .catalog-title i {
        color: #ff4b2b;
        -webkit-text-fill-color: initial;
        margin-right: 12px;
    }

    .catalog-subtitle {
        color: #6c757d;
        font-size: 1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Buscador */
    .search-box-container {
        position: relative;
        max-width: 650px;
        margin: 2.5rem auto 0 auto;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
        border-radius: 50px;
        overflow: hidden;
        border: 1px solid #2a2e35;
    }

    .search-icon {
        position: absolute;
        left: 22px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 1.1rem;
    }

    .search-box-container input {
        width: 100%;
        padding: 16px 20px 16px 55px;
        background-color: #1a1d24;
        border: none;
        color: #ffffff;
        font-size: 1rem;
        outline: none;
        transition: background 0.3s;
    }

    .search-box-container input:focus {
        background-color: #22262f;
    }

    /* Grid interactiva de 4 columnas inmune a interferencias de app.css */
    .movies-streaming-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)) !important;
        gap: 2rem !important;
        width: 100% !important;
    }

    /* Tarjetas estilo cartelera */
    .movie-premium-card {
        background-color: #1a1d24;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    .movie-premium-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(255, 75, 43, 0.25);
    }

    .movie-premium-poster {
        height: 190px;
        background: linear-gradient(135deg, #232731, #0f1115);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .movie-premium-poster i {
        color: #2b303c;
        font-size: 3.5rem;
    }

    .movie-premium-price {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: rgba(255, 75, 43, 0.9);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        color: #ffffff;
        padding: 6px 14px;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .movie-premium-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .movie-premium-genre {
        font-size: 0.72rem;
        font-weight: 700;
        color: #ff4b2b;
        text-uppercase: uppercase;
        letter-spacing: 1.2px;
        font-family: monospace;
        margin-bottom: 0.6rem;
        display: block;
    }

    .movie-premium-title {
        color: #ffffff;
        font-size: 1.15rem;
        font-weight: 600;
        margin: 0 0 1rem 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .movie-premium-stock-box {
        display: flex;
        align-items: center;
        margin-top: auto;
        margin-bottom: 1.5rem;
    }

    .stock-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .dot-available {
        background-color: #2ec4b6;
    }

    .dot-out {
        background-color: #e63946;
    }

    .stock-text {
        font-size: 0.85rem;
        font-weight: 600;
    }

    .text-available {
        color: #2ec4b6;
    }

    .text-out {
        color: #e63946;
    }

    /* Botones dinámicos */
    .btn-premium-action {
        display: block;
        width: 100%;
        text-align: center;
        padding: 11px 0;
        font-weight: 600;
        font-size: 0.88rem;
        border-radius: 10px;
        text-decoration: none;
        transition: background 0.2s, transform 0.2s;
        border: none;
    }

    .btn-rent {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
    }

    .btn-rent:hover {
        background: linear-gradient(45deg, #ff527b, #ff5e43);
    }

    .btn-guest {
        background-color: #2a2e35;
        color: #b3b3b3 !important;
    }

    .btn-guest:hover {
        background-color: #343a44;
        color: #ffffff !important;
    }

    .disabled-action {
        pointer-events: none !important;
        opacity: 0.4 !important;
        box-shadow: none !important;
    }
</style>
@endsection

@push('scripts')
<script>
    // Sincronizar estados Blade a variables globales de JavaScript para el buscador
    const isAuthenticated = {
        {
            auth() - > check() ? 'true' : 'false'
        }
    };
    const userRole = "{{ auth()->check() ? auth()->user()->rol : '' }}";

    function buscarPeliculas() {
        let query = document.getElementById('search').value;

        fetch(`/buscar-peliculas?search=${query}`)
            .then(response => response.json())
            .then(data => {
                let container = document.getElementById('movies-container');
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = `
                    <div style="text-align: center; padding: 5rem 0; width: 100%; grid-column: 1 / -1;">
                        <i class="fas fa-search fa-3x" style="color: #3a3f4d; margin-bottom: 1.5rem; display:block;"></i>
                        <p style="color: #6c757d; font-size: 1.1rem;">No se encontraron películas coincidentes.</p>
                    </div>`;
                    return;
                }

                let html = '<div class="movies-streaming-grid">';

                data.forEach(pelicula => {
                    let precio = parseFloat(pelicula.precio_alquiler).toFixed(2);

                    let stockHTML = pelicula.copias_en_estante > 0 ?
                        `<span class="stock-dot dot-available"></span><span class="stock-text text-available"><i class="fas fa-check"></i> ${pelicula.copias_en_estante} disponibles</span>` :
                        `<span class="stock-dot dot-out"></span><span class="stock-text text-out"><i class="fas fa-times"></i> Agotado</span>`;

                    let botonHTML = '';
                    if (isAuthenticated) {
                        if (userRole === 'cliente') {
                            let disabledStyle = pelicula.copias_en_estante == 0 ? 'disabled-action' : '';
                            botonHTML = `
                            <a href="/alquilar/${pelicula.id}" class="btn-premium-action btn-rent ${disabledStyle}">
                                <i class="fas fa-shopping-cart"></i> Alquilar
                            </a>`;
                        }
                    } else {
                        botonHTML = `
                        <a href="/login" class="btn-premium-action btn-guest">
                            <i class="fas fa-sign-in-alt"></i> Inicia sesión para alquilar
                        </a>`;
                    }

                    html += `
                    <div class="movie-premium-card">
                        <div class="movie-premium-poster">
                            <i class="fas fa-video"></i>
                            <span class="movie-premium-price">$${precio}</span>
                        </div>
                        <div class="movie-premium-body">
                            <span class="movie-premium-genre">
                                <i class="fas fa-tag"></i> ${pelicula.genero}
                            </span>
                            <h3 class="movie-premium-title" title="${pelicula.titulo}">
                                ${pelicula.titulo}
                            </h3>
                            <div class="movie-premium-stock-box">
                                ${stockHTML}
                            </div>
                            <div class="movie-premium-actions">
                                ${botonHTML}
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