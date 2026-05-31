@extends('layouts.app')

@section('title', 'Nuevo Préstamo')

@section('content')
<div class="loans-create-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="create-page-header">
            <div class="header-left">
                <h1 class="create-main-title"><i class="fas fa-plus-circle"></i> Nuevo Préstamo</h1>
                <p class="create-main-subtitle">Registra una nueva orden de arriendo físico en mostrador, vincula al afiliado y selecciona las copias disponibles.</p>
            </div>
            <a href="{{ route('prestamos.index') }}" class="btn-premium-back">
                <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
        </div>

        <div class="premium-form-card">
            <form method="POST" action="{{ route('prestamos.store') }}" class="premium-interactive-form">
                @csrf
                
                <div class="form-premium-section">
                    <h3 class="section-form-title"><i class="fas fa-user-tag"></i> Datos del Cliente</h3>
                    <div class="form-premium-group">
                        <label for="id_usuario">Seleccionar Cuenta de Usuario</label>
                        <div class="premium-select-wrapper">
                            <select name="id_usuario" id="id_usuario" required>
                                <option value="">-- Selecciona el correo o nombre del afiliado --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->name }} — {{ $cliente->email }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-premium-section">
                    <h3 class="section-form-title"><i class="fas fa-film"></i> Películas a Alquilar <span class="title-helper">(Puedes marcar varias cintas)</span></h3>
                    
                    <div class="peliculas-premium-grid">
                        @foreach($peliculas as $pelicula)
                        <div class="pelicula-premium-checkbox">
                            <label class="checkbox-interactive-label">
                                <input type="checkbox" name="peliculas[]" value="{{ $pelicula->id }}">
                                <span class="checkbox-custom-indicator"></span>
                                
                                <div class="pelicula-premium-info">
                                    <strong class="movie-title-text">{{ $pelicula->titulo }}</strong>
                                    <div class="movie-meta-row">
                                        <span class="movie-price-tag">${{ number_format($pelicula->precio_alquiler, 2) }}</span>
                                        <span class="movie-stock-tag"><i class="fas fa-compact-disc"></i> {{ $pelicula->copias_en_estante }} en estante</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    @if($peliculas->isEmpty())
                        <div class="alert-warning-premium">
                            <div class="alert-icon-box"><i class="fas fa-exclamation-triangle"></i></div>
                            <p>No se registran películas con copias físicas disponibles en estantería en este momento.</p>
                        </div>
                    @endif
                </div>

                <div class="form-premium-section last-section">
                    <div class="form-premium-row">
                        
                        <div class="form-premium-group">
                            <label for="dias_prestamo"><i class="fas fa-calendar-day"></i> Duración del Préstamo</label>
                            <div class="premium-select-wrapper">
                                <select name="dias_prestamo" id="dias_prestamo" required>
                                    @for ($i = 1; $i <= 7; $i++)
                                        <option value="{{ $i }}" {{ $i == 3 ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'día' : 'días' }} base</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="form-premium-group">
                            <label for="metodo_pago"><i class="fas fa-wallet"></i> Método de Pago</label>
                            <div class="premium-select-wrapper">
                                <select name="metodo_pago" id="metodo_pago" required>
                                    <option value="efectivo">💵 Efectivo en Caja</option>
                                    <option value="tarjeta">💳 Terminal de Tarjeta</option>
                                    <option value="transferencia">🏦 Transferencia de Banco</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-premium-actions">
                    <a href="{{ route('prestamos.index') }}" class="btn-form-cancel">Cancelar Operación</a>
                    <button type="submit" class="btn-form-save">
                        <span>Registrar Préstamo</span> <i class="fas fa-check-circle"></i>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<style>
    .loans-create-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem; /* Sincroniza con app.blade.php */
        padding: 3rem 0 5rem 0;
        color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .create-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        gap: 1.5rem;
    }

    .create-main-title {
        font-size: 2.6rem;
        font-weight: 800;
        margin: 0 0 0.4rem 0;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .create-main-title i {
        color: #ff4b2b;
        -webkit-text-fill-color: initial;
        margin-right: 12px;
    }

    .create-main-subtitle {
        color: #6c757d;
        font-size: 0.98rem;
        margin: 0;
    }

    .btn-premium-back {
        background-color: #1a1d24;
        color: #b3b3b3 !important;
        border: 1px solid rgba(255,255,255,0.05);
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.88rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-premium-back:hover {
        background-color: #242933;
        color: #ffffff !important;
    }

    /* TARJETA DEL FORMULARIO CENTRAL */
    .premium-form-card {
        background-color: #1a1d24;
        border-radius: 16px;
        padding: 3rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.02);
    }

    .premium-interactive-form {
        display: flex;
        flex-direction: column;
        gap: 2.2rem;
    }

    .form-premium-section {
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        padding-bottom: 2rem;
    }

    .form-premium-section.last-section {
        border-bottom: none;
        padding-bottom: 0;
    }

    .section-form-title {
        color: #ffffff;
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0 0 1.25rem 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-form-title i { color: #ff4b2b; }
    
    .title-helper {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 500;
    }

    .form-premium-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-premium-group label {
        color: #cdcdcd;
        font-size: 0.88rem;
        font-weight: 600;
    }

    /* Componentes Desplegables Select Estilizados */
    .premium-form-card select {
        width: 100%;
        padding: 12px 16px;
        background-color: #111317;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        color: #ffffff;
        font-size: 0.95rem;
        outline: none;
        box-sizing: border-box;
        font-family: inherit;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .premium-form-card select:focus {
        border-color: #ff4b2b;
        background-color: #14171c;
        box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.15);
    }

    /* Inyección de Flecha Minimalista en los Selects */
    .premium-select-wrapper { position: relative; width: 100%; }
    .premium-select-wrapper select { appearance: none; -webkit-appearance: none; padding-right: 40px; }
    .premium-select-wrapper::after {
        content: '\f078'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        font-size: 0.72rem; color: #6c757d; position: absolute; right: 16px; top: 50%;
        transform: translateY(-50%); pointer-events: none;
    }

    /* FILA INTERNA EN DOS COLUMNAS RESPONSIVAS */
    .form-premium-row {
        display: flex;
        gap: 1.5rem;
    }

    .form-premium-row .form-premium-group {
        flex: 1;
    }

    /* CUADRÍCULA MULTIMEDIA DE CHECBOXES */
    .peliculas-premium-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
        gap: 1.25rem !important;
        max-height: 380px;
        overflow-y: auto;
        padding-right: 8px;
    }

    /* Personalización de la barra de desplazamiento interna */
    .peliculas-premium-grid::-webkit-scrollbar { width: 6px; }
    .peliculas-premium-grid::-webkit-scrollbar-track { background: #111317; border-radius: 10px; }
    .peliculas-premium-grid::-webkit-scrollbar-thumb { background: #2a2e35; border-radius: 10px; }

    /* Tarjeta Checkbox Activa */
    .pelicula-premium-checkbox {
        background-color: #111317;
        border: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .checkbox-interactive-label {
        display: flex;
        align-items: center;
        padding: 1rem;
        gap: 12px;
        cursor: pointer;
        width: 100%;
        box-sizing: border-box;
        user-select: none;
    }

    /* Ocultar input nativo tosco */
    .checkbox-interactive-label input[type="checkbox"] {
        display: none;
    }

    /* Indicador personalizado de Checkbox circular */
    .checkbox-custom-indicator {
        width: 20px;
        height: 20px;
        border: 2px solid #2a2e35;
        border-radius: 50%;
        position: relative;
        flex-shrink: 0;
        transition: all 0.2s;
    }

    /* Comportamiento al activarse */
    .checkbox-interactive-label input[type="checkbox"]:checked + .checkbox-custom-indicator {
        border-color: #ff4b2b;
        background-color: #ff4b2b;
    }

    .checkbox-interactive-label input[type="checkbox"]:checked + .checkbox-custom-indicator::after {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        font-size: 0.65rem;
        color: #ffffff;
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Resaltar la tarjeta completa si el checkbox está activo */
    .pelicula-premium-checkbox:has(input[type="checkbox"]:checked) {
        border-color: rgba(255, 75, 43, 0.3);
        background-color: rgba(255, 75, 43, 0.02);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .pelicula-premium-checkbox:hover {
        border-color: rgba(255, 255, 255, 0.1);
        background-color: #14171d;
    }

    .pelicula-premium-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        flex-grow: 1;
        overflow: hidden;
    }

    .movie-title-text {
        color: #ffffff;
        font-size: 0.95rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .movie-meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .movie-price-tag {
        color: #2ec4b6;
        font-weight: 700;
        font-size: 0.88rem;
        font-family: monospace;
    }

    .movie-stock-tag {
        color: #6c757d;
        font-size: 0.78rem;
        font-weight: 500;
    }

    /* Caja de Alerta Vacía */
    .alert-warning-premium {
        background-color: rgba(255, 152, 0, 0.1);
        border: 1px solid rgba(255, 152, 0, 0.15);
        border-radius: 10px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #ffb74d;
        font-weight: 600;
        font-size: 0.92rem;
    }

    .alert-icon-box { font-size: 1.1rem; }
    .alert-warning-premium p { margin: 0; }

    /* Botonera inferior de confirmación */
    .form-premium-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
        border-top: 1px solid rgba(255,255,255,0.04);
        padding-top: 2rem;
    }

    .btn-form-cancel {
        background-color: #2a2e35;
        color: #b3b3b3 !important;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.92rem;
        transition: all 0.2s;
    }

    .btn-form-cancel:hover {
        background-color: #343a44;
        color: #ffffff !important;
    }

    .btn-form-save {
        background: linear-gradient(45deg, #2ec4b6, #009688);
        color: #ffffff;
        border: none;
        padding: 12px 26px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.92rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(46, 196, 182, 0.2);
        transition: all 0.2s ease;
    }

    .btn-form-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(46, 196, 182, 0.3);
    }

    /* Consultas de Adaptación Móvil */
    @media (max-width: 768px) {
        .create-page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .btn-premium-back, .form-premium-actions { width: 100%; }
        .form-premium-row { flex-direction: column; gap: 1.5rem; }
        .premium-form-card { padding: 1.5rem; }
        .btn-form-save, .btn-form-cancel { flex: 1; text-align: center; justify-content: center; }
    }
</style>