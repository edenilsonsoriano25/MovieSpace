@extends('layouts.app')

@section('title', 'Resumen de Caja')

@section('content')
<div class="cash-dark-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="cash-page-header">
            <div class="header-left">
                <h1 class="cash-main-title"><i class="fas fa-chart-line"></i> Resumen del Día</h1>
                <p class="cash-main-subtitle">Auditoría rápida de flujo de efectivo, arqueo diario de transacciones y conciliación monetaria.</p>
            </div>
            <div class="header-buttons">
                <button class="btn-premium-action btn-print-report" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir Reporte
                </button>
                <a href="{{ route('pagos.index') }}" class="btn-premium-back-link">
                    <i class="fas fa-arrow-left"></i> Ver Historial
                </a>
            </div>
        </div>

        <div class="cash-stats-grid">
            <div class="cash-stat-card border-glow-blue">
                <div class="stat-icon-box icon-blue"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
                    <div class="stat-label">Fecha del Arqueo</div>
                </div>
            </div>
            
            <div class="cash-stat-card border-glow-teal">
                <div class="stat-icon-box icon-teal"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-info">
                    <div class="stat-number">${{ number_format($totalHoy, 2) }}</div>
                    <div class="stat-label">Total Liquidado</div>
                </div>
            </div>
            
            <div class="cash-stat-card border-glow-orange">
                <div class="stat-icon-box icon-orange"><i class="fas fa-receipt"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $pagosHoy->count() }}</div>
                    <div class="stat-label">Transacciones Ejecutadas</div>
                </div>
            </div>
        </div>

        <div class="premium-table-wrapper">
            <div class="table-premium-header">
                <h3><i class="fas fa-list"></i> Libro de Operaciones Diarias</h3>
            </div>
            
            @if($pagosHoy->isEmpty())
                <div class="cash-empty-state">
                    <i class="fas fa-coins"></i>
                    <p>No se registran movimientos financieros durante la jornada actual.</p>
                </div>
            @else
                <table class="premium-data-table">
                    <thead>
                        <tr>
                            <th>Hora Marca</th>
                            <th>Concepto de Pago</th>
                            <th>Monto Neto</th>
                            <th>Método Utilizado</th>
                            <th style="text-align: right;">Usuario Asignado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pagosHoy as $pago)
                        <tr>
                            <td class="td-time">{{ \Carbon\Carbon::parse($pago->created_at)->format('H:i:s') }}</td>
                            <td>
                                @if($pago->concepto == 'alquiler')
                                    <span class="badge-concept-pill concept-pill-success">📀 Alquiler</span>
                                distribute
                                @else
                                    <span class="badge-concept-pill concept-pill-warning">⚠️ Multa</span>
                                @endif
                            </td>
                            <td class="td-amount-green">${{ number_format($pago->monto, 2) }}</td>
                            <td class="td-method">
                                @if($pago->metodo_pago == 'efectivo')
                                    💵 Efectivo
                                @elseif($pago->metodo_pago == 'tarjeta')
                                    💳 Tarjeta
                                @else
                                    🏦 Transferencia
                                @endif
                            </td>
                            <td style="text-align: right;" class="td-username">{{ $pago->usuario->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="premium-total-footer-row">
                            <td colspan="2" class="text-right-label">TOTAL CONCILIADO</td>
                            <td class="td-final-total">${{ number_format($totalHoy, 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</div>

<style>
    .cash-dark-wrapper {
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
        flex-wrap: wrap;
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

    .header-buttons {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .btn-print-report {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.92rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-print-report:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.4);
    }

    .btn-premium-back-link {
        background-color: #1a1d24;
        color: #b3b3b3;
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

    .btn-premium-back-link:hover {
        background-color: #242933;
        color: #ffffff;
    }

    /* REJILLA DE KPIS (STATS CARDS) */
    .cash-stats-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)) !important;
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
    .stat-number { font-size: 1.6rem; font-weight: 800; color: #fff; margin: 0; }
    .stat-label { font-size: 0.82rem; color: #6c757d; font-weight: 600; }

    /* MAQUETACIÓN DE TABLA DE AUDITORÍA */
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

    .premium-data-table { width: 100%; border-collapse: collapse; }
    
    .premium-data-table th {
        background-color: #15181e; color: #ffffff; font-size: 0.88rem;
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px;
        padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,0.04);
    }

    .premium-data-table td {
        padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,0.02);
        color: #b3b3b3; font-size: 0.95rem; background-color: #1a1d24 !important;
    }

    /* Solución definitiva para colisiones de celdas invisibles */
    .premium-data-table tbody tr:hover td {
        background-color: #222731 !important;
        color: #ffffff !important;
        cursor: pointer;
    }

    /* Blindaje para la última celda de la tabla */
    .premium-data-table th:last-child,
    .premium-data-table td:last-child {
        border-bottom: 1px solid rgba(255, 255, 255, 0.02) !important;
        box-shadow: none !important;
    }

    .premium-data-table tbody tr:hover td:last-child {
        background-color: #222731 !important;
    }

    .td-time { font-family: monospace; color: #ff4b2b; font-weight: 600; }
    .td-amount-green { color: #2ec4b6 !important; font-weight: 700; font-family: monospace; }
    .td-method { color: #cdcdcd; }
    .td-username { color: #ffffff; font-weight: 600; }

    /* Badges de conceptos */
    .badge-concept-pill {
        font-size: 0.76rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
    }
    .concept-pill-success { background-color: rgba(46, 196, 182, 0.12); color: #2ec4b6; }
    .concept-pill-warning { background-color: rgba(255, 152, 0, 0.12); color: #ffb74d; }

    /* Fila de Totales */
    .premium-total-footer-row td {
        background-color: #121419 !important;
        padding: 18px 20px;
        border-top: 1px solid rgba(255,255,255,0.05);
    }
    .text-right-label { text-align: left; font-weight: 800; color: #ffffff; letter-spacing: 0.5px; }
    .td-final-total { color: #2ec4b6 !important; font-weight: 800; font-family: monospace; font-size: 1.15rem; }

    .cash-empty-state { text-align: center; padding: 5rem 2rem; color: #495057; }
    .cash-empty-state i { font-size: 3rem; margin-bottom: 1rem; }
    .cash-empty-state p { font-size: 1.1rem; font-weight: 600; margin: 0; }

    /* ==========================================================================
       🖨️ CONTROL DE HOJA DE IMPRESIÓN NATIVA EN BLANCO Y NEGRO (ANTI-TINTA)
       ========================================================================== */
    @media print {
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        
        .cash-dark-wrapper {
            background-color: #ffffff !important;
            color: #000000 !important;
            padding: 0; margin: 0;
        }

        /* Ocultar barra de navegación, footer y botones operativos */
        nav, footer, .header-buttons, .table-premium-header {
            display: none !important;
        }

        .cash-main-title {
            color: #000000 !important;
            font-size: 24px;
            -webkit-text-fill-color: initial !important;
            background: none !important;
        }
        
        .cash-main-title i { color: #000000 !important; }
        .cash-main-subtitle { color: #555555 !important; }

        /* Transformar Tarjetas de Stats a Recuadros Contables con Borde */
        .cash-stats-grid {
            display: table !important;
            width: 100%;
            margin-bottom: 30px;
        }
        .cash-stat-card {
            display: table-cell !important;
            background: #ffffff !important;
            border: 1px solid #cccccc !important;
            padding: 15px !important;
            color: #000000 !important;
            box-shadow: none !important;
            width: 33.33%;
        }
        .stat-icon-box { display: none !important; }
        .stat-number { color: #000000 !important; font-size: 20px; }
        .stat-label { color: #555555 !important; }

        /* Transformar Tabla para Copia de Seguridad Física */
        .premium-table-wrapper { border: none !important; box-shadow: none !important; }
        .premium-data-table { width: 100% !important; border: 1px solid #000000 !important; }
        .premium-data-table th {
            background: #e9ecef !important;
            color: #000000 !important;
            border-bottom: 2px solid #000000 !important;
            padding: 10px !important;
        }
        .premium-data-table td {
            background: #ffffff !important;
            color: #000000 !important;
            border-bottom: 1px solid #dddddd !important;
            padding: 10px !important;
        }
        .td-time, .td-amount-green, .td-username { color: #000000 !important; }
        .badge-concept-pill { background: none !important; color: #000000 !important; padding: 0; font-weight: bold; }
        
        .premium-total-footer-row td {
            background: #e9ecef !important;
            border-top: 2px solid #000000 !important;
        }
        .text-right-label, .td-final-total { color: #000000 !important; }
    }
</style>
@endsection