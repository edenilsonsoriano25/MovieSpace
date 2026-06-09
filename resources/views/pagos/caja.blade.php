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
                <button class="btn-premium-action btn-pdf-report" onclick="generarPDF()">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </button>
                <button class="btn-premium-action btn-print-report" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <a href="{{ route('pagos.index') }}" class="btn-premium-back-link">
                    <i class="fas fa-arrow-left"></i> Ver Historial
                </a>
            </div>
        </div>

        <!-- CONTENEDOR PRINCIPAL PARA IMPRESIÓN Y PDF -->
        <div id="reportContent">
            <div class="cash-stats-grid print-stats">
                <div class="cash-stat-card border-glow-blue print-card">
                    <div class="stat-icon-box icon-blue"><i class="fas fa-calendar-day"></i></div>
                    <div class="stat-info">
                        <div class="stat-number">{{ \Carbon\Carbon::now('America/El_Salvador')->format('d/m/Y') }}</div>
                        <div class="stat-label">Fecha del Arqueo</div>
                    </div>
                </div>
                
                <div class="cash-stat-card border-glow-teal print-card">
                    <div class="stat-icon-box icon-teal"><i class="fas fa-dollar-sign"></i></div>
                    <div class="stat-info">
                        <div class="stat-number">${{ number_format($totalHoy, 2) }}</div>
                        <div class="stat-label">Total Liquidado</div>
                    </div>
                </div>
                
                <div class="cash-stat-card border-glow-orange print-card">
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
                                <td class="td-time">{{ \Carbon\Carbon::parse($pago->created_at)->timezone('America/El_Salvador')->format('h:i:s A') }}</td>
                                <td>
                                    @if($pago->concepto == 'alquiler')
                                        <span class="badge-concept-pill concept-pill-success">📀 Alquiler</span>
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
                        </tfoot>
                    </table>
                @endif
            </div>
        </div>

        <!-- CONTENEDOR OCULTO PARA IMPRESIÓN CON MISMA ESTÉTICA QUE PDF -->
        <div id="printContent" style="display: none;">
            <div class="print-header">
                <div class="print-logo">
                    <div class="print-logo-icon">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="print-logo-text">
                        <h1>MOVIESPACE</h1>
                        <p>Sistema de Control de Inventarios y Alquileres</p>
                    </div>
                </div>
                <div class="print-title">
                    <h2>REPORTE DE CAJA</h2>
                    <p>Arqueo Diario</p>
                </div>
            </div>

            <div class="print-divider"></div>

            <div class="print-info">
                <div class="print-info-left">
                    <p><strong>📅 Fecha de Emisión:</strong> <span id="printFechaActual"></span></p>
                    <p><strong>⏰ Hora de Generación:</strong> <span id="printHoraActual"></span></p>
                    <p><strong>🏢 Sucursal:</strong> Jayaque, El Salvador</p>
                </div>
                <div class="print-info-right">
                    <p><strong>👤 Generado por:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>🔹 Rol:</strong> {{ auth()->user()->rol === 'admin' ? 'Administrador' : 'Trabajador' }}</p>
                    <p><strong>📊 Tipo:</strong> Reporte de Caja Diario</p>
                </div>
            </div>

            <div class="print-stats-container">
                <div class="print-stat-card">
                    <span class="print-stat-label">Fecha del Arqueo</span>
                    <span class="print-stat-number">{{ \Carbon\Carbon::now('America/El_Salvador')->format('d/m/Y') }}</span>
                </div>
                <div class="print-stat-card">
                    <span class="print-stat-label">Total Liquidado</span>
                    <span class="print-stat-number">${{ number_format($totalHoy, 2) }}</span>
                </div>
                <div class="print-stat-card">
                    <span class="print-stat-label">Transacciones</span>
                    <span class="print-stat-number">{{ $pagosHoy->count() }}</span>
                </div>
            </div>

            <h3 class="print-table-title">📋 Libro de Operaciones Diarias</h3>

            <table class="print-table">
                <thead>
                    <tr>
                        <th>Hora Marca</th>
                        <th>Concepto de Pago</th>
                        <th>Monto Neto</th>
                        <th>Método Utilizado</th>
                        <th>Usuario Asignado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagosHoy as $pago)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($pago->created_at)->timezone('America/El_Salvador')->format('h:i:s A') }}</td>
                        <td>{{ $pago->concepto == 'alquiler' ? '📀 Alquiler' : '⚠️ Multa' }}</td>
                        <td style="text-align: right;">${{ number_format($pago->monto, 2) }}</td>
                        <td>
                            @if($pago->metodo_pago == 'efectivo') 💵 Efectivo
                            @elseif($pago->metodo_pago == 'tarjeta') 💳 Tarjeta
                            @else 🏦 Transferencia
                            @endif
                        </td>
                        <td>{{ $pago->usuario->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="print-total-row">
                        <td colspan="2">TOTAL CONCILIADO</td>
                        <td style="text-align: right;">${{ number_format($totalHoy, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>

            <div class="print-certification">
                <strong>📌 Nota de Certificación:</strong> Este documento constituye un arqueo de caja válido generado por el software MovieSpace basándose en las transacciones registradas durante la jornada. La información aquí presentada es fiel a los movimientos financieros procesados en el sistema.
            </div>

            <div class="print-signatures">
                <div class="print-signature">
                    <div class="signature-line"></div>
                    <p>Firma del Encargado de Caja</p>
                    <strong>{{ auth()->user()->name }}</strong>
                </div>
                <div class="print-signature">
                    <div class="signature-line"></div>
                    <p>Sello y Visto Bueno</p>
                    <strong>Administración MovieSpace</strong>
                </div>
            </div>

            <div class="print-footer">
                MovieSpace - Sistema de Alquiler de Películas | Sucursal Jayaque, El Salvador
            </div>
        </div>

    </div>
</div>

<style>
    .cash-dark-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem;
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
        flex-wrap: wrap;
    }

    .btn-pdf-report {
        background: linear-gradient(45deg, #e63946, #d62828);
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
        box-shadow: 0 4px 15px rgba(230, 57, 70, 0.3);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-pdf-report:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(230, 57, 70, 0.4);
    }

    .btn-print-report {
        background: linear-gradient(45deg, #2ec4b6, #009688);
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
        box-shadow: 0 4px 15px rgba(46, 196, 182, 0.3);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-print-report:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(46, 196, 182, 0.4);
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

    .premium-data-table tbody tr:hover td {
        background-color: #222731 !important;
        color: #ffffff !important;
        cursor: pointer;
    }

    .td-time { font-family: monospace; color: #ff4b2b; font-weight: 600; }
    .td-amount-green { color: #2ec4b6 !important; font-weight: 700; font-family: monospace; }
    .td-method { color: #cdcdcd; }
    .td-username { color: #ffffff; font-weight: 600; }

    .badge-concept-pill {
        font-size: 0.76rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
    }
    .concept-pill-success { background-color: rgba(46, 196, 182, 0.12); color: #2ec4b6; }
    .concept-pill-warning { background-color: rgba(255, 152, 0, 0.12); color: #ffb74d; }

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

    /* ============================================ */
    /* ESTILOS PARA IMPRESIÓN (MISMA ESTÉTICA QUE PDF) */
    /* ============================================ */
    @media print {
        body {
            background-color: #ffffff !important;
            padding: 0;
            margin: 0;
        }
        
        /* Ocultar elementos de la interfaz */
        .cash-dark-wrapper {
            background-color: #ffffff !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        nav, footer, .cash-page-header, .header-buttons, .btn-premium-back-link,
        .btn-pdf-report, .btn-print-report, .stat-icon-box, .premium-table-wrapper,
        .cash-stats-grid, .table-premium-header {
            display: none !important;
        }
        
        /* Mostrar solo el contenido de impresión */
        #printContent {
            display: block !important;
            padding: 20px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2b2b2b;
        }
    }

    /* Estilos del contenido de impresión */
    #printContent {
        max-width: 100%;
        background: white;
        color: #2b2b2b;
    }

    .print-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .print-logo {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .print-logo-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .print-logo-icon i {
        font-size: 24px;
        color: white;
    }

    .print-logo-text h1 {
        margin: 0;
        color: #ff4b2b;
        font-size: 24px;
        font-weight: 800;
    }

    .print-logo-text p {
        margin: 4px 0 0 0;
        color: #6c757d;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .print-title {
        text-align: right;
    }

    .print-title h2 {
        margin: 0;
        color: #1a1d24;
        font-size: 18px;
        font-weight: 700;
    }

    .print-title p {
        margin: 4px 0 0 0;
        color: #ff4b2b;
        font-size: 13px;
        font-weight: 600;
    }

    .print-divider {
        height: 2px;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        margin-bottom: 25px;
    }

    .print-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        font-size: 12px;
        color: #495057;
    }

    .print-info-left p, .print-info-right p {
        margin: 5px 0;
    }

    .print-stats-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 40px;
    }

    .print-stat-card {
        flex: 1;
        border: 1px solid #dee2e6;
        padding: 20px;
        text-align: center;
        border-radius: 12px;
        background-color: #f8f9fa;
    }

    .print-stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 6px;
    }

    .print-stat-number {
        font-size: 22px;
        font-weight: 800;
        color: #1a1d24;
    }

    .print-table-title {
        color: #1a1d24;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 15px;
        border-bottom: 2px solid #ff4b2b;
        padding-bottom: 8px;
        display: inline-block;
    }

    .print-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 12px;
    }

    .print-table th {
        background-color: #f8f9fa;
        padding: 12px;
        text-align: left;
        font-weight: 700;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }

    .print-table td {
        padding: 10px;
        border-bottom: 1px solid #e0e0e0;
        color: #2b2b2b;
    }

    .print-total-row {
        background-color: #f8f9fa;
        font-weight: 800;
        border-top: 2px solid #dee2e6;
    }

    .print-total-row td {
        padding: 12px;
    }

    .print-certification {
        background-color: #fff9db;
        border-left: 4px solid #fcc419;
        padding: 15px;
        border-radius: 6px;
        font-size: 11px;
        line-height: 1.5;
        color: #664d03;
        margin-top: 40px;
    }

    .print-signatures {
        display: flex;
        justify-content: space-between;
        margin-top: 50px;
    }

    .print-signature {
        text-align: center;
        width: 45%;
    }

    .signature-line {
        width: 80%;
        border-bottom: 1px solid #dee2e6;
        margin: 0 auto 8px auto;
    }

    .print-signature p {
        margin: 0;
        font-size: 11px;
        color: #6c757d;
    }

    .print-signature strong {
        font-size: 12px;
        color: #1a1d24;
    }

    .print-footer {
        text-align: center;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
        font-size: 10px;
        color: #adb5bd;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function generarPDF() {
    const fechaActual = new Date().toLocaleDateString('es-SV', { year: 'numeric', month: 'long', day: 'numeric' });
    const horaActual = new Date().toLocaleTimeString('es-SV', { hour: '2-digit', minute: '2-digit' });
    const trabajadorNombre = "{{ auth()->user()->name }}";
    const trabajadorRol = "{{ auth()->user()->rol }}";
    const sucursal = "Jayaque, El Salvador";
    
    const filas = document.querySelectorAll('.premium-data-table tbody tr');
    let tablaHTML = '';
    
    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td');
        if (celdas.length >= 5) {
            tablaHTML += `
                <tr style="border-bottom: 1px solid #e0e0e0;">
                    <td style="padding: 10px; text-align: left;">${celdas[0].innerText}</td>
                    <td style="padding: 10px; text-align: left;">${celdas[1].innerText}</td>
                    <td style="padding: 10px; text-align: right;">${celdas[2].innerText}</td>
                    <td style="padding: 10px; text-align: left;">${celdas[3].innerText}</td>
                    <td style="padding: 10px; text-align: right;">${celdas[4].innerText}</td>
                </tr>
            `;
        }
    });
    
    const totalMonto = "{{ number_format($totalHoy, 2) }}";
    const totalTransacciones = "{{ $pagosHoy->count() }}";
    
    const element = document.createElement('div');
    element.innerHTML = `
        <div style="padding: 40px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #2b2b2b; background-color: #ffffff;">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(45deg, #ff416c, #ff4b2b); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-film" style="color: white; font-size: 24px;"></i>
                    </div>
                    <div>
                        <h1 style="margin: 0; color: #ff4b2b; font-size: 24px; font-weight: 800;">MOVIESPACE</h1>
                        <p style="margin: 4px 0 0 0; color: #6c757d; font-size: 11px; font-weight: 600; text-transform: uppercase;">Sistema de Control de Inventarios y Alquileres</p>
                    </div>
                </div>
                <div style="text-align: right;">
                    <h2 style="margin: 0; color: #1a1d24; font-size: 18px; font-weight: 700;">REPORTE DE CAJA</h2>
                    <p style="margin: 4px 0 0 0; color: #ff4b2b; font-size: 13px; font-weight: 600;">Arqueo Diario</p>
                </div>
            </div>

            <div style="height: 2px; background: linear-gradient(45deg, #ff416c, #ff4b2b); margin-bottom: 25px;"></div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 30px; font-size: 12px; color: #495057;">
                <div>
                    <p><strong>📅 Fecha de Emisión:</strong> ${fechaActual}</p>
                    <p><strong>⏰ Hora de Generación:</strong> ${horaActual}</p>
                    <p><strong>🏢 Sucursal:</strong> ${sucursal}</p>
                </div>
                <div style="text-align: right;">
                    <p><strong>👤 Generado por:</strong> ${trabajadorNombre}</p>
                    <p><strong>🔹 Rol:</strong> ${trabajadorRol === 'admin' ? 'Administrador' : 'Trabajador'}</p>
                    <p><strong>📊 Tipo:</strong> Reporte de Caja Diario</p>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; gap: 20px; margin-bottom: 40px;">
                <div style="flex: 1; border: 1px solid #dee2e6; padding: 20px; text-align: center; border-radius: 12px; background-color: #f8f9fa;">
                    <span style="font-size: 11px; font-weight: 700; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Fecha del Arqueo</span>
                    <span style="font-size: 22px; font-weight: 800; color: #1a1d24;">${new Date().toLocaleDateString('es-SV')}</span>
                </div>
                <div style="flex: 1; border: 1px solid #dee2e6; padding: 20px; text-align: center; border-radius: 12px; background-color: #f8f9fa;">
                    <span style="font-size: 11px; font-weight: 700; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Total Liquidado</span>
                    <span style="font-size: 22px; font-weight: 800; color: #2ec4b6;">$${totalMonto}</span>
                </div>
                <div style="flex: 1; border: 1px solid #dee2e6; padding: 20px; text-align: center; border-radius: 12px; background-color: #f8f9fa;">
                    <span style="font-size: 11px; font-weight: 700; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Transacciones</span>
                    <span style="font-size: 22px; font-weight: 800; color: #ff9f43;">${totalTransacciones}</span>
                </div>
            </div>

            <h3 style="color: #1a1d24; font-size: 16px; font-weight: 700; margin-bottom: 15px; border-bottom: 2px solid #ff4b2b; padding-bottom: 8px; display: inline-block;">
                📋 Libro de Operaciones Diarias
            </h3>
            
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; font-weight: 700; color: #495057;">Hora Marca</th>
                        <th style="padding: 12px; text-align: left; font-weight: 700; color: #495057;">Concepto de Pago</th>
                        <th style="padding: 12px; text-align: right; font-weight: 700; color: #495057;">Monto Neto</th>
                        <th style="padding: 12px; text-align: left; font-weight: 700; color: #495057;">Método Utilizado</th>
                        <th style="padding: 12px; text-align: right; font-weight: 700; color: #495057;">Usuario Asignado</th>
                    </tr>
                </thead>
                <tbody>
                    ${tablaHTML || `
                        <tr>
                            <td colspan="5" style="padding: 40px; text-align: center; color: #6c757d;">
                                No hay transacciones registradas en esta fecha
                            </td>
                        </tr>
                    `}
                </tbody>
                <tfoot>
                    <tr style="background-color: #f8f9fa; border-top: 2px solid #dee2e6;">
                        <td colspan="2" style="padding: 12px; font-weight: 800; color: #1a1d24;">TOTAL CONCILIADO</td>
                        <td style="padding: 12px; text-align: right; font-weight: 800; color: #2ec4b6;">$${totalMonto}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>

            <div style="background-color: #fff9db; border-left: 4px solid #fcc419; padding: 15px; border-radius: 6px; font-size: 11px; line-height: 1.5; color: #664d03; margin-top: 40px;">
                <strong>📌 Nota de Certificación:</strong> Este documento constituye un arqueo de caja válido generado por el software MovieSpace basándose en las transacciones registradas durante la jornada. La información aquí presentada es fiel a los movimientos financieros procesados en el sistema.
            </div>

            <div style="display: flex; justify-content: space-between; margin-top: 50px;">
                <div style="text-align: center; width: 45%;">
                    <div style="width: 80%; border-bottom: 1px solid #dee2e6; margin: 0 auto 8px auto;"></div>
                    <p style="margin: 0; font-size: 11px; color: #6c757d;">Firma del Encargado de Caja</p>
                    <strong style="font-size: 12px; color: #1a1d24;">${trabajadorNombre}</strong>
                </div>
                <div style="text-align: center; width: 45%;">
                    <div style="width: 80%; border-bottom: 1px solid #dee2e6; margin: 0 auto 8px auto;"></div>
                    <p style="margin: 0; font-size: 11px; color: #6c757d;">Sello y Visto Bueno</p>
                    <strong style="font-size: 12px; color: #1a1d24;">Administración MovieSpace</strong>
                </div>
            </div>

            <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e9ecef; font-size: 10px; color: #adb5bd;">
                MovieSpace - Sistema de Alquiler de Películas | Sucursal Jayaque, El Salvador | Generado el ${fechaActual} a las ${horaActual}
            </div>
        </div>
    `;
    
    const opt = {
        margin: [0.5, 0.5, 0.5, 0.5],
        filename: `Arqueo_Caja_${new Date().toLocaleDateString('es-SV').replace(/\//g, '-')}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    
    Swal.fire({
        title: 'Generando PDF',
        text: 'Por favor espere mientras se compila el reporte de caja...',
        icon: 'info',
        background: '#1a1d24',
        color: '#ffffff',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        html2pdf().set(opt).from(element).save().then(() => {
            Swal.close();
            Swal.fire({
                title: '✅ PDF Generado',
                text: 'El reporte de caja se ha descargado correctamente.',
                icon: 'success',
                background: '#1a1d24',
                color: '#ffffff',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }, 500);
}

// Para la impresión, actualizar fechas dinámicas
document.addEventListener('DOMContentLoaded', function() {
    const fechaActual = new Date().toLocaleDateString('es-SV', { year: 'numeric', month: 'long', day: 'numeric' });
    const horaActual = new Date().toLocaleTimeString('es-SV', { hour: '2-digit', minute: '2-digit' });
    
    const fechaSpan = document.getElementById('printFechaActual');
    const horaSpan = document.getElementById('printHoraActual');
    
    if (fechaSpan) fechaSpan.innerText = fechaActual;
    if (horaSpan) horaSpan.innerText = horaActual;
});
</script>
@endsection