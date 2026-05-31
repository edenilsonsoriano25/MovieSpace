@extends('layouts.app')

@section('title', 'Mis Préstamos')

@section('content')
<div class="history-dark-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="history-header">
            <h1 class="history-title">
                <i class="fas fa-history"></i> Mis Préstamos
            </h1>
            <p class="history-subtitle">Consulta el estado de tus alquileres activos, fechas límite de entrega y tu historial completo de consumos.</p>
        </div>
        
        @if($prestamos->isEmpty())
            <div class="history-empty-card">
                <div class="empty-icon-box">
                    <i class="fas fa-film"></i>
                </div>
                <h3>No tienes préstamos registrados</h3>
                <p>Aún no has apartado ningún título. Explora nuestra cartelera física y reserva tu primera película.</p>
                <a href="{{ route('peliculas.index') }}" class="btn-history-empty">
                    <i class="fas fa-search me-2"></i> Explorar Catálogo
                </a>
            </div>
        @else
            <div class="history-grid">
                @foreach($prestamos as $prestamo)
                <div class="history-premium-card {{ $prestamo->estado_prestamo == 'activo' && \Carbon\Carbon::now()->gt($prestamo->fecha_limite) ? 'card-deadline-critical' : '' }}">
                    
                    <div class="history-card-header-ticket">
                        <div class="ticket-info">
                            <span class="ticket-label">COMPROBANTE</span>
                            <h4 class="ticket-number">#{{ str_pad($prestamo->id, 5, '0', STR_PAD_LEFT) }}</h4>
                        </div>
                        <div class="ticket-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                    </div>
                    
                    <div class="history-card-body">
                        <div class="rented-movies-list">
                            @if($prestamos && $prestamo->detalles && $prestamo->detalles->count() > 0)
                                @foreach($prestamo->detalles as $detalle)
                                <div class="movie-detail-row">
                                    <div class="movie-main-info">
                                        <span class="movie-dot-bullet"></span>
                                        <p class="movie-title-text">{{ $detalle->pelicula->titulo ?? 'Película no encontrada' }}</p>
                                    </div>
                                    <span class="movie-price-tag">${{ number_format($detalle->precio_alquiler_momento, 2) }}</span>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted fs-7"><i class="fas fa-info-circle"></i> Sin detalles adjuntos al registro.</p>
                            @endif
                        </div>
                        
                        <div class="history-divider"></div>
                        
                        <div class="timeline-dates-container">
                            <div class="date-item">
                                <span class="date-label"><i class="fas fa-calendar-alt"></i> Salida</span>
                                <span class="date-value">{{ \Carbon\Carbon::parse($prestamo->fecha_salida)->format('d/m/Y') }}</span>
                            </div>
                            
                            <div class="date-item">
                                <span class="date-label"><i class="fas fa-clock"></i> Límite</span>
                                <span class="date-value deadline-highlight">{{ \Carbon\Carbon::parse($prestamo->fecha_limite)->format('d/m/Y') }}</span>
                            </div>
                            
                            @if($prestamo->fecha_entrega_real)
                            <div class="date-item">
                                <span class="date-label"><i class="fas fa-calendar-check"></i> Retorno</span>
                                <span class="date-value text-teal">{{ \Carbon\Carbon::parse($prestamo->fecha_entrega_real)->format('d/m/Y') }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="history-card-footer-metrics">
                            <div class="status-badges-group">
                                @if($prestamo->estado_prestamo == 'activo')
                                    <span class="badge-status badge-active">
                                        <span class="pulse-indicator"></span> Activo
                                    </span>
                                    
                                    @if(\Carbon\Carbon::now()->gt($prestamo->fecha_limite))
                                        <span class="badge-status badge-overdue">
                                            <i class="fas fa-exclamation-triangle"></i> Retrasado
                                        </span>
                                    @endif
                                @else
                                    <span class="badge-status badge-completed">
                                        <i class="fas fa-check-double"></i> Completado
                                    </span>
                                @endif
                            </div>
                            
                            @if($prestamo->multa_total > 0)
                                <div class="fine-counter-box">
                                    <span class="fine-label">RECARGO POR MORA</span>
                                    <span class="fine-amount">${{ number_format($prestamo->multa_total, 2) }}</span>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        @endif
        
    </div>
</div>

<style>
    .history-dark-wrapper {
        background-color: #0f1115;
        min-height: calc(100vh - 140px);
        margin-top: -2rem; /* Sincroniza con el padding de app.blade.php */
        padding: 3rem 0 5rem 0;
        color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .history-header {
        text-align: center;
        margin-bottom: 4rem;
    }

    .history-title {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .history-title i {
        color: #ff4b2b;
        -webkit-text-fill-color: initial;
        margin-right: 12px;
    }

    .history-subtitle {
        color: #6c757d;
        font-size: 1rem;
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.5;
    }

    /* CSS Grid Responsivo e Independiente */
    .history-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)) !important;
        gap: 2rem !important;
        width: 100% !important;
    }

    /* Estilo de Tarjetas de Historial */
    .history-premium-card {
        background-color: #1a1d24;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.03);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .history-premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
    }

    /* Alerta crítica visual perimetral si el préstamo venció */
    .card-deadline-critical {
        border: 1px solid rgba(240, 62, 62, 0.3) !important;
        box-shadow: 0 10px 25px rgba(240, 62, 62, 0.08) !important;
    }

    /* Cabecera Tipo Ticket */
    .history-card-header-ticket {
        background: linear-gradient(135deg, #232731, #14171c);
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px dashed rgba(255, 255, 255, 0.06);
    }

    .ticket-label {
        font-size: 0.68rem;
        font-weight: 700;
        color: #6c757d;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 0.2rem;
    }

    .ticket-number {
        color: #ffffff;
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        font-family: monospace;
    }

    .ticket-icon i {
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.15);
    }

    /* Cuerpo de Información Interna */
    .history-card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .rented-movies-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .movie-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .movie-main-info {
        display: flex;
        align-items: center;
        gap: 10px;
        max-width: 78%;
    }

    .movie-dot-bullet {
        width: 6px;
        height: 6px;
        background-color: #ff4b2b;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .movie-title-text {
        margin: 0;
        color: #ffffff;
        font-size: 0.98rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .movie-price-tag {
        color: #cdcdcd;
        font-size: 0.92rem;
        font-weight: 600;
        font-family: monospace;
    }

    .history-divider {
        height: 1px;
        background-color: rgba(255, 255, 255, 0.04);
        margin-bottom: 1.25rem;
    }

    /* Bloque de Fechas Horizontal */
    .timeline-dates-container {
        display: flex;
        justify-content: space-between;
        background-color: #111317;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        gap: 0.5rem;
    }

    .date-item {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .date-label {
        font-size: 0.72rem;
        color: #6c757d;
        font-weight: 600;
        text-uppercase: uppercase;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .date-value {
        color: #ffffff;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .deadline-highlight { color: #ff9f43; }
    .text-teal { color: #2ec4b6; }

    /* Barra Inferior de Métricas */
    .history-card-footer-metrics {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .status-badges-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .badge-status {
        font-size: 0.78rem;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
    }

    .badge-active { background-color: rgba(46, 196, 182, 0.15); color: #2ec4b6; }
    .badge-completed { background-color: rgba(158, 158, 158, 0.15); color: #a0a0a0; }
    .badge-overdue { background-color: rgba(240, 62, 62, 0.15); color: #f03e3e; }

    /* Efecto de pulso en el indicador activo */
    .pulse-indicator {
        width: 6px;
        height: 6px;
        background-color: #2ec4b6;
        border-radius: 50%;
        display: inline-block;
        animation: pulseAnimation 1.8s infinite;
    }

    @keyframes pulseAnimation {
        0% { box-shadow: 0 0 0 0 rgba(46, 196, 182, 0.7); }
        70% { box-shadow: 0 0 0 6px rgba(46, 196, 182, 0); }
        100% { box-shadow: 0 0 0 0 rgba(46, 196, 182, 0); }
    }

    /* Caja de Contabilidad de Multas */
    .fine-counter-box {
        text-align: right;
    }

    .fine-label {
        font-size: 0.62rem;
        color: #f03e3e;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: block;
    }

    .fine-amount {
        color: #f03e3e;
        font-size: 1.15rem;
        font-weight: 800;
        font-family: monospace;
    }

    /* Caja de Escenario Vacío */
    .history-empty-card {
        background-color: #1a1d24;
        border-radius: 16px;
        padding: 4.5rem 2rem;
        text-align: center;
        max-width: 550px;
        margin: 2rem auto 0 auto;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        border: 1px solid rgba(255, 255, 255, 0.02);
    }

    .empty-icon-box {
        width: 80px;
        height: 80px;
        background-color: #111317;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem auto;
        color: #3b4252;
        font-size: 2.5rem;
    }

    .history-empty-card h3 {
        color: #ffffff;
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .history-empty-card p {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.5;
        margin: 0 0 2rem 0;
    }

    .btn-history-empty {
        display: inline-flex;
        align-items: center;
        padding: 12px 24px;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff;
        font-weight: 600;
        font-size: 0.92rem;
        text-decoration: none;
        border-radius: 30px;
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.25);
        transition: transform 0.2s;
    }

    .btn-history-empty:hover {
        transform: translateY(-2px);
    }
</style>
@endsection