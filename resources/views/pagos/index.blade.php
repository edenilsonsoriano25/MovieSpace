@extends('layouts.app')

@section('title', 'Caja y Pagos')

@section('content')
<div class="cash-history-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="cash-page-header">
            <div class="header-left">
                <h1 class="cash-main-title"><i class="fas fa-cash-register"></i> Caja Chica</h1>
                <p class="cash-main-subtitle">Monitorea los flujos de ingresos globales, revisa la distribución por métodos de pago y audita el historial de transacciones.</p>
            </div>
            <div class="header-buttons">
                <a href="{{ route('caja.index') }}" class="btn-premium-action btn-summary-link">
                    <i class="fas fa-chart-line"></i> Resumen del Día
                </a>
            </div>
        </div>

        <div class="cash-stats-grid">
            <div class="cash-stat-card border-glow-blue">
                <div class="stat-icon-box icon-blue"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-info">
                    <div class="stat-number">${{ number_format($pagos->sum('monto'), 2) }}</div>
                    <div class="stat-label">Total Recaudado</div>
                </div>
            </div>
            
            <div class="cash-stat-card border-glow-teal">
                <div class="stat-icon-box icon-teal"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-info">
                    <div class="stat-number">${{ number_format($pagos->where('metodo_pago', 'efectivo')->sum('monto'), 2) }}</div>
                    <div class="stat-label">Flujo Efectivo</div>
                </div>
            </div>
            
            <div class="cash-stat-card border-glow-orange">
                <div class="stat-icon-box icon-orange"><i class="fas fa-credit-card"></i></div>
                <div class="stat-info">
                    <div class="stat-number">${{ number_format($pagos->where('metodo_pago', 'tarjeta')->sum('monto'), 2) }}</div>
                    <div class="stat-label">Terminal Tarjeta</div>
                </div>
            </div>

            <div class="cash-stat-card border-glow-purple">
                <div class="stat-icon-box icon-purple"><i class="fas fa-exchange-alt"></i></div>
                <div class="stat-info">
                    <div class="stat-number">${{ number_format($pagos->where('metodo_pago', 'transferencia')->sum('monto'), 2) }}</div>
                    <div class="stat-label">Transferencias</div>
                </div>
            </div>
        </div>

        <div class="premium-table-wrapper">
            <div class="table-premium-header">
                <h3><i class="fas fa-history"></i> Historial General de Transacciones</h3>
            </div>
            
            <table class="premium-data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Concepto</th>
                        <th>Monto Procesado</th>
                        <th>Método de Pago</th>
                        <th>Usuario / Cliente</th>
                        <th>Fecha de Registro</th>
                        <th style="text-align: center;">Estado Auditoría</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $pago)
                    <tr>
                        <td class="td-id">#{{ str_pad($pago->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            @if($pago->concepto == 'alquiler')
                                <span class="badge-concept-pill concept-pill-success">📀 Alquiler</span>
                            @else
                                <span class="badge-concept-pill concept-pill-warning">⚠️ Multa</span>
                            @endif
                        </td>
                        <td class="td-amount-green">${{ number_format($pago->monto, 2) }}</td>
                        <td class="td-method-type">
                            @if($pago->metodo_pago == 'efectivo')
                                <span class="payment-badge-text text-teal">💵 Efectivo</span>
                            @elseif($pago->metodo_pago == 'tarjeta')
                                <span class="payment-badge-text text-blue">💳 Tarjeta</span>
                            @else
                                <span class="payment-badge-text text-purple"> 🏦 Transferencia</span>
                            @endif
                        </td>
                        <td class="td-username"><strong>{{ $pago->usuario->name }}</strong></td>
                        <td class="td-date">{{ \Carbon\Carbon::parse($pago->created_at)->timezone('America/El_Salvador')->format('d/m/Y h:i:s A') }}</td>
                        <td class="td-status-completed">
                            <div class="status-inner-box">
                                <span class="status-dot-active"></span>
                                <span>Pagado</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($pagos->hasPages())
                <div class="premium-pagination-box">
                    {{ $pagos->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<style>
    .cash-history-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem; /* Sincroniza con app.blade.php */
        padding: 3rem 0 5rem 0;
        color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .cash-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        gap: 1.5rem;
    }

    .cash-main-title {
        font-size: 2.6rem;
        font-weight: 800;
        margin: 0 0 0.4rem 0;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .cash-main-title i {
        color: #ff4b2b;
        -webkit-text-fill-color: initial;
        margin-right: 12px;
    }

    .cash-main-subtitle {
        color: #6c757d;
        font-size: 0.98rem;
        margin: 0;
    }

    .btn-summary-link {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff !important;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.92rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        flex-shrink: 0;
    }

    .btn-summary-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.4);
    }

    /* REJILLA DE TARJETAS DE MÉTRICAS ANALÍTICAS (STATS) */
    .cash-stats-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)) !important;
        gap: 1.5rem !important;
        margin-bottom: 3rem;
        width: 100%;
    }

    .cash-stat-card {
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
    .border-glow-purple { border-left: 4px solid #9c27b0; }

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
    .icon-purple { color: #9c27b0; }

    .stat-info { display: flex; flex-direction: column; }
    .stat-number { font-size: 1.6rem; font-weight: 800; color: #fff; margin: 0; }
    .stat-label { font-size: 0.82rem; color: #6c757d; font-weight: 600; }

    /* CONTENEDOR DE LA DATA-TABLE */
    .premium-table-wrapper {
        background-color: #1a1d24;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.02);
    }

    .table-premium-header {
        padding: 1.25rem 1.5rem;
        background-color: #121419;
        border-bottom: 1px solid rgba(255,255,255,0.04);
    }

    .table-premium-header h3 {
        margin: 0; color: #ffffff; font-size: 1.1rem; font-weight: 700;
        display: flex; align-items: center; gap: 8px;
    }

    .table-premium-header h3 i { color: #ff4b2b; }

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
        background-color: #1a1d24 !important; /* Blindaje de fondo */
    }

    /* Resaltado de fila en hover */
    .premium-data-table tbody tr:hover td {
        background-color: #222731 !important;
        color: #ffffff !important;
        cursor: pointer;
    }

    /* BLINDAJE INTEGRAL CONTRA COLAPSO Y LÍNEAS BLANCAS EN LA ÚLTIMA COLUMNA */
    .premium-data-table th:last-child,
    .premium-data-table td:last-child {
        border-bottom: 1px solid rgba(255, 255, 255, 0.02) !important;
        background-color: #1a1d24 !important;
        box-shadow: none !important;
    }

    .premium-data-table tbody tr:hover td:last-child {
        background-color: #222731 !important;
        box-shadow: none !important;
    }

    .td-id { font-family: monospace; color: #ff4b2b !important; font-weight: 600; }
    .td-amount-green { color: #2ec4b6 !important; font-weight: 700; font-family: monospace; }
    .td-username { color: #ffffff; }
    .td-date { color: #8a8a8a; font-size: 0.9rem; font-family: monospace; }

    /* Conceptos Badges */
    .badge-concept-pill {
        font-size: 0.76rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        text-transform: uppercase;
    }
    .concept-pill-success { background-color: rgba(46, 196, 182, 0.12); color: #2ec4b6; }
    .concept-pill-warning { background-color: rgba(255, 152, 0, 0.12); color: #ffb74d; }

    /* Textos colorizados por método de pago */
    .payment-badge-text { font-weight: 600; font-size: 0.92rem; }
    .text-teal { color: #2ec4b6; }
    .text-blue { color: #2196f3; }
    .text-purple { color: #b57cff; }

    /* Estado de Pago Exitoso */
    .td-status-completed { text-align: center; }
    
    .status-inner-box {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #2ec4b6;
        font-size: 0.88rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-dot-active {
        width: 6px;
        height: 6px;
        background-color: #2ec4b6;
        border-radius: 50%;
    }

    /* ==========================================================================
       🔥 ULTRA-FIX CONTRA PAGINACIÓN APILADA EN INGLÉS (TAILWIND OVERRIDE)
       ========================================================================== */
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

    /* Ocultar por completo textos en inglés redundantes de Tailwind */
    .premium-pagination-box div:first-child,
    .premium-pagination-box p,
    .premium-pagination-box .text-sm,
    .premium-pagination-box .hidden {
        display: none !important;
    }

    /* Alinear en fila horizontal limpia */
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

    /* Formato de botones numéricos */
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
        transition: all 0.2s ease;
        cursor: pointer;
        margin: 0 !important;
    }

    .premium-pagination-box a:hover {
        background-color: #222731 !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }

    /* Gradiente en la página activa actual */
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
        .cash-page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .btn-summary-link { width: 100%; justify-content: center; }
    }
</style>
@endsection