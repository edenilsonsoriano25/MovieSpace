@extends('layouts.app')

@section('title', 'Agregar Película')

@section('content')
<div class="admin-create-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="create-page-header">
            <div class="header-left">
                <h1 class="create-main-title"><i class="fas fa-plus-circle"></i> Agregar Nueva Película</h1>
                <p class="create-main-subtitle">Inserta un nuevo título al catálogo global, define sus tarifas base e inicializa el stock físico de CDs.</p>
            </div>
            <a href="{{ route('admin.peliculas.index') }}" class="btn-premium-back">
                <i class="fas fa-arrow-left"></i> Volver al Catálogo
            </a>
        </div>

        <div class="premium-form-card">
            <form method="POST" action="{{ route('admin.peliculas.store') }}" class="premium-interactive-form">
                @csrf
                
                <div class="form-premium-row">
                    <div class="form-premium-group">
                        <label for="titulo"><i class="fas fa-heading"></i> Título de la Película *</label>
                        <input type="text" name="titulo" id="titulo" placeholder="Ej: Interestelar" required value="{{ old('titulo') }}">
                        @error('titulo')
                            <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-premium-group">
                        <label for="genero"><i class="fas fa-tags"></i> Género Cinematográfico *</label>
                        <div class="premium-select-wrapper">
                            <select name="genero" id="genero" required>
                                <option value="">Seleccionar género...</option>
                                @foreach(['Acción', 'Comedia', 'Drama', 'Ciencia Ficción', 'Terror', 'Romance', 'Animación', 'Superhéroes'] as $gen)
                                    <option value="{{ $gen }}" {{ old('genero') == $gen ? 'selected' : '' }}>{{ $gen }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('genero')
                            <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-premium-row">
                    <div class="form-premium-group">
                        <label for="director"><i class="fas fa-user-theater"></i> Director *</label>
                        <input type="text" name="director" id="director" placeholder="Ej: Christopher Nolan" required value="{{ old('director') }}">
                        @error('director')
                            <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-premium-group">
                        <label for="año"><i class="fas fa-calendar-alt"></i> Año de Lanzamiento *</label>
                        <input type="number" name="año" id="año" placeholder="Ej: 2014" required min="1900" max="2026" value="{{ old('año', date('Y')) }}">
                        @error('año')
                            <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-premium-group">
                    <label for="sinopsis"><i class="fas fa-align-left"></i> Sinopsis o Resumen Argumental *</label>
                    <textarea name="sinopsis" id="sinopsis" rows="4" placeholder="Escribe una breve descripción sobre la trama de la película..." required>{{ old('sinopsis') }}</textarea>
                    @error('sinopsis')
                        <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-premium-row triple-row">
                    <div class="form-premium-group">
                        <label for="precio_alquiler"><i class="fas fa-dollar-sign"></i> Precio de Alquiler *</label>
                        <input type="number" step="0.01" name="precio_alquiler" id="precio_alquiler" placeholder="0.00" required value="{{ old('precio_alquiler', 2.50) }}">
                        @error('precio_alquiler')
                            <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-premium-group">
                        <label for="copias_totales"><i class="fas fa-boxes"></i> Copias Totales (Stock) *</label>
                        <input type="number" name="copias_totales" id="copias_totales" placeholder="Ej: 5" required min="1" value="{{ old('copias_totales', 5) }}">
                        @error('copias_totales')
                            <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-premium-group">
                        <label for="copias_en_estante"><i class="fas fa-clipboard-check"></i> Copias Disponibles *</label>
                        <input type="number" name="copias_en_estante" id="copias_en_estante" placeholder="Ej: 5" required min="0" value="{{ old('copias_en_estante', 5) }}">
                        @error('copias_en_estante')
                            <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-premium-actions">
                    <a href="{{ route('admin.peliculas.index') }}" class="btn-form-cancel">Cancelar</a>
                    <button type="submit" class="btn-form-save">
                        <i class="fas fa-save"></i> Guardar Película
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<style>
    .admin-create-wrapper {
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

    /* TARJETA DEL FORMULARIO */
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
        gap: 1.5rem;
    }

    /* Filas Flexibles Responsivas */
    .form-premium-row {
        display: flex;
        gap: 1.5rem;
    }

    .form-premium-row .form-premium-group {
        flex: 1;
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
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-premium-group label i {
        color: #6c757d;
        font-size: 0.9rem;
        width: 16px;
        text-align: center;
    }

    /* Inputs, Selects y Textareas Oscuros */
    .form-premium-group input, 
    .form-premium-group textarea, 
    .premium-select-wrapper select {
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
    }

    .form-premium-group textarea {
        resize: vertical;
    }

    .form-premium-group input:focus,
    .form-premium-group textarea:focus,
    .premium-select-wrapper select:focus {
        border-color: #ff4b2b;
        background-color: #14171c;
        box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.15);
    }

    /* Inyección de Flecha Estilizada en el Select */
    .premium-select-wrapper { position: relative; width: 100%; }
    .premium-select-wrapper select { appearance: none; -webkit-appearance: none; padding-right: 40px; cursor: pointer; }
    .premium-select-wrapper::after {
        content: '\f078'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        font-size: 0.72rem; color: #6c757d; position: absolute; right: 16px; top: 50%;
        transform: translateY(-50%); pointer-events: none;
    }

    /* Mensajes de error debajo del input */
    .field-error-msg {
        color: #ef5350;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 0.1rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Botonera inferior */
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

    /* Consultas Responsivas */
    @media (max-width: 768px) {
        .create-page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .btn-premium-back, .form-premium-actions { width: 100%; }
        .form-premium-row { flex-direction: column; gap: 1.5rem; }
        .premium-form-card { padding: 1.5rem; }
        .btn-form-save, .btn-form-cancel { flex: 1; text-align: center; justify-content: center; }
    }
</style>
@endsection