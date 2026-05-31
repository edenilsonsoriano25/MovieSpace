@extends('layouts.app')

@section('title', 'Bienvenido a MovieSpace')

@section('content')
<div class="landing-premium-wrapper">
    
    <div class="hero-premium-banner">
        <div class="hero-overlay-glow"></div>
        <div class="hero-premium-content">
            <span class="hero-badge-pill"><i class="fas fa-popcorn"></i> Alquiler de CDs Físicos</span>
            <h1 class="hero-main-title">Tu Espacio de Cine en Jayaque</h1>
            <p class="hero-subtitle">Explora un catálogo exclusivo de películas físicas, aparta tus títulos favoritos en línea y retira tu copia en estante de forma rápida y segura.</p>
            <div class="hero-actions-group">
                <a href="{{ route('peliculas.index') }}" class="btn-hero-primary">
                    <span>Explorar Catálogo</span> <i class="fas fa-play"></i>
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn-hero-secondary">Crear Cuenta Gratis</a>
                @endguest
            </div>
        </div>
    </div>
    
    <div class="container-fluid px-4 px-md-5">
        <div class="section-title-wrapper">
            <h2 class="landing-section-title">
                <span class="title-accent-bar"></span>
                <i class="fas fa-fire"></i> Películas Destacadas en Cartelera
            </h2>
            <p class="section-subtitle-text">Los títulos más solicitados y con mayor disponibilidad de copias físicas en mostrador hoy.</p>
        </div>
        
        <div class="movies-streaming-grid">
            @forelse($destacadas as $pelicula)
            <div class="movie-premium-card">
                <div class="movie-poster-viewport">
                    <div class="movie-gradient-shading"></div>
                    <i class="fas fa-clapperboard poster-fallback-icon"></i>
                    <span class="movie-badge-genre"><i class="fas fa-ticket"></i> {{ $pelicula->genero }}</span>
                </div>
                
                <div class="movie-premium-info">
                    <h3 class="movie-premium-title" title="{{ $pelicula->titulo }}">{{ $pelicula->titulo }}</h3>
                    
                    <div class="movie-metrics-footer">
                        <div class="movie-rent-rate">
                            <span class="rate-label">ALQUILER</span>
                            <span class="rate-value">${{ number_format($pelicula->precio_alquiler, 2) }}</span>
                        </div>
                        
                        <a href="{{ route('peliculas.index') }}" class="btn-card-details">
                            <i class="fas fa-circle-info"></i> Detalle
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="catalog-empty-state-box">
                <i class="fas fa-film"></i>
                <p>Nuestra cartelera se está actualizando. Visita el catálogo global para ver los títulos disponibles.</p>
                <a href="{{ route('peliculas.index') }}" class="btn-hero-primary" style="margin-top: 1rem;">Ver Todo</a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .landing-premium-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem; /* Sincroniza con el padding de app.blade.php */
        padding-bottom: 5rem;
        color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    /* SECCIÓN HERO BANNER PREMIUM */
    .hero-premium-banner {
        position: relative;
        background: linear-gradient(135deg, #161920 0%, #0b0d10 100%);
        padding: 8rem 2rem;
        text-align: center;
        overflow: hidden;
        border-bottom: 1px solid rgba(255, 255, 255, 0.02);
        margin-bottom: 4rem;
    }

    /* Resplandor trasero de ambiente multimedia (Efecto Cine) */
    .hero-overlay-glow {
        position: absolute;
        top: 50%; left: 50%; width: 600px; height: 300px;
        background: radial-gradient(circle, rgba(255, 65, 108, 0.08) 0%, transparent 70%);
        transform: translate(-50%, -50%);
        z-index: 1;
        pointer-events: none;
    }

    .hero-premium-content {
        position: relative;
        z-index: 2;
        max-width: 750px;
        margin: 0 auto;
    }

    .hero-badge-pill {
        background-color: rgba(255, 65, 108, 0.1);
        color: #ff4b2b;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 30px;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 65, 108, 0.15);
    }

    .hero-main-title {
        font-size: 3.4rem;
        font-weight: 800;
        letter-spacing: -1px;
        line-height: 1.15;
        margin: 0 0 1rem 0;
        background: linear-gradient(45deg, #ffffff 30%, #cdcdcd 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
        color: #9a9a9a;
        font-size: 1.15rem;
        line-height: 1.6;
        margin: 0 0 2.5rem 0;
    }

    .hero-actions-group {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
    }

    .btn-hero-primary {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff !important;
        text-decoration: none;
        padding: 14px 28px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 20px rgba(255, 65, 108, 0.35);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-hero-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(255, 65, 108, 0.45);
    }

    .btn-hero-secondary {
        background-color: #1a1d24;
        color: #ffffff !important;
        text-decoration: none;
        padding: 14px 28px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.04);
        transition: background-color 0.2s;
    }

    .btn-hero-secondary:hover {
        background-color: #242933;
    }

    /* ENCABEZADOS DE SECCIÓN */
    .section-title-wrapper {
        margin-bottom: 2.5rem;
    }

    .landing-section-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 0.3rem 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .landing-section-title i {
        color: #ff4b2b;
    }

    .section-subtitle-text {
        color: #6c757d;
        font-size: 0.95rem;
        margin: 0;
    }

    /* REJILLA DE TARJETAS MULTIMEDIA (MOVIES GRID) */
    .movies-streaming-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)) !important;
        gap: 2rem !important;
        width: 100%;
    }

    /* Tarjeta Cinemática Estilizada */
    .movie-premium-card {
        background-color: #1a1d24;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.02);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
        transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.3s;
    }

    .movie-premium-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.5);
    }

    /* Contenedor del Poster (Viewport) */
    .movie-poster-viewport {
        height: 250px;
        background: linear-gradient(135deg, #242933 0%, #111317 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .movie-gradient-shading {
        position: absolute;
        bottom: 0; left: 0; width: 100%; height: 50%;
        background: linear-gradient(to top, rgba(26, 29, 36, 0.9), transparent);
        z-index: 1;
    }

    .poster-fallback-icon {
        font-size: 3.5rem;
        color: rgba(255, 255, 255, 0.03);
        transition: transform 0.4s ease;
    }

    .movie-premium-card:hover .poster-fallback-icon {
        transform: scale(1.1) rotate(-3deg);
        color: rgba(255, 65, 108, 0.06);
    }

    .movie-badge-genre {
        position: absolute;
        top: 14px; left: 14px;
        background-color: rgba(15, 17, 21, 0.85);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        color: #cdcdcd;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        z-index: 2;
        border: 1px solid rgba(255,255,255,0.03);
    }

    /* Información de la Tarjeta Inferior */
    .movie-premium-info {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .movie-premium-title {
        color: #ffffff;
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0 0 1rem 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Panel de Métricas e Interacción */
    .movie-metrics-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
    }

    .movie-rent-rate {
        display: flex;
        flex-direction: column;
    }

    .rate-label {
        font-size: 0.62rem;
        color: #6c757d;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .rate-value {
        color: #2ec4b6;
        font-size: 1.15rem;
        font-weight: 800;
        font-family: monospace;
    }

    .btn-card-details {
        background-color: #111317;
        color: #cdcdcd !important;
        border: 1px solid rgba(255, 255, 255, 0.04);
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }

    .movie-premium-card:hover .btn-card-details {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff !important;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(255, 65, 108, 0.25);
    }

    /* Estado de Cartelera Vacía */
    .catalog-empty-state-box {
        grid-column: 1 / -1;
        background-color: #1a1d24;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
        color: #495057;
        border: 1px solid rgba(255,255,255,0.02);
    }

    .catalog-empty-state-box i { font-size: 3rem; margin-bottom: 1rem; color: #323742; }
    .catalog-empty-state-box p { font-size: 1.1rem; font-weight: 600; margin: 0 0 1.5rem 0; color: #6c757d; }

    @media (max-width: 576px) {
        .hero-main-title { font-size: 2.2rem; }
        .hero-premium-banner { padding: 5rem 1rem; margin-bottom: 2.5rem; }
        .movies-streaming-grid { grid-template-columns: 1fr !important; }
    }
</style>
@endsection