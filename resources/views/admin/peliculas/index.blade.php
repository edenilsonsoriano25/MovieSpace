@extends('layouts.app')

@section('title', 'Administrar Catálogo')

@section('content')
<div class="admin-catalog-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="catalog-page-header">
            <div class="header-left">
                <h1 class="catalog-main-title"><i class="fas fa-film"></i> Administrar Catálogo</h1>
                <p class="catalog-main-subtitle">Controla el inventario de cintas, actualiza precios de arriendo y gestiona las copias físicas en la sucursal de Jayaque.</p>
            </div>
            
            <div class="header-controls-group" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                <div class="premium-input-search-wrapper" style="position: relative; width: 300px;">
                    <i class="fas fa-search search-input-icon" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 0.9rem;"></i>
                    <input type="text" id="search_pelicula_admin" placeholder="Buscar película por título..." class="premium-search-input" style="width: 100%; padding: 12px 16px 12px 42px; background-color: #1a1d24; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 10px; color: #ffffff; font-size: 0.92rem; outline: none; transition: all 0.2s ease;" autocomplete="off">
                </div>
                
                <a href="{{ route('admin.peliculas.create') }}" class="btn-premium-action btn-add-movie">
                    <i class="fas fa-plus-circle"></i> Agregar Película
                </a>
            </div>
        </div>

        <div class="premium-table-wrapper">
            <table class="premium-data-table" id="catalog_table_admin">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título de la Película</th>
                        <th>Género</th>
                        <th>Director</th>
                        <th style="text-align: center;">Año</th>
                        <th>Precio Alquiler</th>
                        <th style="text-align: center;">Stock Total</th>
                        <th>Disponibilidad</th>
                        <th style="text-align: center;">Acciones de Control</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peliculas as $pelicula)
                    <tr class="movie-row-item" data-titulo="{{ strtolower($pelicula->titulo) }}">
                        <td class="td-id">#{{ str_pad($pelicula->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="td-title"><strong>{{ $pelicula->titulo }}</strong></td>
                        <td class="td-genre">{{ $pelicula->genero }}</td>
                        <td class="td-director">{{ $pelicula->director }}</td>
                        <td style="text-align: center;" class="td-year">{{ $pelicula->año }}</td>
                        <td class="td-price">${{ number_format($pelicula->precio_alquiler, 2) }}</td>
                        <td style="text-align: center;" class="td-stock">{{ $pelicula->copias_totales }} uds.</td>
                        <td>
                            @if($pelicula->copias_en_estante > 0)
                                <span class="badge-stock-pill stock-pill-available">
                                    <span class="pulse-dot"></span> {{ $pelicula->copias_en_estante }} disponibles
                                </span>
                            @else
                                <span class="badge-stock-pill stock-pill-out">
                                    <i class="fas fa-times-circle"></i> Agotado
                                </span>
                            @endif
                        </td>
                        <td class="td-actions-cell">
                            <div class="actions-wrapper" style="display: flex; gap: 8px; justify-content: center;">
                                <button class="btn-table-action btn-action-edit" onclick="confirmEdit({{ $pelicula->id }})">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn-table-action btn-action-delete" onclick="confirmDelete({{ $pelicula->id }}, '{{ addslashes($pelicula->titulo) }}')">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                                <form action="{{ route('admin.peliculas.destroy', $pelicula) }}" method="POST" style="display: none;" id="form-delete-{{ $pelicula->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-state-row">
                        <td colspan="9" class="td-empty-state">
                            <div class="empty-state-box">
                                <i class="fas fa-video-slash"></i>
                                <p>No hay películas registradas en el catálogo actual.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    
                    <tr id="no_results_row" style="display: none;">
                        <td colspan="9" style="text-align: center; padding: 4rem 0; color: #6c757d;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                <i class="fas fa-search" style="font-size: 2.5rem; color: #2a2e35;"></i>
                                <span style="font-weight: 600; font-size: 1.05rem;">No se encontraron películas que coincidan con la búsqueda.</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            @if($peliculas->hasPages())
                <div class="premium-pagination-box" id="pagination_wrapper_admin">
                    {{ $peliculas->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<style>
    .admin-catalog-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem;
        padding: 3rem 0 5rem 0;
        color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .catalog-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .catalog-main-title {
        font-size: 2.6rem;
        font-weight: 800;
        margin: 0 0 0.4rem 0;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .catalog-main-title i {
        color: #ff4b2b;
        -webkit-text-fill-color: initial;
        margin-right: 12px;
    }

    .catalog-main-subtitle {
        color: #6c757d;
        font-size: 0.98rem;
        margin: 0;
    }

    .premium-search-input:focus {
        border-color: #ff4b2b !important;
        background-color: #111317 !important;
        box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.15) !important;
    }

    .btn-add-movie {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff !important;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.92rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        flex-shrink: 0;
    }

    .btn-add-movie:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.4);
    }

    .premium-table-wrapper {
        background-color: #1a1d24;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.02);
    }

    .premium-data-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .premium-data-table th {
        background: linear-gradient(135deg, #1f232b 0%, #14161c 100%);
        color: #ffffff;
        font-size: 0.88rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
    }

    .premium-data-table td {
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.02);
        color: #b3b3b3;
        font-size: 0.95rem;
        vertical-align: middle;
        background-color: #1a1d24 !important;
    }

    .premium-data-table tbody tr:hover td:not([colspan]) {
        background-color: #222731 !important;
        color: #ffffff !important;
        cursor: pointer;
    }

    .premium-data-table td.td-actions-cell,
    .premium-data-table th:last-child,
    .premium-data-table td:last-child {
        border-bottom: 1px solid rgba(255, 255, 255, 0.02) !important;
        background-color: #1a1d24 !important;
        box-shadow: none !important;
    }

    .premium-data-table tbody tr:hover td.td-actions-cell,
    .premium-data-table tbody tr:hover td:last-child {
        background-color: #222731 !important;
        box-shadow: none !important;
    }

    .td-id { font-family: monospace; color: #ff4b2b !important; font-weight: 600; }
    .td-title { color: #ffffff; }
    .td-genre { color: #d1d1d1; }
    .td-director { color: #cdcdcd; }
    .td-year { color: #8a8a8a; font-family: monospace; }
    .td-price { color: #2ec4b6 !important; font-weight: 700; font-family: monospace; }
    .td-stock { font-family: monospace; }

    .badge-stock-pill {
        font-size: 0.76rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
    }

    .stock-pill-available { background-color: rgba(46, 196, 182, 0.12); color: #2ec4b6; }
    .stock-pill-out { background-color: rgba(244, 67, 54, 0.12); color: #ef5350; }

    .pulse-dot {
        width: 6px;
        height: 6px;
        background-color: #2ec4b6;
        border-radius: 50%;
        display: inline-block;
    }

    .btn-table-action {
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-action-edit {
        background-color: rgba(255, 152, 0, 0.08);
        color: #ffb74d;
        border: 1px solid rgba(255, 152, 0, 0.2);
    }

    .btn-action-edit:hover {
        background-color: #f57c00;
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(245, 124, 0, 0.25);
    }

    .btn-action-delete {
        background: rgba(244, 67, 54, 0.08);
        color: #ef5350;
        border: 1px solid rgba(244, 67, 54, 0.2);
    }

    .btn-action-delete:hover {
        background: #d32f2f;
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(211, 47, 47, 0.25);
    }

        /* ==========================================================================
    FIX GLOBAL DE PAGINACIÓN PREMIUM (ANTI-APILAMIENTO VERTICAL)
    ========================================================================== */
    .premium-pagination-box,
    [id^="pagination_wrapper"] {
        padding: 1.5rem !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        background-color: #1a1d24 !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }

    /* Ocultar por completo los textos basura en inglés descriptivos de Tailwind */
    .premium-pagination-box div:first-child,
    .premium-pagination-box p,
    .premium-pagination-box .text-sm,
    .premium-pagination-box .hidden,
    [id^="pagination_wrapper"] div:first-child {
        display: none !important;
    }

    /* CLAVE: Forzar flex-row horizontal rígido e impedir el salto de línea */
    .premium-pagination-box div:last-child,
    .premium-pagination-box nav,
    .premium-pagination-box ul,
    .premium-pagination-box .flex,
    [id^="pagination_wrapper"] div:last-child,
    [id^="pagination_wrapper"] nav {
        display: flex !important;
        flex-direction: row !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 8px !important;
        flex-wrap: nowrap !important; /* Impide que colapsen hacia abajo */
    }

    /* Botones e indicadores numéricos estilizados */
    .premium-pagination-box a,
    .premium-pagination-box span,
    [id^="pagination_wrapper"] a,
    [id^="pagination_wrapper"] span {
        background-color: #111317 !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        color: #b3b3b3 !important;
        padding: 10px 16px !important;
        border-radius: 8px !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.2s ease !important;
        margin: 0 !important;
        min-width: 40px !important;
        height: 40px !important;
    }

    /* Efecto Hover */
    .premium-pagination-box a:hover,
    [id^="pagination_wrapper"] a:hover {
        background-color: #222731 !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }

    /* Página activa en degradado MovieSpace */
    .premium-pagination-box span[aria-current="page"],
    .premium-pagination-box .active span,
    [id^="pagination_wrapper"] span[aria-current="page"] {
        background: linear-gradient(45deg, #ff416c, #ff4b2b) !important;
        color: #ffffff !important;
        border-color: transparent !important;
    }

    /* Botones deshabilitados */
    .premium-pagination-box span[aria-disabled="true"],
    [id^="pagination_wrapper"] span[aria-disabled="true"] {
        background-color: rgba(255, 255, 255, 0.01) !important;
        color: #3a404a !important;
        border-color: rgba(255, 255, 255, 0.02) !important;
        pointer-events: none !important;
    }

    /* Ajuste de flechas SVG nativas */
    .premium-pagination-box svg,
    [id^="pagination_wrapper"] svg {
        width: 16px !important;
        height: 16px !important;
        fill: currentColor !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search_pelicula_admin');
    const movieRows = document.querySelectorAll('.movie-row-item');
    const noResultsRow = document.getElementById('no_results_row');
    const paginationWrapper = document.getElementById('pagination_wrapper_admin');

    searchInput.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase().trim();
        let visibleRows = 0;

        movieRows.forEach(row => {
            const titulo = row.getAttribute('data-titulo');
            if (titulo.includes(term)) {
                row.style.display = 'table-row';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });

        if (visibleRows === 0 && term !== '') {
            noResultsRow.style.display = 'table-row';
            if (paginationWrapper) paginationWrapper.style.display = 'none';
        } else {
            noResultsRow.style.display = 'none';
            if (paginationWrapper) paginationWrapper.style.display = 'flex';
        }
    });
});

function confirmEdit(id) {
    Swal.fire({
        title: '¿Modificar Registro?',
        text: "Vas a abrir el formulario de actualización de parámetros para esta película.",
        icon: 'question',
        background: '#1a1d24',
        color: '#ffffff',
        showCancelButton: true,
        confirmButtonColor: '#ff9800',
        cancelButtonColor: '#2a2e35',
        confirmButtonText: '<i class="fas fa-edit"></i> Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/admin/peliculas/${id}/edit`;
        }
    });
}

function confirmDelete(id, titulo) {
    Swal.fire({
        title: '¿Remover Película?',
        text: `¿Estás seguro de eliminar permanentemente "${titulo}"? El stock físico se dará de baja en el sistema.`,
        icon: 'warning',
        background: '#1a1d24',
        color: '#ffffff',
        showCancelButton: true,
        confirmButtonColor: '#ef5350',
        cancelButtonColor: '#2a2e35',
        confirmButtonText: '<i class="fas fa-trash-alt"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`form-delete-${id}`).submit();
        }
    });
}
</script>
@endsection