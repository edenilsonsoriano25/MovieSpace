@extends('layouts.app')

@section('title', 'Confirmar Alquiler')

@section('content')
<div class="rent-dark-wrapper">
    <div class="rent-card-premium">
        
        <div class="rent-card-header">
            <div class="rent-icon-badge">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2>Confirmar tu Alquiler</h2>
            <p class="text-muted">Estás a un paso de apartar tu película. Revisa los detalles antes de confirmar.</p>
        </div>
        
        <div class="movie-preview-box">
            <div class="movie-preview-poster">
                <i class="fas fa-video"></i>
            </div>
            <div class="movie-preview-details">
                <span class="movie-preview-genre"><i class="fas fa-tag"></i> {{ $pelicula->genero }}</span>
                <h3 class="movie-preview-title">{{ $pelicula->titulo }}</h3>
                <div class="movie-preview-price">${{ number_format($pelicula->precio_alquiler, 2) }} <span class="price-type">/ Base</span></div>
                
                <div class="movie-preview-stock">
                    <span class="stock-dot-active"></span>
                    <span class="stock-text-active"><i class="fas fa-check"></i> {{ $pelicula->copias_en_estante }} copias disponibles</span>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('prestamos.cliente.store') }}" class="rent-form">
            @csrf
            <input type="hidden" name="pelicula_id" value="{{ $pelicula->id }}">
            
            <div class="rent-input-group">
                <label for="dias_prestamo"><i class="fas fa-calendar-day"></i> Tiempo de Préstamo (1-7 días)</label>
                <select name="dias_prestamo" id="dias_prestamo" required>
                    @for ($i = 1; $i <= 7; $i++)
                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'día' : 'días' }} — ${{ number_format($pelicula->precio_alquiler, 2) }}</option>
                    @endfor
                </select>
            </div>
            
            <div class="rent-input-group">
                <label for="metodo_pago"><i class="fas fa-wallet"></i> Método de Pago Preferido</label>
                <select name="metodo_pago" id="metodo_pago" required>
                    <option value="efectivo">💵 Efectivo</option>
                    <option value="tarjeta">💳 Tarjeta de Crédito / Débito</option>
                    <option value="transferencia">🏦 Transferencia Bancaria</option>
                </select>
            </div>
            
            <button type="submit" class="btn-rent-submit">
                <span>Confirmar y Reservar CD</span> <i class="fas fa-check-circle"></i>
            </button>
        </form>
        
        <div class="rent-card-footer">
            <a href="{{ route('peliculas.index') }}" class="btn-cancel-link">
                <i class="fas fa-arrow-left"></i> Cancelar y volver al catálogo
            </a>
        </div>
    </div>
</div>

<style>
    .rent-dark-wrapper {
        background-color: #0f1115;
        min-height: calc(100vh - 140px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .rent-card-premium {
        background-color: #1a1d24;
        width: 100%;
        max-width: 500px;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    .rent-card-header {
        text-align: center;
        margin-bottom: 2.2rem;
    }

    .rent-icon-badge {
        width: 60px;
        height: 60px;
        background: linear-gradient(45deg, rgba(255, 65, 108, 0.1), rgba(255, 75, 43, 0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
        border: 1px solid rgba(255, 75, 43, 0.2);
    }

    .rent-icon-badge i {
        font-size: 1.4rem;
        color: #ff4b2b;
    }

    .rent-card-header h2 {
        color: #ffffff;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .rent-card-header p {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.4;
    }

    /* Caja de previsualización horizontal de la película */
    .movie-preview-box {
        background-color: #111317;
        border-radius: 14px;
        display: flex;
        overflow: hidden;
        margin-bottom: 2.2rem;
        border: 1px solid rgba(255, 255, 255, 0.02);
    }

    .movie-preview-poster {
        width: 130px;
        background: linear-gradient(135deg, #232731, #0f1115);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .movie-preview-poster i {
        color: #2b303c;
        font-size: 2.8rem;
    }

    .movie-preview-details {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .movie-preview-genre {
        font-size: 0.68rem;
        font-weight: 700;
        color: #ff4b2b;
        text-uppercase: uppercase;
        letter-spacing: 1.2px;
        font-family: monospace;
        margin-bottom: 0.3rem;
    }

    .movie-preview-title {
        color: #ffffff;
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0 0 0.4rem 0;
    }

    .movie-preview-price {
        color: #ffffff;
        font-size: 1.35rem;
        font-weight: 800;
        margin-bottom: 0.6rem;
    }

    .price-type {
        font-size: 0.78rem;
        color: #6c757d;
        font-weight: 500;
    }

    .movie-preview-stock {
        display: flex;
        align-items: center;
    }

    .stock-dot-active {
        width: 7px;
        height: 7px;
        background-color: #2ec4b6;
        border-radius: 50%;
        margin-right: 6px;
    }

    .stock-text-active {
        color: #2ec4b6;
        font-size: 0.82rem;
        font-weight: 600;
    }

    /* Formulario e inputs */
    .rent-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .rent-input-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .rent-input-group label {
        color: #cdcdcd;
        font-size: 0.88rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .rent-input-group label i {
        color: #6c757d;
    }

    .rent-input-group select {
        width: 100%;
        padding: 12px 16px;
        background-color: #111317;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        color: #ffffff;
        font-size: 0.95rem;
        outline: none;
        box-sizing: border-box;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s ease;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%236c757d' class='bi bi-chevron-down' viewBox='0 0 16 16'><path fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/></svg>");
        background-repeat: no-repeat;
        background-position: right 16px center;
    }

    .rent-input-group select:focus {
        border-color: #ff4b2b;
        background-color: #14171c;
        box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.15);
    }

    /* Botón Submit Premium */
    .btn-rent-submit {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff;
        border: none;
        padding: 14px;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
        transition: all 0.2s ease;
        margin-top: 0.5rem;
    }

    .btn-rent-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.4);
    }

    /* Footer Enlace */
    .rent-card-footer {
        text-align: center;
        margin-top: 1.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 1.25rem;
    }

    .btn-cancel-link {
        color: #6c757d;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-cancel-link:hover {
        color: #ffffff;
    }
</style>
@endsection