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
            <a href="{{ route('admin.peliculas.create') }}" class="btn-premium-action btn-add-movie">
                <i class="fas fa-plus-circle"></i> Agregar Película
            </a>
        </div>

        @if(session('success'))
            <div class="toast-alert alert-success-premium">
                <div class="toast-icon-box"><i class="fas fa-check-circle"></i></div>
                <div class="toast-content">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast-alert alert-error-premium">
                <div class="toast-icon-box"><i class="fas fa-exclamation-circle"></i></div>
                <div class="toast-content">{{ session('error') }}</div>
            </div>
        @endif

        <div class="premium-table-wrapper">
            <table class="premium-data-table">
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
                    <tr>
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
                        <td class="td-actions-buttons">
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="td-empty-state">
                            <div class="empty-state-box">
                                <i class="fas fa-video-slash"></i>
                                <p>No hay películas registradas en el catálogo actual.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($peliculas->hasPages())
                <div class="premium-pagination-box">
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
        margin-top: -2rem; /* Sincroniza con el padding de app.blade.php */
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

    /* CONTENEDOR DE LA DATA-TABLE */
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
        background-color: #1a1d24 !important; /* Blindaje contra fondos blancos */
    }

    /* Resaltado de fila al pasar cursor encima */
    .premium-data-table tbody tr:hover td {
        background-color: #222731 !important;
        color: #ffffff !important;
        cursor: pointer;
    }

    /* BLINDAJE INTEGRAL CONTRA LA LÍNEA BLANCA EN LA COLUMNA DE ACCIONES */
    .premium-data-table td.td-actions-buttons,
    .premium-data-table th:last-child,
    .premium-data-table td:last-child {
        border-bottom: 1px solid rgba(255, 255, 255, 0.02) !important;
        background-color: #1a1d24 !important;
        box-shadow: none !important;
    }

    .premium-data-table tbody tr:hover td.td-actions-buttons,
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

    /* Badges de Existencias (Stock) */
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

    /* Celda de Acciones y Botones Operativos */
    .td-actions-buttons {
        display: flex;
        gap: 0.6rem;
        justify-content: center;
        align-items: center;
        background-color: #1a1d24 !important;
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
       ESTILOS DE PAGINACIÓN PREMIUM MEJORADOS (FIX APILADO)
       ========================================================================== */
    .premium-pagination-box {
        padding: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.04);
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #1a1d24;
    }

    /* Ocultar texto informativo en inglés nativo de Laravel */
    .premium-pagination-box div:first-child p,
    .premium-pagination-box p.text-muted,
    .premium-pagination-box .text-sm {
        display: none !important;
    }

    /* Forzar alineación horizontal limpia */
    .premium-pagination-box nav,
    .premium-pagination-box ul.pagination {
        display: flex !important;
        flex-direction: row !important;
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
        gap: 6px !important;
    }

    .premium-pagination-box .page-item .page-link,
    .premium-pagination-box .page-link,
    .premium-pagination-box nav span,
    .premium-pagination-box nav a {
        background-color: #111317 !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        color: #b3b3b3 !important;
        padding: 8px 14px !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 0.88rem !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: none !important;
    }

    .premium-pagination-box .page-item:not(.active) .page-link:hover,
    .premium-pagination-box nav a:hover {
        background-color: #222731 !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }

    .premium-pagination-box .page-item.active .page-link,
    .premium-pagination-box .active > .page-link,
    .premium-pagination-box nav span[aria-current="page"] {
        background: linear-gradient(45deg, #ff416c, #ff4b2b) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        font-weight: 700 !important;
    }

    .premium-pagination-box .page-item.disabled .page-link,
    .premium-pagination-box nav span[aria-disabled="true"] {
        background-color: rgba(255, 255, 255, 0.01) !important;
        color: #4a5262 !important;
        border-color: rgba(255, 255, 255, 0.02) !important;
        pointer-events: none;
    }

    /* Contenedores Auxiliares */
    .td-empty-state {
        padding: 5rem 0 !important;
        text-align: center;
    }

    .empty-state-box {
        color: #495057;
    }

    .empty-state-box i { font-size: 3rem; margin-bottom: 1rem; }
    .empty-state-box p { font-size: 1.1rem; font-weight: 600; margin: 0; }

    @media (max-width: 768px) {
        .catalog-page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .btn-add-movie { width: 100%; justify-content: center; }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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

// Fade out progresivo controlado para los carteles de alertas de Laravel
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        let alerts = document.querySelectorAll('.toast-alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 4000);
});
</script>
@endsection