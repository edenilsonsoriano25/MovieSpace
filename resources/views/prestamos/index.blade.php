@extends('layouts.app')

@section('title', 'Préstamos')

@section('content')
<div class="loans-dark-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="loans-page-header">
            <div class="header-left">
                <h1 class="loans-main-title"><i class="fas fa-exchange-alt"></i> Gestión de Préstamos</h1>
                <p class="loans-main-subtitle">Supervisa todas las salidas de CDs, controla las fechas de expiración, aplica recargos por mora y gestiona las recepciones en mostrador.</p>
            </div>
            <a href="{{ route('prestamos.create') }}" class="btn-premium-action btn-add-loan">
                <i class="fas fa-plus"></i> Nuevo Préstamo
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

        <div class="loans-stats-grid">
            <div class="loans-stat-card border-glow-blue">
                <div class="stat-icon-box icon-blue"><i class="fas fa-ticket-alt"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $prestamos->total() }}</div>
                    <div class="stat-label">Total Préstamos</div>
                </div>
            </div>
            
            <div class="loans-stat-card border-glow-teal">
                <div class="stat-icon-box icon-teal"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $prestamos->where('estado_prestamo', 'activo')->count() }}</div>
                    <div class="stat-label">Préstamos Activos</div>
                </div>
            </div>
            
            <div class="loans-stat-card border-glow-orange">
                <div class="stat-icon-box icon-orange"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $prestamos->where('estado_prestamo', 'activo')->filter(function($p) { return now()->gt($p->fecha_limite); })->count() }}</div>
                    <div class="stat-label">Retrasados / Mora</div>
                </div>
            </div>
        </div>

        <div class="premium-table-wrapper">
            <table class="premium-data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente / Afiliado</th>
                        <th>Películas Vinculadas</th>
                        <th>F. Salida</th>
                        <th>F. Límite Entrega</th>
                        <th>Estado Actual</th>
                        <th>Recargo Multa</th>
                        <th style="text-align: center;">Acciones de Control</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prestamos as $prestamo)
                    <tr>
                        <td class="td-id">#{{ str_pad($prestamo->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="td-client">
                            <strong>{{ $prestamo->usuario->name }}</strong><br>
                            <small class="text-muted-email">{{ $prestamo->usuario->email }}</small>
                        </td>
                        <td class="td-movies-badges">
                            @foreach($prestamo->detalles as $detalle)
                                <span class="badge-movie-pill"><i class="fas fa-disc"></i> {{ $detalle->pelicula->titulo }}</span>
                            @endforeach
                        </td>
                        <td class="td-date-normal">{{ \Carbon\Carbon::parse($prestamo->fecha_salida)->format('d/m/Y') }}</td>
                        <td class="td-date-limit {{ now()->gt($prestamo->fecha_limite) && $prestamo->estado_prestamo == 'activo' ? 'limit-overdue' : '' }}">
                            <span class="date-text">{{ \Carbon\Carbon::parse($prestamo->fecha_limite)->format('d/m/Y') }}</span>
                            @if(now()->gt($prestamo->fecha_limite) && $prestamo->estado_prestamo == 'activo')
                                <span class="badge-danger-pill">Mora</span>
                            @endif
                        </td>
                        <td>
                            @if($prestamo->estado_prestamo == 'activo')
                                <span class="status-pill status-pill-active"><span class="pulse-dot"></span> Activo</span>
                            @else
                                <span class="status-pill status-pill-completed"><i class="fas fa-check-circle"></i> Devuelto</span>
                            @endif
                        </td>
                        <td class="td-fine {{ $prestamo->multa_total > 0 ? 'text-danger-fine' : 'text-fine-zero' }}">
                            ${{ number_format($prestamo->multa_total, 2) }}
                        </td>
                        <td class="td-actions-cell">
                            <div class="actions-wrapper">
                                @if($prestamo->estado_prestamo == 'activo')
                                    <button class="btn-loan-action btn-action-return" onclick="confirmDevolucion({{ $prestamo->id }})">
                                        <i class="fas fa-undo-alt"></i> Devolver
                                    </button>
                                    <form action="{{ route('prestamos.devolucion', $prestamo) }}" method="POST" style="display: none;" id="form-devolucion-{{ $prestamo->id }}">
                                        @csrf
                                    </form>
                                @endif
                                
                                <button class="btn-loan-action btn-action-view" 
                                        onclick="verDetalle(
                                            '{{ str_pad($prestamo->id, 5, '0', STR_PAD_LEFT) }}', 
                                            '{{ addslashes($prestamo->usuario->name) }}', 
                                            '{{ $prestamo->usuario->email }}', 
                                            '{{ addslashes($prestamo->detalles->map(function($d){ return $d->pelicula->titulo; })->implode(', ')) }}', 
                                            '{{ \Carbon\Carbon::parse($prestamo->fecha_salida)->format('d/m/Y') }}', 
                                            '{{ \Carbon\Carbon::parse($prestamo->fecha_limite)->format('d/m/Y') }}', 
                                            '{{ $prestamo->estado_prestamo }}', 
                                            '{{ number_format($prestamo->multa_total, 2) }}'
                                        )">
                                    <i class="fas fa-eye"></i> Detalle
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($prestamos->hasPages())
                <div class="premium-pagination-box">
                    {{ $prestamos->links() }}
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

    .btn-add-loan {
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

    .btn-add-loan:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.4);
    }

    .loans-stats-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)) !important;
        gap: 1.5rem !important;
        margin-bottom: 3rem;
        width: 100%;
    }

    .loans-stat-card {
        background-color: #1a1d24;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.02);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .border-glow-blue { border-left: 4px solid #2196f3; }
    .border-glow-teal { border-left: 4px solid #2ec4b6; }
    .border-glow-orange { border-left: 4px solid #ff9f43; }

    .stat-icon-box {
        width: 48px;
        height: 48px;
        background-color: #111317;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .icon-blue { color: #2196f3; }
    .icon-teal { color: #2ec4b6; }
    .icon-orange { color: #ff9f43; }

    .stat-info { display: flex; flex-direction: column; }
    .stat-number { font-size: 1.8rem; font-weight: 800; color: #fff; margin: 0; }
    .stat-label { font-size: 0.82rem; color: #6c757d; font-weight: 600; }

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

    .premium-data-table tbody tr:hover td {
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
    .td-date-normal { font-family: monospace; color: #d1d1d1; }
    .td-date-limit { font-family: monospace; }
    
    .limit-overdue .date-text { color: #ef5350 !important; font-weight: 700; }
    .badge-danger-pill {
        background-color: rgba(244, 67, 54, 0.15);
        color: #ef5350;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 700;
        margin-left: 6px;
        text-transform: uppercase;
    }

    .badge-movie-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background-color: #111317;
        color: #cdcdcd;
        border-radius: 6px;
        font-size: 0.8rem;
        margin: 2px;
        border: 1px solid rgba(255,255,255,0.03);
    }

    .status-pill {
        font-size: 0.76rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-transform: uppercase;
    }
    .status-pill-active { background-color: rgba(46, 196, 182, 0.12); color: #2ec4b6; }
    .status-pill-completed { background-color: rgba(108, 117, 125, 0.15); color: #8a8a8a; }

    .pulse-dot {
        width: 6px;
        height: 6px;
        background-color: #2ec4b6;
        border-radius: 50%;
        display: inline-block;
    }

    .text-danger-fine { color: #ef5350 !important; font-weight: 700; font-family: monospace; }
    .text-fine-zero { color: #495057; font-family: monospace; }

    .actions-wrapper {
        display: flex !important;
        gap: 0.5rem;
        justify-content: center;
        align-items: center;
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

    .btn-action-return {
        background-color: rgba(33, 150, 243, 0.08);
        color: #42a5f5;
        border: 1px solid rgba(33, 150, 243, 0.2);
    }

    .btn-action-return:hover {
        background-color: #2196f3;
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.25);
    }

    .btn-action-view {
        background-color: rgba(255, 255, 255, 0.02);
        color: #b3b3b3;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .btn-action-view:hover {
        background-color: #343a44;
        color: #ffffff;
        border-color: transparent;
    }

    /* ==========================================================================
       🛡️ BLINDAJE ULTRA-STRICT CONTRA FONDOS BLANCOS INVOLUNTARIOS EN EL MODAL
       ========================================================================== */
    .swal-modal-table {
        width: 100% !important;
        margin-top: 15px !important;
        border-collapse: collapse !important;
        text-align: left !important;
        background-color: #1a1d24 !important;
    }

    /* Fuerza de forma absoluta a que ninguna fila o celda herede fondos de Bootstrap */
    .swal-modal-table tr, 
    .swal-modal-table td {
        background-color: #1a1d24 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        padding: 12px 10px !important;
        font-size: 0.92rem !important;
        vertical-align: middle !important;
    }

    .swal-modal-table td.swal-label {
        color: #6c757d !important;
        font-weight: 600 !important;
        width: 30% !important;
    }

    .swal-modal-table td.swal-value {
        color: #ffffff !important;
    }

    .premium-pagination-box {
        padding: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.04);
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        background-color: #1a1d24 !important;
        width: 100% !important;
        box-sizing: border-box;
    }

    .premium-pagination-box div:first-child,
    .premium-pagination-box p,
    .premium-pagination-box .text-sm,
    .premium-pagination-box .hidden {
        display: none !important;
    }

    .premium-pagination-box div:last-child,
    .premium-pagination-box nav,
    .premium-pagination-box flex,
    .premium-pagination-box .flex {
        display: flex !important;
        flex-direction: row !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .premium-pagination-box a,
    .premium-pagination-box span {
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
        margin: 0 !important;
    }

    .premium-pagination-box a:hover {
        background-color: #222731 !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }

    .premium-pagination-box span[aria-current="page"],
    .premium-pagination-box .bg-blue-600 {
        background: linear-gradient(45deg, #ff416c, #ff4b2b) !important;
        color: #ffffff !important;
        border-color: transparent !important;
    }

    .premium-pagination-box span[aria-disabled="true"] {
        background-color: rgba(255, 255, 255, 0.01) !important;
        color: #3a404a !important;
        border-color: rgba(255, 255, 255, 0.02) !important;
        pointer-events: none !important;
    }

    .premium-pagination-box svg {
        width: 16px !important;
        height: 16px !important;
        fill: currentColor !important;
    }

    @media (max-width: 768px) {
        .loans-page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .btn-add-loan { width: 100%; justify-content: center; }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDevolucion(id) {
    Swal.fire({
        title: '¿Registrar devolución?',
        text: "Confirma que el cliente ha retornado las copias físicas de los CDs a la estantería de forma exitosa.",
        icon: 'question',
        background: '#1a1d24',
        color: '#ffffff',
        showCancelButton: true,
        confirmButtonColor: '#2196f3',
        cancelButtonColor: '#2a2e35',
        confirmButtonText: '<i class="fas fa-clipboard-check"></i> Sí, procesar ingreso',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`form-devolucion-${id}`).submit();
        }
    });
}

function verDetalle(id, cliente, email, peliculas, salida, limite, estado, multa) {
    let estadoBadge = estado === 'activo' 
        ? '<span style="color: #2ec4b6; font-weight: bold; text-transform: uppercase;">Activo</span>' 
        : '<span style="color: #8a8a8a; font-weight: bold; text-transform: uppercase;">Devuelto</span>';

    let multaTexto = parseFloat(multa) > 0 
        ? `<span style="color: #ef5350; font-weight: bold;">$${multa}</span>` 
        : `<span style="color: #6c757d;">$${multa}</span>`;

    Swal.fire({
        title: `Detalle del Préstamo #${id}`,
        html: `
            <table class="swal-modal-table">
                <tr>
                    <td class="swal-label">Cliente:</td>
                    <td class="swal-value"><strong>${cliente}</strong><br><small style="color: #8a8a8a;">${email}</small></td>
                </tr>
                <tr>
                    <td class="swal-label">Películas:</td>
                    <td class="swal-value" style="color: #ff416c; font-weight: 600;">${peliculas}</td>
                </tr>
                <tr>
                    <td class="swal-label">F. Salida:</td>
                    <td class="swal-value" style="font-family: monospace;">${salida}</td>
                </tr>
                <tr>
                    <td class="swal-label">F. Límite:</td>
                    <td class="swal-value" style="font-family: monospace;">${limite}</td>
                </tr>
                <tr>
                    <td class="swal-label">Estado:</td>
                    <td class="swal-value">${estadoBadge}</td>
                </tr>
                <tr>
                    <td class="swal-label">Multa Actual:</td>
                    <td class="swal-value">${multaTexto}</td>
                </tr>
            </table>
        `,
        icon: 'info',
        background: '#1a1d24',
        color: '#ffffff',
        confirmButtonColor: '#ff4b2b',
        confirmButtonText: 'Cerrar Ventana'
    });
}

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