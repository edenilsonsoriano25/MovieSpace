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
            <form method="POST" action="{{ route('prestamos.store') }}" class="premium-interactive-form" id="prestamoForm">
                @csrf
                
                <div class="form-premium-section">
                    <h3 class="section-form-title"><i class="fas fa-user-tag"></i> Datos del Cliente</h3>
                    <div class="form-premium-group">
                        <label for="search_cliente">Buscar o Seleccionar Afiliado (Escribe nombre o correo)</label>
                        <div class="premium-input-search-wrapper">
                            <i class="fas fa-search search-input-icon"></i>
                            <input type="text" id="search_cliente" placeholder="Ej: Hil o hailyaneth@gmail.com" class="premium-search-input" autocomplete="off">
                        </div>
                        
                        <div class="premium-select-wrapper mt-2 customer-list-container" id="cliente_list_container">
                            <select name="id_usuario" id="id_usuario" required size="5" class="premium-scrollable-select">
                                <option value="" disabled selected>-- Selecciona un cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" data-search="{{ strtolower($cliente->name . ' ' . $cliente->email) }}" data-name="{{ $cliente->name }}" data-email="{{ $cliente->email }}">
                                        {{ $cliente->name }} — {{ $cliente->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="form-text text-muted" style="color: #6c757d; font-size: 0.75rem;">
                            <i class="fas fa-info-circle"></i> Selecciona un cliente de la lista o busca escribiendo arriba
                        </small>
                    </div>
                </div>

                <div class="form-premium-section">
                    <div class="section-header-flex">
                        <h3 class="section-form-title"><i class="fas fa-film"></i> Películas a Alquilar <span class="title-helper">(Puedes marcar varias cintas)</span></h3>
                        <div class="premium-input-search-wrapper short-search">
                            <i class="fas fa-search search-input-icon"></i>
                            <input type="text" id="search_pelicula" placeholder="Buscar película por título..." class="premium-search-input" autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="peliculas-premium-grid" id="peliculas_container">
                        @foreach($peliculas as $pelicula)
                        <div class="pelicula-premium-checkbox" data-titulo="{{ strtolower($pelicula->titulo) }}">
                            <label class="checkbox-interactive-label">
                                <input type="checkbox" name="peliculas[]" value="{{ $pelicula->id }}">
                                <span class="checkbox-custom-indicator"></span>
                                
                                <div class="cd-media-wrapper">
                                    <div class="cd-disc">
                                        <div class="cd-hole"></div>
                                        @if($pelicula->portada)
                                            <img src="{{ $pelicula->portada }}" alt="{{ $pelicula->titulo }}" class="cd-poster-img">
                                        @else
                                            <div class="cd-poster-placeholder">
                                                <i class="fas fa-compact-disc"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="pelicula-premium-info">
                                    <strong class="movie-title-text">{{ $pelicula->titulo }}</strong>
                                    <div class="movie-meta-row">
                                        <span class="movie-price-tag">${{ number_format($pelicula->precio_alquiler, 2) }}</span>
                                        <span class="movie-stock-tag"><i class="fas fa-layer-group"></i> {{ $pelicula->copias_en_estante }} u.</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="alert-warning-premium d-none" id="movie_empty_alert">
                        <div class="alert-icon-box"><i class="fas fa-exclamation-triangle"></i></div>
                        <p>No se encontraron películas que coincidan con los criterios de búsqueda.</p>
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
                                    <option value="efectivo">Efectivo en Caja</option>
                                    <option value="tarjeta">Terminal de Tarjeta</option>
                                    <option value="transferencia">Transferencia de Banco</option>
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
        margin-top: -2rem;
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
        border-color: rgba(255,255,255,0.15);
    }

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

    .section-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .section-header-flex .section-form-title {
        margin-bottom: 0;
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
        gap: 0.6rem;
    }

    .form-premium-group label {
        color: #cdcdcd;
        font-size: 0.88rem;
        font-weight: 600;
    }

    .form-text {
        color: #6c757d !important;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .premium-input-search-wrapper {
        position: relative;
        width: 100%;
    }

    .premium-input-search-wrapper.short-search {
        width: 320px;
    }

    .premium-search-input {
        width: 100%;
        padding: 12px 16px 12px 42px;
        background-color: #111317;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        color: #ffffff;
        font-size: 0.92rem;
        outline: none;
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    .premium-search-input:focus {
        border-color: #ff4b2b;
        box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.15);
    }

    .search-input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 0.9rem;
    }

    .customer-list-container {
        display: block; 
        margin-top: 1rem;
    }

    .premium-scrollable-select {
        width: 100%;
        height: auto !important;
        max-height: 200px;
        overflow-y: auto;
        padding: 8px !important;
        background-color: #111317;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        color: #ffffff;
        font-size: 0.95rem;
        outline: none;
        box-sizing: border-box;
    }

    .premium-scrollable-select option {
        padding: 10px 12px;
        border-radius: 6px;
        margin-bottom: 4px;
        background-color: #111317;
        transition: background 0.15s;
        cursor: pointer;
    }

    .premium-scrollable-select option:hover {
        background-color: #222731 !important;
        color: #ffffff;
    }

    .premium-scrollable-select option:checked {
        background: linear-gradient(45deg, #ff416c, #ff4b2b) !important;
        color: #ffffff !important;
    }

    .premium-form-card select:not([size]) {
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

    .premium-select-wrapper { position: relative; width: 100%; }
    .premium-select-wrapper select:not([size]) { appearance: none; -webkit-appearance: none; padding-right: 40px; }
    .premium-select-wrapper select:not([size]):focus { border-color: #ff4b2b; box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.15); }
    .premium-select-wrapper:not(:has([size]))::after {
        content: '\f078'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        font-size: 0.72rem; color: #6c757d; position: absolute; right: 16px; top: 50%;
        transform: translateY(-50%); pointer-events: none;
    }

    .form-premium-row { display: flex; gap: 1.5rem; }
    .form-premium-row .form-premium-group { flex: 1; }

    .peliculas-premium-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)) !important;
        gap: 1.25rem !important;
        max-height: 420px;
        overflow-y: auto;
        padding-right: 8px;
    }

    .peliculas-premium-grid::-webkit-scrollbar { width: 6px; }
    .peliculas-premium-grid::-webkit-scrollbar-track { background: #111317; border-radius: 10px; }
    .peliculas-premium-grid::-webkit-scrollbar-thumb { background: #2a2e35; border-radius: 10px; }

    .pelicula-premium-checkbox {
        background-color: #111317;
        border: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        transition: all 0.2s ease;
    }

    .checkbox-interactive-label {
        display: flex;
        align-items: center;
        padding: 1.1rem;
        gap: 14px;
        cursor: pointer;
        width: 100%;
        box-sizing: border-box;
        user-select: none;
    }

    .checkbox-interactive-label input[type="checkbox"] { display: none; }

    .checkbox-custom-indicator {
        width: 20px;
        height: 20px;
        border: 2px solid #2a2e35;
        border-radius: 50%;
        position: relative;
        flex-shrink: 0;
        transition: all 0.2s;
    }

    .checkbox-interactive-label input[type="checkbox"]:checked + .checkbox-custom-indicator {
        border-color: #ff4b2b;
        background-color: #ff4b2b;
    }

    .checkbox-interactive-label input[type="checkbox"]:checked + .checkbox-custom-indicator::after {
        content: '\f00c'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        font-size: 0.65rem; color: #ffffff; position: absolute; top: 50%; left: 50%;
        transform: translate(-50%, -50%);
    }

    .cd-media-wrapper {
        flex-shrink: 0;
        width: 65px;
        height: 65px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cd-disc {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        position: relative;
        overflow: hidden;
        border: 2px solid #252932;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5), inset 0 0 8px rgba(255, 255, 255, 0.1);
        background-color: #1a1d24;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .cd-hole {
        position: absolute;
        width: 14px;
        height: 14px;
        background-color: #111317; 
        border: 2.5px solid rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.8);
    }

    .cd-poster-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .cd-poster-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #222731;
        color: #495057;
        font-size: 1.4rem;
    }

    .pelicula-premium-checkbox:hover .cd-disc { transform: rotate(45deg); }
    .pelicula-premium-checkbox:has(input[type="checkbox"]:checked) .cd-disc { border-color: #ff4b2b; box-shadow: 0 0 12px rgba(255, 75, 43, 0.4); }
    .pelicula-premium-checkbox:has(input[type="checkbox"]:checked) { border-color: rgba(255, 75, 43, 0.3); background-color: rgba(255, 75, 43, 0.02); }
    .pelicula-premium-checkbox:hover { border-color: rgba(255, 255, 255, 0.1); background-color: #14171d; }

    .pelicula-premium-info { display: flex; flex-direction: column; gap: 0.25rem; flex-grow: 1; overflow: hidden; }
    .movie-title-text { color: #ffffff; font-size: 0.95rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .movie-meta-row { display: flex; justify-content: space-between; align-items: center; }
    .movie-price-tag { color: #2ec4b6; font-weight: 700; font-size: 0.88rem; font-family: monospace; }
    .movie-stock-tag { color: #6c757d; font-size: 0.78rem; font-weight: 500; }

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
        margin-top: 1rem;
    }

    .alert-icon-box { font-size: 1.1rem; }
    .alert-warning-premium.d-none { display: none !important; }

    .form-premium-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
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

    .btn-form-cancel:hover { background-color: #343a44; color: #ffffff !important; }

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

    /* Estilo para el input de búsqueda cuando tiene un cliente seleccionado */
    .premium-search-input.cliente-seleccionado {
        border-color: #2ec4b6;
        background-color: #1a2a2a;
        color: #2ec4b6;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .create-page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .btn-premium-back, .form-premium-actions { width: 100%; }
        .form-premium-row { flex-direction: column; gap: 1.5rem; }
        .premium-form-card { padding: 1.5rem; }
        .btn-form-save, .btn-form-cancel { flex: 1; text-align: center; justify-content: center; }
        .premium-input-search-wrapper.short-search { width: 100%; }
        .section-header-flex { flex-direction: column; align-items: flex-start; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 🔍 1. Lógica del Buscador de Clientes Dinámico - CON SELECCIÓN VISIBLE
    const searchCliente = document.getElementById('search_cliente');
    const selectCliente = document.getElementById('id_usuario');
    const optionsCliente = selectCliente.querySelectorAll('option');

    // Mostrar todos los clientes inicialmente
    optionsCliente.forEach(option => {
        option.style.display = 'block';
    });

    // Función para actualizar el input de búsqueda con el cliente seleccionado
    function actualizarInputConClienteSeleccionado() {
        const selectedOption = selectCliente.options[selectCliente.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const nombreCliente = selectedOption.getAttribute('data-name');
            const emailCliente = selectedOption.getAttribute('data-email');
            searchCliente.value = `${nombreCliente} — ${emailCliente}`;
            searchCliente.classList.add('cliente-seleccionado');
            
            // Opcional: Mostrar un pequeño indicador visual
            searchCliente.style.borderColor = '#2ec4b6';
            searchCliente.style.backgroundColor = '#1a2a2a';
            searchCliente.style.color = '#2ec4b6';
        } else {
            searchCliente.value = '';
            searchCliente.classList.remove('cliente-seleccionado');
            searchCliente.style.borderColor = '';
            searchCliente.style.backgroundColor = '';
            searchCliente.style.color = '';
        }
    }

    // Evento cuando se selecciona una opción del select
    selectCliente.addEventListener('change', function() {
        actualizarInputConClienteSeleccionado();
        
        // Limpiar filtro de búsqueda y mostrar todos los clientes
        searchCliente.value = '';
        optionsCliente.forEach(option => {
            option.style.display = 'block';
        });
        
        // Volver a poner el valor del cliente seleccionado en el input
        actualizarInputConClienteSeleccionado();
    });

    // Filtrar clientes mientras se escribe en la búsqueda
    searchCliente.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase().trim();
        
        // Si el buscador tiene texto, remover la clase de seleccionado
        if (term !== '') {
            searchCliente.classList.remove('cliente-seleccionado');
            searchCliente.style.borderColor = '';
            searchCliente.style.backgroundColor = '';
            searchCliente.style.color = '';
        }
        
        if (term === '') {
            // Si está vacío, mostrar todos y resetear selección
            optionsCliente.forEach(option => {
                option.style.display = 'block';
            });
            // No resetear la selección automáticamente para no perder el cliente elegido
            return;
        }

        // Filtrar opciones según el término de búsqueda
        let hayCoincidencias = false;
        optionsCliente.forEach(option => {
            const searchData = option.getAttribute('data-search');
            if (searchData && searchData.includes(term)) {
                option.style.display = 'block';
                hayCoincidencias = true;
            } else {
                option.style.display = 'none';
            }
        });
        
        // Si hay una sola coincidencia y el usuario presiona Enter, seleccionarla automáticamente
        const opcionesVisibles = Array.from(optionsCliente).filter(opt => opt.style.display !== 'none');
        if (opcionesVisibles.length === 1 && opcionesVisibles[0].value) {
            // Auto-seleccionar la única coincidencia (opcional)
            // selectCliente.value = opcionesVisibles[0].value;
            // actualizarInputConClienteSeleccionado();
        }
    });

    // Permitir seleccionar con Enter después de buscar
    searchCliente.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const opcionesVisibles = Array.from(optionsCliente).filter(opt => opt.style.display !== 'none' && opt.value);
            if (opcionesVisibles.length === 1) {
                selectCliente.value = opcionesVisibles[0].value;
                actualizarInputConClienteSeleccionado();
            } else if (opcionesVisibles.length > 1) {
                // Mostrar un pequeño mensaje o simplemente hacer foco en el select
                selectCliente.focus();
            }
        }
    });

    // Si ya hay un cliente seleccionado por defecto (por ejemplo, después de un error de validación)
    if (selectCliente.value) {
        actualizarInputConClienteSeleccionado();
    }

    // 🔍 2. Lógica del Buscador de Películas (Grid Checkboxes)
    const searchPelicula = document.getElementById('search_pelicula');
    const movieCards = document.querySelectorAll('.pelicula-premium-checkbox');
    const emptyAlert = document.getElementById('movie_empty_alert');

    if (searchPelicula) {
        searchPelicula.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase().trim();
            let visibleCount = 0;

            movieCards.forEach(card => {
                const titulo = card.getAttribute('data-titulo');
                if (titulo && titulo.includes(term)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount === 0 && term !== '') {
                emptyAlert.classList.remove('d-none');
            } else {
                emptyAlert.classList.add('d-none');
            }
        });
    }
});
</script>
@endsection