@extends('layouts.app')

@section('title', 'Catálogo de Películas')

@section('content')
<div class="catalog-dark-wrapper">
    <div class="container-fluid px-4 px-md-5">

        <div class="catalog-header">
            <h1 class="catalog-title">
                <i class="fas fa-film"></i> Catálogo de Películas
            </h1>
            <p class="catalog-subtitle">Busca tus títulos favoritos y verifica la disponibilidad de copias físicas en nuestra sucursal de Jayaque.</p>

            <div class="search-box-container">
                <span class="search-icon"><i class="fas fa-search"></i></span>
                <input type="text" id="search" placeholder="Buscar películas por título, género o director..." onkeyup="buscarPeliculas()">
            </div>
        </div>

        <div id="movies-container">
            <div class="movies-streaming-grid">
                @foreach($peliculas as $pelicula)
                <div class="movie-premium-card" onclick="openPreviewModal({{ json_encode($pelicula) }}, '{{ auth()->check() ? auth()->user()->rol : 'invitado' }}')">

                    <div class="movie-premium-poster">
                        @if($pelicula->portada)
                        <img src="{{ $pelicula->portada }}" alt="Portada de {{ $pelicula->titulo }}">
                        @else
                        <div class="poster-placeholder">
                            <i class="fas fa-video"></i>
                        </div>
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

                        <div class="movie-premium-actions" onclick="event.stopPropagation();">
                            @auth
                                @if(auth()->user()->rol === 'cliente')
                                <a href="{{ route('alquilar', $pelicula) }}" class="btn-premium-action btn-rent {{ $pelicula->copias_en_estante == 0 ? 'disabled-action' : '' }}">
                                    <i class="fas fa-shopping-cart"></i> Reservar
                                </a>
                                @else
                                <a href="{{ route('prestamos.create', ['pelicula_id' => $pelicula->id]) }}" class="btn-premium-action btn-staff {{ $pelicula->copias_en_estante == 0 ? 'disabled-action' : '' }}">
                                    <i class="fas fa-cash-register"></i> Alquilar en Mostrador
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

<div id="previewMovieModal" class="modal-premium-overlay" onclick="closePreviewModal()">
    <div class="modal-premium-content movie-preview-box" onclick="event.stopPropagation();">
        <div class="modal-premium-header">
            <h3 id="preview-title"><i class="fas fa-info-circle"></i> Título de Película</h3>
            <span class="close-modal-btn" onclick="closePreviewModal()">&times;</span>
        </div>
        
        <div class="modal-scroll-body">
            <div class="preview-layout-grid">
                <div class="preview-poster-box">
                    <img id="preview-img" src="" alt="Portada">
                </div>
                <div class="preview-data-box">
                    <span id="preview-genre" class="movie-premium-genre">GÉNERO</span>
                    <div class="meta-pill-row">
                        <span class="meta-pill"><i class="fas fa-dollar-sign"></i> Alquiler: <strong id="preview-price">0.00</strong></span>
                        <span class="meta-pill" id="preview-stock-pill">Stock: <strong id="preview-stock">0</strong></span>
                    </div>
                    <label class="info-label">Sinopsis / Resumen Ejecutivo:</label>
                    <p id="preview-synopsis" class="preview-text-synopsis">No hay sinopsis disponible para este título.</p>
                </div>
            </div>
        </div>

        <div class="modal-premium-footer">
            <button type="button" class="btn-modal-cancel" onclick="closePreviewModal()">Cerrar Vista</button>
            <div id="modal-action-placement"></div>
        </div>
    </div>
</div>

<style>
    .catalog-dark-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem;
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

    .search-box-container input:focus { background-color: #22262f; }

    .movies-streaming-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)) !important;
        gap: 2.5rem !important;
        width: 100% !important;
    }

    .movie-premium-card {
        background-color: #1a1d24;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.3s ease;
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.03);
        cursor: pointer;
    }

    .movie-premium-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(255, 75, 43, 0.25);
    }

    .movie-premium-poster {
        width: 100%;
        aspect-ratio: 2 / 3;
        background-color: #111317;
        position: relative;
        overflow: hidden;
    }

    .movie-premium-poster img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .movie-premium-card:hover .movie-premium-poster img {
        transform: scale(1.04);
    }

    .poster-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .poster-placeholder i { color: #2b303c; font-size: 4rem; }

    .movie-premium-price {
        position: absolute;
        top: 15px; right: 15px;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff;
        padding: 6px 14px;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        z-index: 5;
    }

    .movie-premium-body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .movie-premium-genre {
        font-size: 0.72rem;
        font-weight: 700;
        color: #ff4b2b;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-family: monospace;
        margin-bottom: 0.4rem;
        display: block;
    }

    .movie-premium-title {
        color: #ffffff;
        font-size: 1.15rem;
        font-weight: 600;
        margin: 0 0 0.8rem 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .movie-premium-stock-box {
        display: flex;
        align-items: center;
        margin-top: auto;
        margin-bottom: 1.25rem;
    }

    .stock-dot { width: 8px; height: 8px; border-radius: 50%; margin-right: 8px; }
    .dot-available { background-color: #2ec4b6; }
    .dot-out { background-color: #e63946; }
    .stock-text { font-size: 0.85rem; font-weight: 600; }
    .text-available { color: #2ec4b6; }
    .text-out { color: #e63946; }

    .btn-premium-action {
        display: block; width: 100%; text-align: center; padding: 11px 0;
        font-weight: 600; font-size: 0.88rem; border-radius: 10px; text-decoration: none;
        transition: background 0.2s, transform 0.1s; border: none; cursor: pointer;
    }

    .btn-rent {
        background: linear-gradient(45deg, #00c6ff, #0072ff);
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 114, 255, 0.2);
    }
    .btn-rent:hover { background: linear-gradient(45deg, #1ad1ff, #1a80ff); }

    .btn-staff {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.2);
    }
    .btn-staff:hover { background: linear-gradient(45deg, #ff527b, #ff5e43); }

    .btn-guest { background-color: #2a2e35; color: #b3b3b3 !important; }
    .btn-guest:hover { background-color: #343a44; color: #ffffff !important; }
    .disabled-action { pointer-events: none !important; opacity: 0.3 !important; box-shadow: none !important; }

    .modal-premium-overlay {
        display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%;
        background: rgba(10, 11, 14, 0.85); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    }

    .modal-premium-content {
        background-color: #1a1d24; margin: 5% auto; width: 92%; max-width: 680px;
        border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.6); border: 1px solid rgba(255,255,255,0.03);
        animation: modalSlideDown 0.25s cubic-bezier(0.165, 0.84, 0.44, 1); overflow: hidden;
        max-height: 85vh; display: flex; flex-direction: column;
    }

    .modal-scroll-body { overflow-y: auto; flex-grow: 1; padding: 1.5rem; }
    
    .preview-layout-grid {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 1.5rem;
    }

    .preview-poster-box {
        width: 100%; aspect-ratio: 2 / 3; border-radius: 10px; overflow: hidden;
        background-color: #111317; border: 1px solid rgba(255,255,255,0.03);
    }
    .preview-poster-box img { width: 100%; height: 100%; object-fit: cover; }

    .preview-data-box { display: flex; flex-direction: column; }
    .meta-pill-row { display: flex; gap: 0.75rem; margin: 1rem 0 1.5rem 0; flex-wrap: wrap; }
    .meta-pill {
        background-color: #111317; padding: 6px 14px; border-radius: 30px;
        font-size: 0.85rem; color: #cdcdcd; border: 1px solid rgba(255,255,255,0.03);
    }
    .meta-pill strong { color: #ffffff; }
    .info-label { font-size: 0.85rem; color: #6c757d; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem; }
    .preview-text-synopsis { color: #d1d1d1; line-height: 1.6; font-size: 0.95rem; margin: 0; }

    .modal-premium-footer {
        background-color: #121419; display: flex; justify-content: flex-end;
        gap: 0.75rem; border-top: 1px solid rgba(255,255,255,0.04); padding: 1rem 1.5rem; flex-shrink: 0;
    }

    .btn-modal-cancel {
        background-color: #2a2e35; color: #b3b3b3; border: none; padding: 11px 20px;
        border-radius: 8px; font-weight: 600; font-size: 0.88rem; cursor: pointer; transition: all 0.2s;
    }
    .btn-modal-cancel:hover { background-color: #343a44; color: #ffffff; }

    @media (max-width: 600px) {
        .preview-layout-grid { grid-template-columns: 1fr; }
        .preview-poster-box { max-width: 160px; margin: 0 auto; }
    }

    .modal-premium-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    padding: 1.5rem 1.75rem !important; /* Separa el título y la X de los bordes */
    border-bottom: 1px solid rgba(255, 255, 255, 0.04) !important;
    background-color: #121419 !important; /* Un fondo ligeramente más oscuro para dar contraste */
    }

    .modal-premium-header h3 {
    margin: 0 !important;
    font-size: 1.4rem !important;
    font-weight: 700 !important;
    color: #ffffff !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important; /* Espacio entre el icono de película y el texto */
    }

    .modal-premium-header h3 i {
    color: #ff4b2b !important; /* Mantiene el color corporativo en el icono */
    }

    .close-modal-btn {
    font-size: 1.8rem !important;
    font-weight: 400 !important;
    color: #6c757d !important;
    cursor: pointer !important;
    transition: color 0.2s ease, transform 0.2s ease !important;
    line-height: 1 !important;
    padding: 0 5px !important;
    }

    .close-modal-btn:hover {
        color: #ff4b2b !important; /* Se vuelve roja al pasar el cursor */
        transform: scale(1.1) !important; /* Un sutil efecto de agrandado */
    }
</style>
@endsection

@push('scripts')
<script>
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    const userRole = "{{ auth()->check() ? auth()->user()->rol : '' }}";

    function openPreviewModal(pelicula, rolContexto) {
        document.getElementById('preview-title').innerHTML = `<i class="fas fa-film"></i> ${pelicula.titulo}`;
        document.getElementById('preview-genre').innerText = pelicula.genero;
        document.getElementById('preview-price').innerText = parseFloat(pelicula.precio_alquiler).toFixed(2);
        document.getElementById('preview-stock').innerText = pelicula.copias_en_estante;
        
        // 🎯 CORREGIDO: Cambiado pelicula.descripcion por pelicula.sinopsis para vincular con tu DB
        document.getElementById('preview-synopsis').innerText = pelicula.sinopsis || 'No hay descripción detallada disponible para este título cinematográfico.';

        const img = document.getElementById('preview-img');
        if(pelicula.portada) {
            img.src = pelicula.portada;
            img.style.display = 'block';
        } else {
            img.src = '';
            img.style.display = 'none';
        }

        const pill = document.getElementById('preview-stock-pill');
        if(pelicula.copias_en_estante > 0) {
            pill.style.borderLeft = "3px solid #2ec4b6";
        } else {
            pill.style.borderLeft = "3px solid #e63946";
        }

        const placement = document.getElementById('modal-action-placement');
        placement.innerHTML = ''; 

        if (rolContexto === 'cliente') {
            let disabledClass = pelicula.copias_en_estante == 0 ? 'disabled-action' : '';
            placement.innerHTML = `
                <a href="/alquilar/${pelicula.id}" class="btn-premium-action btn-rent ${disabledClass}" style="padding: 11px 24px;">
                    <i class="fas fa-shopping-cart"></i> Reservar Copia Web
                </a>`;
        } else if (rolContexto === 'trabajador' || rolContexto === 'admin') {
            let disabledClass = pelicula.copias_en_estante == 0 ? 'disabled-action' : '';
            placement.innerHTML = `
                <a href="/prestamos/create?pelicula_id=${pelicula.id}" class="btn-premium-action btn-staff ${disabledClass}" style="padding: 11px 24px;">
                    <i class="fas fa-cash-register"></i> Despachar en Mostrador
                </a>`;
        } else {
            placement.innerHTML = `
                <a href="/login" class="btn-premium-action btn-guest" style="padding: 11px 24px;">
                    <i class="fas fa-sign-in-alt"></i> Loguearse para Alquilar
                </a>`;
        }

        document.getElementById('previewMovieModal').style.display = 'block';
    }

    function closePreviewModal() {
        document.getElementById('previewMovieModal').style.display = 'none';
    }

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
                    let peliculaJson = JSON.stringify(pelicula).replace(/"/g, '&quot;');
                    let ctxRol = isAuthenticated ? userRole : 'invitado';

                    let stockHTML = pelicula.copias_en_estante > 0 ?
                        `<span class="stock-dot dot-available"></span><span class="stock-text text-available"><i class="fas fa-check"></i> ${pelicula.copias_en_estante} disponibles</span>` :
                        `<span class="stock-dot dot-out"></span><span class="stock-text text-out"><i class="fas fa-times"></i> Agotado</span>`;

                    let botonHTML = '';
                    let disabledStyle = pelicula.copias_en_estante == 0 ? 'disabled-action' : '';
                    
                    if (isAuthenticated) {
                        if (userRole === 'cliente') {
                            botonHTML = `<a href="/alquilar/${pelicula.id}" class="btn-premium-action btn-rent ${disabledStyle}"><i class="fas fa-shopping-cart"></i> Reservar</a>`;
                        } else {
                            botonHTML = `<a href="/prestamos/create?pelicula_id=${pelicula.id}" class="btn-premium-action btn-staff ${disabledStyle}"><i class="fas fa-cash-register"></i> Alquilar en Mostrador</a>`;
                        }
                    } else {
                        botonHTML = `<a href="/login" class="btn-premium-action btn-guest"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión para Reservar</a>`;
                    }

                    let portadaHTML = pelicula.portada ? 
                        `<img src="${pelicula.portada}" alt="Portada">` : 
                        `<div class="poster-placeholder"><i class="fas fa-video"></i></div>`;

                    html += `
                    <div class="movie-premium-card" onclick="openPreviewModal(${peliculaJson}, '${ctxRol}')">
                        <div class="movie-premium-poster">
                            ${portadaHTML}
                            <span class="movie-premium-price">$${precio}</span>
                        </div>
                        <div class="movie-premium-body">
                            <span class="movie-premium-genre"><i class="fas fa-tag"></i> ${pelicula.genero}</span>
                            <h3 class="movie-premium-title" title="${pelicula.titulo}">${pelicula.titulo}</h3>
                            <div class="movie-premium-stock-box">${stockHTML}</div>
                            <div class="movie-premium-actions" onclick="event.stopPropagation();">${botonHTML}</div>
                        </div>
                    </div>`;
                });

                html += '</div>';
                container.innerHTML = html;
            });
    }
</script>
@endpush