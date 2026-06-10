@extends('layouts.app')

@section('title', 'Agregar Película')

@section('content')
<div class="admin-create-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="create-page-header">
            <div class="header-left">
                <h1 class="create-main-title"><i class="fas fa-magic"></i> Agregar Nueva Película</h1>
                <p class="create-main-subtitle">Inserta un título al catálogo global. Puedes usar el buscador integrado para auto-completar los metadatos de la obra.</p>
            </div>
            <a href="{{ route('admin.peliculas.index') }}" class="btn-premium-back">
                <i class="fas fa-arrow-left"></i> Volver al Catálogo
            </a>
        </div>

        <div class="premium-form-card">
            <div class="form-layout-split">
                
                <form method="POST" action="{{ route('admin.peliculas.store') }}" class="premium-interactive-form" id="movieCreateForm">
                    @csrf
                    
                    <div class="form-premium-row">
                        <div class="form-premium-group">
                            <label for="titulo"><i class="fas fa-heading"></i> Título de la Película *</label>
                            <input type="text" name="titulo" id="titulo" placeholder="Ej: Interestelar" required value="{{ old('titulo') }}" oninput="sincronizarTituloHub()">
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
                        <label for="portada"><i class="fas fa-image"></i> Enlace / URL de la Portada *</label>
                        <div class="input-action-mix-box">
                            <input type="url" name="portada" id="portada" placeholder="https://ejemplo.com/imagen.jpg" required value="{{ old('portada') }}" oninput="actualizarPrevisualizacionManual()">
                            <button type="button" class="btn-hub-trigger" onclick="openHubModal()">
                                <i class="fas fa-search-plus"></i> Auto-completar Ficha
                            </button>
                        </div>
                        @error('portada')
                            <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-premium-group">
                        <label for="sinopsis"><i class="fas fa-align-left"></i> Sinopsis o Resumen Argumental *</label>
                        <textarea name="sinopsis" id="sinopsis" rows="4" placeholder="Escribe o auto-completa la descripción sobre la trama de la película..." required>{{ old('sinopsis') }}</textarea>
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
                        <button type="submit" form="movieCreateForm" class="btn-form-save">
                            <i class="fas fa-save"></i> Guardar Película
                        </button>
                    </div>
                </form>

                <div class="form-preview-aside">
                    <label class="aside-preview-label"><i class="fas fa-eye"></i> Póster Seleccionado</label>
                    <div class="live-poster-frame">
                        <img id="live-poster-img" src="" alt="Previsualización" style="display: none;">
                        <div id="live-poster-placeholder" class="poster-empty-placeholder">
                            <i class="fas fa-film"></i>
                            <span>Campos vacíos</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<div id="hubPortadasModal" class="modal-premium-overlay" onclick="closeHubModal()">
    <div class="modal-premium-content hub-modal-box" onclick="event.stopPropagation();">
        <div class="modal-premium-header">
            <h3><i class="fas fa-images"></i> Selecciona la Portada de Referencia</h3>
            <span class="close-modal-btn" onclick="closeHubModal()">&times;</span>
        </div>
        
        <div class="hub-search-bar-box">
            <input type="text" id="hub-search-input" placeholder="Escribe el nombre de la película o anime...">
            <button type="button" id="hub-search-btn" class="btn-hub-search" onclick="ejecutarBusquedaHub()">Escanear Servidores</button>
        </div>

        <div class="modal-scroll-body hub-results-body">
            <div id="gemini-loading-overlay" class="gemini-ai-loader" style="display: none;">
                <i class="fas fa-circle-notch fa-spin"></i>
                <h4>Sincronizando información de la obra...</h4>
                <p>Por favor espera un momento mientras se estructuran los detalles técnicos.</p>
            </div>

            <div class="hub-movies-grid" id="hub-movies-grid">
                <div class="hub-empty-state">
                    <i class="fas fa-images"></i>
                    <p>Ingresa el nombre de la obra para buscar su portada física de referencia.</p>
                </div>
            </div>
        </div>

        <div class="modal-premium-footer">
            <button type="button" class="btn-modal-cancel" onclick="closeHubModal()">Cancelar</button>
        </div>
    </div>
</div>

<div id="hub-toast" class="hub-toast-alert">¡Información importada exitosamente! 🎯</div>

<style>
    .admin-create-wrapper { background-color: #0f1115; min-height: 100vh; margin-top: -2rem; padding: 3rem 0 5rem 0; color: #ffffff; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
    .create-page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; gap: 1.5rem; }
    .create-main-title { font-size: 2.6rem; font-weight: 800; margin: 0 0 0.4rem 0; background: linear-gradient(45deg, #ff416c, #ff4b2b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .create-main-title i { color: #ff4b2b; -webkit-text-fill-color: initial; margin-right: 12px; }
    .create-main-subtitle { color: #6c757d; font-size: 0.98rem; margin: 0; }
    .btn-premium-back { background-color: #1a1d24; color: #b3b3b3 !important; border: 1px solid rgba(255,255,255,0.05); padding: 12px 20px; border-radius: 8px; font-weight: 600; font-size: 0.88rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease; }
    .btn-premium-back:hover { background-color: #242933; color: #ffffff !important; }
    .premium-form-card { background-color: #1a1d24; border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.02); }
    .form-layout-split { display: grid; grid-template-columns: 1fr 220px; gap: 2.5rem; align-items: start; }
    .premium-interactive-form { display: flex; flex-direction: column; gap: 1.25rem; }
    .form-premium-row { display: flex; gap: 1.5rem; }
    .form-premium-row .form-premium-group { flex: 1; }
    .form-premium-group { display: flex; flex-direction: column; gap: 0.5rem; }
    .form-premium-group label { color: #cdcdcd; font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; gap: 6px; }
    .form-premium-group label i { color: #6c757d; font-size: 0.9rem; width: 16px; text-align: center; }
    .form-premium-group input, .form-premium-group textarea, .premium-select-wrapper select { width: 100%; padding: 12px 16px; background-color: #111317; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 10px; color: #ffffff; font-size: 0.95rem; outline: none; box-sizing: border-box; font-family: inherit; transition: all 0.2s ease; }
    .form-premium-group textarea { resize: vertical; }
    .form-premium-group input:focus, .form-premium-group textarea:focus, .premium-select-wrapper select:focus { border-color: #ff4b2b; background-color: #14171c; box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.15); }
    .input-action-mix-box { display: flex; gap: 10px; width: 100%; }
    .input-action-mix-box input { flex: 1; }
    
    .btn-hub-trigger { background: linear-gradient(45deg, #2ec4b6, #009688); color: #ffffff; border: none; padding: 0 18px; border-radius: 10px; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 6px; flex-shrink: 0; box-shadow: 0 4px 12px rgba(46, 196, 182, 0.2); transition: transform 0.2s; }
    .btn-hub-trigger:hover { transform: translateY(-1px); }
    
    .premium-select-wrapper { position: relative; width: 100%; }
    .premium-select-wrapper select { appearance: none; -webkit-appearance: none; padding-right: 40px; cursor: pointer; }
    .premium-select-wrapper::after { content: '\f078'; font-family: 'Font Awesome 5 Free'; font-weight: 900; font-size: 0.72rem; color: #6c757d; position: absolute; right: 16px; top: 50%; transform: translateY(-50%); pointer-events: none; }
    .field-error-msg { color: #ef5350; font-size: 0.8rem; font-weight: 600; margin-top: 0.1rem; display: flex; align-items: center; gap: 5px; }
    .form-preview-aside { display: flex; flex-direction: column; gap: 0.5rem; width: 100%; }
    .aside-preview-label { color: #6c757d; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .live-poster-frame { width: 100%; aspect-ratio: 2 / 3; background-color: #111317; border-radius: 14px; border: 1px solid rgba(255,255,255,0.03); overflow: hidden; box-shadow: 0 6px 20px rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; }
    .live-poster-frame img { width: 100%; height: 100%; object-fit: cover; animation: fadeIn 0.3s ease; }
    .poster-empty-placeholder { display: flex; flex-direction: column; align-items: center; gap: 10px; color: #2e3440; padding: 1rem; text-align: center; }
    .poster-empty-placeholder i { font-size: 3.5rem; color: #232833; }
    .poster-empty-placeholder span { font-size: 0.82rem; font-weight: 600; color: #434c5e; }
    .form-premium-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.04); padding-top: 1.5rem; }
    .btn-form-cancel { background-color: #2a2e35; color: #b3b3b3 !important; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.92rem; transition: all 0.2s; }
    .btn-form-cancel:hover { background-color: #343a44; color: #ffffff !important; }
    .btn-form-save { background: linear-gradient(45deg, #ff416c, #ff4b2b); color: #ffffff; border: none; padding: 12px 26px; border-radius: 10px; font-weight: 600; font-size: 0.92rem; cursor: pointer; box-shadow: 0 4px 15px rgba(255, 65, 108, 0.2); transition: all 0.2s ease; }
    .btn-form-save:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(255, 65, 108, 0.3); }

    .hub-modal-box { max-width: 900px !important; max-height: 85vh; display: flex; flex-direction: column; border-radius: 16px; position: relative; }
    .hub-search-bar-box { display: flex; gap: 12px; padding: 1.25rem 1.5rem; background-color: #111317; border-bottom: 1px solid rgba(255,255,255,0.02); }
    .hub-search-bar-box input { flex: 1; padding: 11px 16px; background-color: #1a1d24; border: 1px solid #2d3139; border-radius: 8px; color: #fff; font-size: 0.95rem; outline: none; }
    .hub-search-bar-box input:focus { border-color: #ff4b2b; }
    .btn-hub-search { background-color: #ff4b2b; color: white; border: none; padding: 0 24px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.92rem; transition: background 0.2s; }
    .btn-hub-search:hover { background-color: #e63917; }
    .hub-results-body { padding: 1.5rem !important; background-color: #14161d; overflow-y: auto; flex-grow: 1; position: relative; }
    .hub-movies-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; width: 100%; }
    .hub-movie-card { background-color: #1a1d24; border-radius: 12px; overflow: hidden; border: 1px solid #262b35; display: flex; flex-direction: column; position: relative; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
    .hub-badge-source { position: absolute; top: 10px; right: 10px; padding: 4px 8px; border-radius: 4px; font-size: 9px; font-weight: 800; text-transform: uppercase; color: #fff; z-index: 10; letter-spacing: 0.5px; }
    .bg-omdb { background-color: #e11d48; }
    .bg-tmdb { background-color: #032541; }
    .bg-jikan { background-color: #6366f1; }
    .hub-poster-container { width: 100%; aspect-ratio: 2 / 3; background-color: #000; overflow: hidden; }
    .hub-poster-container img { width: 100%; height: 100%; object-fit: cover; }
    .hub-movie-info { padding: 12px; display: flex; flex-direction: column; flex-grow: 1; justify-content: space-between; gap: 10px; }
    .hub-movie-info h4 { margin: 0; font-size: 0.9rem; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    
    .btn-hub-use-url { width: 100%; padding: 8px 0; background: linear-gradient(45deg, #2ec4b6, #009688); color: white; border: none; border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; transition: all 0.2s; }
    .btn-hub-use-url:hover { transform: scale(1.02); }
    
    .gemini-ai-loader { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(20, 22, 29, 0.96); z-index: 100; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; color: #fff; padding: 2rem; }
    .gemini-ai-loader i { font-size: 3.5rem; color: #2ec4b6; margin-bottom: 1.5rem; }
    .gemini-ai-loader h4 { font-size: 1.3rem; margin: 0 0 0.5rem 0; font-weight: 700; }
    .gemini-ai-loader p { color: #6c757d; font-size: 0.9rem; margin: 0; }

    .hub-empty-state { text-align: center; padding: 5rem 0; grid-column: 1 / -1; color: #3e4451; }
    .hub-empty-state i { font-size: 3rem; margin-bottom: 1rem; }
    .hub-empty-state p { font-size: 0.95rem; font-weight: 600; margin: 0; }
    .modal-premium-footer { background-color: #121419; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid rgba(255,255,255,0.04); padding: 1rem 1.5rem; flex-shrink: 0; }
    
    .hub-toast-alert { position: fixed; bottom: 25px; right: 25px; background-color: #009688; color: white; padding: 14px 28px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; box-shadow: 0 10px 30px rgba(0, 150, 136, 0.3); display: none; z-index: 3000; animation: fadeIn 0.2s ease; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @media (max-width: 900px) { .form-layout-split { grid-template-columns: 1fr; gap: 2rem; } .form-preview-aside { max-width: 200px; margin: 0 auto; } }
</style>

@push('scripts')
<script>
    const GEMINI_API_KEY = "{!! env('GEMINI_API_KEY') !!}";
    const OMDB_API_KEY   = "{!! env('OMDB_API_KEY') !!}"; 
    const TMDB_API_KEY   = "{!! env('TMDB_API_KEY') !!}"; 
    const TMDB_IMG_BASE  = "{!! env('TMDB_IMG_BASE', 'https://image.tmdb.org/t/p/w500') !!}";

    function sincronizarTituloHub() {
        const tituloVal = document.getElementById('titulo').value;
        document.getElementById('hub-search-input').value = tituloVal;
    }

    function openHubModal() {
        sincronizarTituloHub();
        document.getElementById('hubPortadasModal').style.display = 'block';
    }

    function closeHubModal() { 
        document.getElementById('hubPortadasModal').style.display = 'none'; 
        document.getElementById('gemini-loading-overlay').style.display = 'none';
    }

    async function ejecutarBusquedaHub() {
        const query = document.getElementById('hub-search-input').value.trim();
        const grid = document.getElementById('hub-movies-grid');
        if (!query) return;

        grid.innerHTML = '<div class="hub-empty-state"><i class="fas fa-circle-notch fa-spin"></i><p>Escaneando servidores de arte físico de cine y anime...</p></div>';

        const encodedQuery = encodeURIComponent(query);
        const urlOmdb = `https://www.omdbapi.com/?s=${encodedQuery}&apikey=${OMDB_API_KEY}`;
        const urlTmdb = `https://api.themoviedb.org/3/search/movie?api_key=${TMDB_API_KEY}&query=${encodedQuery}&include_adult=false&language=es-MX`;
        const urlJikan = `https://api.jikan.moe/v4/anime?q=${encodedQuery}&limit=10`;

        try {
            const [resOmdb, resTmdb, resJikan] = await Promise.all([
                fetch(urlOmdb).then(r => r.json()).catch(() => ({ Response: "False" })),
                fetch(urlTmdb).then(r => r.json()).catch(() => ({ results: [] })),
                fetch(urlJikan).then(r => r.json()).catch(() => ({ data: [] }))
            ]);

            let portadasUnificadas = [];

            if (resOmdb.Response === "True" && resOmdb.Search) {
                resOmdb.Search.forEach(movie => {
                    if (movie.Poster && movie.Poster !== "N/A") {
                        portadasUnificadas.push({ id_ref: movie.imdbID, titulo: movie.Title, anio: movie.Year, portada: movie.Poster, origen: 'omdb' });
                    }
                });
            }

            if (resTmdb.results && resTmdb.results.length > 0) {
                resTmdb.results.forEach(movie => {
                    if (movie.poster_path) {
                        portadasUnificadas.push({ id_ref: movie.id, titulo: movie.title, anio: movie.release_date ? movie.release_date.split('-')[0] : 'N/A', portada: `${TMDB_IMG_BASE}${movie.poster_path}`, origen: 'tmdb' });
                    }
                });
            }

            if (resJikan.data && resJikan.data.length > 0) {
                resJikan.data.forEach(anime => {
                    const imgUrl = anime.images.webp?.large_image_url || anime.images.jpg?.large_image_url;
                    if (imgUrl) {
                        portadasUnificadas.push({ id_ref: anime.mal_id, titulo: anime.title, anio: anime.aired?.from ? anime.aired.from.split('-')[0] : 'N/A', portada: imgUrl, origen: 'jikan' });
                    }
                });
            }

            desplegarResultadosHub(portadasUnificadas);

        } catch (error) {
            console.error(error);
            grid.innerHTML = '<div class="hub-empty-state"><i class="fas fa-exclamation-circle"></i><p>Error temporal al sincronizar las portadas.</p></div>';
        }
    }

    function desplegarResultadosHub(movies) {
        const grid = document.getElementById('hub-movies-grid');
        grid.innerHTML = '';

        if (movies.length === 0) {
            grid.innerHTML = '<div class="hub-empty-state"><i class="fas fa-video-slash"></i><p>No se encontraron posters físicos.</p></div>';
            return;
        }

        movies.forEach(movie => {
            const card = document.createElement('div');
            card.classList.add('hub-movie-card');
            
            const tituloEscapado = movie.titulo.replace(/'/g, "\\'").replace(/"/g, '&quot;');

            card.innerHTML = `
                <span class="hub-badge-source bg-${movie.origen}">${movie.origen}</span>
                <div class="hub-poster-container">
                    <img src="${movie.portada}" alt="Poster" loading="lazy">
                </div>
                <div class="hub-movie-info">
                    <h4>${movie.titulo} (${movie.anio})</h4>
                    <button type="button" class="btn-hub-use-url" onclick="ejecutarSincronizacionFicha('${movie.portada}', '${movie.id_ref}', '${movie.origen}', '${tituloEscapado}', '${movie.anio}')">
                        🎯 Seleccionar esta obra
                    </button>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    function normalizarGeneroCinematografico(nombre) {
        if (!nombre) return "Acción";
        const n = nombre.toLowerCase();
        if (n.includes('action') || n.includes('acción') || n.includes('adventure') || n.includes('aventura')) return 'Acción';
        if (n.includes('comedy') || n.includes('comedia')) return 'Comedia';
        if (n.includes('drama')) return 'Drama';
        if (n.includes('sci-fi') || n.includes('science fiction') || n.includes('ciencia ficción')) return 'Ciencia Ficción';
        if (n.includes('horror') || n.includes('terror')) return 'Terror';
        if (n.includes('romance') || n.includes('romántica')) return 'Romance';
        if (n.includes('animation') || n.includes('animación')) return 'Animación';
        if (n.includes('super') || n.includes('hero') || n.includes('superhéroes')) return 'Superhéroes';
        return 'Acción';
    }

    async function ejecutarSincronizacionFicha(portadaUrl, idRef, origen, tituloBase, anioBase) {
        document.getElementById('gemini-loading-overlay').style.display = 'flex';

        let tituloFinal = tituloBase;
        let directorFinal = 'Desconocido';
        let anioFinal = anioBase ? anioBase.substring(0,4) : new Date().getFullYear();
        let sinopsisOriginal = '';
        let generoFinal = 'Acción';

        try {
            if (origen === 'omdb') {
                const detailed = await fetch(`https://www.omdbapi.com/?i=${idRef}&plot=full&apikey=${OMDB_API_KEY}`).then(r => r.json());
                if (detailed.Response === "True") {
                    tituloFinal = detailed.Title || tituloFinal;
                    directorFinal = detailed.Director && detailed.Director !== "N/A" ? detailed.Director : 'Desconocido';
                    sinopsisOriginal = detailed.Plot && detailed.Plot !== "N/A" ? detailed.Plot : '';
                    generoFinal = normalizarGeneroCinematografico(detailed.Genre);
                }
            } 
            else if (origen === 'tmdb') {
                const detailed = await fetch(`https://api.themoviedb.org/3/movie/${idRef}?api_key=${TMDB_API_KEY}&language=es-MX`).then(r => r.json());
                tituloFinal = detailed.title || tituloFinal;
                sinopsisOriginal = detailed.overview || '';
                if (detailed.genres && detailed.genres.length > 0) {
                    generoFinal = normalizarGeneroCinematografico(detailed.genres[0].name);
                }

                const credits = await fetch(`https://api.themoviedb.org/3/movie/${idRef}/credits?api_key=${TMDB_API_KEY}`).then(r => r.json());
                if (credits.crew) {
                    const directorObj = credits.crew.find(member => member.job === 'Director');
                    if (directorObj) directorFinal = directorObj.name;
                }
            } 
            else if (origen === 'jikan') {
                const detailed = await fetch(`https://api.jikan.moe/v4/anime/${idRef}`).then(r => r.json());
                if (detailed.data) {
                    tituloFinal = detailed.data.title_english || detailed.data.title || tituloFinal;
                    sinopsisOriginal = detailed.data.synopsis || '';
                    generoFinal = 'Animación';
                    if (detailed.data.studios && detailed.data.studios.length > 0) {
                        directorFinal = detailed.data.studios[0].name;
                    }
                }
            }

            sinopsisOriginal = sinopsisOriginal.replace(/[\r\n]+/g, " ").replace(/"/g, '\\"').trim();

            document.getElementById('titulo').value = tituloFinal;
            document.getElementById('director').value = directorFinal;
            document.getElementById('año').value = anioFinal;
            document.getElementById('portada').value = portadaUrl;
            document.getElementById('genero').value = generoFinal;

            if (sinopsisOriginal.length > 0) {
                try {
                    const promptTraduccion = `Traduce al español el resumen de la película "${tituloFinal}": "${sinopsisOriginal}". Devuelve únicamente la traducción limpia, sin comillas ni textos extras.`;

                    const responseAi = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=${GEMINI_API_KEY}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            contents: [{ parts: [{ text: promptTraduccion }] }]
                        })
                    });

                    const dataAi = await responseAi.json();
                    
                    if (dataAi.candidates && dataAi.candidates[0].content && dataAi.candidates[0].content.parts) {
                        let sinopsisTraducida = dataAi.candidates[0].content.parts[0].text.trim();
                        sinopsisTraducida = sinopsisTraducida.replace(/^"+|"+$/g, '').replace(/```/g, '').trim();
                        document.getElementById('sinopsis').value = sinopsisTraducida;
                    } else {
                        document.getElementById('sinopsis').value = sinopsisOriginal.replace(/\\"/g, '"');
                    }

                } catch (aiErr) {
                    console.error("Fallo al traducir con Gemini:", aiErr);
                    document.getElementById('sinopsis').value = sinopsisOriginal.replace(/\\"/g, '"'); 
                }
            } else {
                document.getElementById('sinopsis').value = 'Sin descripción disponible para este título.';
            }

            const imgFrame = document.getElementById('live-poster-img');
            const placeholder = document.getElementById('live-poster-placeholder');
            imgFrame.src = portadaUrl;
            imgFrame.style.display = 'block';
            placeholder.style.display = 'none';

        } catch (error) {
            console.error("Fallo general en recolección de metadatos:", error);
        } finally {
            closeHubModal();
            const toast = document.getElementById('hub-toast');
            toast.style.display = 'block';
            setTimeout(() => { toast.style.display = 'none'; }, 2500);
        }
    }

    function actualizarPrevisualizacionManual() {
        const url = document.getElementById('portada').value.trim();
        const imgFrame = document.getElementById('live-poster-img');
        const placeholder = document.getElementById('live-poster-placeholder');

        if(url) {
            imgFrame.src = url;
            imgFrame.style.display = 'block';
            placeholder.style.display = 'none';
        } else {
            imgFrame.src = '';
            imgFrame.style.display = 'none';
            placeholder.style.display = 'flex';
        }
    }

    document.getElementById('hub-search-input').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            ejecutarBusquedaHub();
        }
    });
</script>
@endpush
@endsection