@extends('layouts.app')

@section('title', 'Solicitudes Pendientes')

@section('content')
<div class="loans-dark-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="loans-page-header">
            <div class="header-left">
                <h1 class="loans-main-title"><i class="fas fa-paper-plane"></i> Solicitudes de Alquiler</h1>
                <p class="loans-main-subtitle">Revisa las solicitudes enviadas por clientes. Aprueba o rechaza según disponibilidad.</p>
            </div>
            
            <div class="header-controls-group" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                <div class="premium-input-search-wrapper" style="position: relative; width: 300px;">
                    <i class="fas fa-search search-input-icon" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 0.9rem;"></i>
                    <input type="text" id="search_solicitud_cliente" placeholder="Buscar solicitud por cliente..." class="premium-search-input" style="width: 100%; padding: 12px 16px 12px 42px; background-color: #1a1d24; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 10px; color: #ffffff; font-size: 0.92rem; outline: none; transition: all 0.2s ease;" autocomplete="off">
                </div>

                <a href="{{ route('prestamos.index') }}" class="btn-premium-back">
                    <i class="fas fa-arrow-left"></i> Ver Préstamos Activos
                </a>
            </div>
        </div>

        <div class="premium-table-wrapper">
            <table class="premium-data-table" id="solicitudes_table_admin">
                <thead>
                    <tr>
                        <th>ID Solicitud</th>
                        <th>Cliente</th>
                        <th>Película</th>
                        <th>Días Solicitados</th>
                        <th>Fecha Solicitud</th>
                        <th>Método Pago</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudes as $solicitud)
                        @php
                            $detalle = $solicitud->detalles->first();
                            $pelicula = $detalle ? $detalle->pelicula : null;
                        @endphp
                        <tr class="solicitud-row-item" data-cliente="{{ strtolower($solicitud->usuario->name) }}">
                            <td class="td-id">#{{ str_pad($solicitud->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="td-client">
                                <strong>{{ $solicitud->usuario->name }}</strong><br>
                                <small class="text-muted-email">{{ $solicitud->usuario->email }}</small>
                            </td>
                            <td>
                                <strong>{{ $pelicula ? $pelicula->titulo : 'N/A' }}</strong><br>
                                <small class="text-muted-price">${{ number_format($detalle->precio_alquiler_momento ?? 0, 2) }}/día</small>
                            </td>
                            <td class="td-days">{{ \Carbon\Carbon::parse($solicitud->fecha_salida)->diffInDays($solicitud->fecha_limite) }} días</td>
                            <td class="td-date-normal">{{ \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $metodo = session('metodo_pago_' . $solicitud->id, 'efectivo');
                                @endphp
                                <span class="badge-method-pill">
                                    @if($metodo == 'efectivo')
                                        <i class="fas fa-money-bill-wave text-success-icon"></i> Efectivo
                                    @elseif($metodo == 'tarjeta')
                                        <i class="fas fa-credit-card text-info-icon"></i> Tarjeta
                                    @else
                                        <i class="fas fa-university text-warning-icon"></i> Transferencia
                                    @endif
                                </span>
                            </td>
                            <td class="td-actions-cell">
                                <div class="actions-wrapper">
                                    <button class="btn-loan-action btn-action-approve" onclick="aprobarSolicitud({{ $solicitud->id }})">
                                        <i class="fas fa-check-circle"></i> Aprobar
                                    </button>
                                    <button class="btn-loan-action btn-action-reject" onclick="rechazarSolicitud({{ $solicitud->id }})">
                                        <i class="fas fa-times-circle"></i> Rechazar
                                    </button>
                                    
                                    <form action="{{ route('solicitudes.aprobar', $solicitud) }}" method="POST" style="display: none;" id="form-aprobar-{{ $solicitud->id }}">
                                        @csrf
                                    </form>
                                    <form action="{{ route('solicitudes.rechazar', $solicitud) }}" method="POST" style="display: none;" id="form-rechazar-{{ $solicitud->id }}">
                                        @csrf
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="td-empty-state">
                                <div class="empty-state-box">
                                    <i class="fas fa-inbox empty-icon"></i>
                                    <p class="empty-text">No hay solicitudes pendientes de aprobación.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="no_solicitudes_results_row" style="display: none;">
                        <td colspan="7" style="text-align: center; padding: 4rem 0; color: #6c757d;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                <i class="fas fa-search" style="font-size: 2.5rem; color: #2a2e35;"></i>
                                <span style="font-weight: 600; font-size: 1.05rem;">No se encontraron solicitudes asociadas a ese cliente.</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            @if(method_exists($solicitudes, 'hasPages') && $solicitudes->hasPages())
                <div class="premium-pagination-box" id="pagination_wrapper_admin">
                    {{ $solicitudes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .loans-dark-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem;
        padding: 3rem 0 5rem 0;
        color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .loans-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .loans-main-title {
        font-size: 2.6rem;
        font-weight: 800;
        margin: 0 0 0.4rem 0;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .loans-main-title i {
        color: #ff4b2b;
        -webkit-text-fill-color: initial;
        margin-right: 12px;
    }

    .loans-main-subtitle {
        color: #6c757d;
        font-size: 0.98rem;
        margin: 0;
    }

    .premium-search-input {
        width: 100%;
        padding: 12px 16px 12px 42px;
        background-color: #1a1d24;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        color: #ffffff;
        font-size: 0.92rem;
        outline: none;
        transition: all 0.2s ease;
    }

    .premium-search-input:focus {
        border-color: #ff4b2b !important;
        background-color: #111317 !important;
        box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.15) !important;
    }

    .btn-premium-back {
        background-color: rgba(255, 255, 255, 0.02);
        color: #b3b3b3 !important;
        border: 1px solid rgba(255, 255, 255, 0.05);
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.92rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .btn-premium-back:hover {
        background-color: #222731;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.15);
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

    .premium-data-table th:last-child,
    .premium-data-table td:last-child,
    .premium-data-table td.td-actions-cell {
        border-bottom: 1px solid rgba(255, 255, 255, 0.02) !important;
        background-color: #1a1d24 !important;
        box-shadow: none !important;
    }

    .premium-data-table tbody tr:hover td:last-child,
    .premium-data-table tbody tr:hover td.td-actions-cell {
        background-color: #222731 !important;
        box-shadow: none !important;
    }

    .td-id { font-family: monospace; color: #ff4b2b !important; font-weight: 600; }
    .td-client { color: #ffffff; }
    .text-muted-email { color: #6c757d; font-size: 0.82rem; }
    .text-muted-price { color: #ff416c; font-size: 0.85rem; font-weight: 600; }
    .td-date-normal, .td-days { font-family: monospace; color: #d1d1d1; }

    .badge-method-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background-color: #111317;
        color: #cdcdcd;
        border-radius: 6px;
        font-size: 0.85rem;
        border: 1px solid rgba(255,255,255,0.03);
    }
    .text-success-icon { color: #2ec4b6; }
    .text-info-icon { color: #2196f3; }
    .text-warning-icon { color: #ff9f43; }

    .actions-wrapper {
        display: flex !important;
        gap: 0.5rem;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-loan-action {
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

    .btn-action-approve {
        background-color: rgba(46, 196, 182, 0.12);
        color: #2ec4b6;
        border: 1px solid rgba(46, 196, 182, 0.2);
    }

    .btn-action-approve:hover {
        background-color: #2ec4b6;
        color: #ffffff !important;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(46, 196, 182, 0.25);
    }

    .btn-action-reject {
        background-color: rgba(244, 67, 54, 0.12);
        color: #ef5350;
        border: 1px solid rgba(244, 67, 54, 0.2);
    }

    .btn-action-reject:hover {
        background-color: #ef5350;
        color: #ffffff !important;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(244, 67, 54, 0.25);
    }

    .td-empty-state {
        padding: 4rem 2px !important;
        text-align: center;
    }
    .empty-state-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }
    .empty-icon { font-size: 3rem; color: #3a404a; }
    .empty-text { color: #6c757d; font-size: 1.05rem; margin: 0; font-weight: 600; }

    /* ==========================================================================
       🔥 MODELO DE REGLAS DE PAGINACIÓN ADAPTADO EXACTO DESDE EL CATÁLOGO
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

    .premium-pagination-box div:first-child,
    .premium-pagination-box p,
    .premium-pagination-box .text-sm,
    .premium-pagination-box .hidden,
    [id^="pagination_wrapper"] div:first-child {
        display: none !important;
    }

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
        flex-wrap: nowrap !important;
    }

    .premium-pagination-box li,
    .premium-pagination-box .page-item,
    [id^="pagination_wrapper"] li {
        display: inline-flex !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .premium-pagination-box a,
    .premium-pagination-box span,
    .premium-pagination-box .page-link,
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
        box-sizing: border-box !important;
    }

    .premium-pagination-box a:hover,
    .premium-pagination-box .page-link:hover,
    [id^="pagination_wrapper"] a:hover {
        background-color: #222731 !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }

    .premium-pagination-box span[aria-current="page"],
    .premium-pagination-box .active span,
    .premium-pagination-box .active .page-link,
    [id^="pagination_wrapper"] span[aria-current="page"] {
        background: linear-gradient(45deg, #ff416c, #ff4b2b) !important;
        color: #ffffff !important;
        border-color: transparent !important;
    }

    .premium-pagination-box span[aria-disabled="true"],
    .premium-pagination-box .disabled .page-link,
    [id^="pagination_wrapper"] span[aria-disabled="true"] {
        background-color: rgba(255, 255, 255, 0.01) !important;
        color: #3a404a !important;
        border-color: rgba(255, 255, 255, 0.02) !important;
        pointer-events: none !important;
    }

    .premium-pagination-box svg,
    [id^="pagination_wrapper"] svg {
        width: 16px !important;
        height: 16px !important;
        fill: currentColor !important;
    }

    @media (max-width: 768px) {
        .loans-page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .btn-premium-back, .premium-input-search-wrapper { width: 100% !important; }
        .header-controls-group { width: 100%; }
        .actions-wrapper { flex-direction: column; width: 100%; }
        .btn-loan-action { width: 100%; justify-content: center; }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// LÓGICA DEL BUSCADOR REACTIVO POR NOMBRE DE CLIENTE
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search_solicitud_cliente');
    const rows = document.querySelectorAll('.solicitud-row-item');
    const noResultsRow = document.getElementById('no_solicitudes_results_row');
    const paginationWrapper = document.getElementById('pagination_wrapper_admin');

    searchInput.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase().trim();
        let visibleRows = 0;

        rows.forEach(row => {
            const cliente = row.getAttribute('data-cliente');
            if (cliente.includes(term)) {
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

function aprobarSolicitud(id) {
    Swal.fire({
        title: '¿Aprobar solicitud?',
        text: "El cliente podrá retirar la película en sucursal.",
        icon: 'question',
        background: '#1a1d24',
        color: '#ffffff',
        showCancelButton: true,
        confirmButtonColor: '#2ec4b6',
        cancelButtonColor: '#2a2e35',
        confirmButtonText: '<i class="fas fa-check"></i> Sí, aprobar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`form-aprobar-${id}`).submit();
        }
    });
}

function rechazarSolicitud(id) {
    Swal.fire({
        title: '¿Rechazar solicitud?',
        text: "Esta acción notificará al cliente que su solicitud fue rechazada.",
        icon: 'warning',
        background: '#1a1d24',
        color: '#ffffff',
        showCancelButton: true,
        confirmButtonColor: '#ef5350',
        cancelButtonColor: '#2a2e35',
        confirmButtonText: '<i class="fas fa-times"></i> Rechazar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`form-rechazar-${id}`).submit();
        }
    });
}
</script>
@endsection