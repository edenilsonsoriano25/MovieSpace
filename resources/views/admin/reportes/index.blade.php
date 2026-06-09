@extends('layouts.app')

@section('title', 'Reportes Financieros')

@section('content')
<div class="reports-dark-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="reports-page-header">
            <div class="header-left">
                <h1 class="reports-main-title"><i class="fas fa-chart-line"></i> Reportes Financieros</h1>
                <p class="reports-main-subtitle">Analiza el rendimiento contable, balances de caja y estadísticas operacionales globales.</p>
            </div>
            
            <div class="header-filters-group">
                <div class="select-wrapper">
                    <select id="mesReporte">
                        @php
                            $mesesAnio = [
                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 
                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                            ];
                            $mesActualNum = \Carbon\Carbon::now()->month;
                        @endphp
                        @foreach($mesesAnio as $numMes => $nombreMes)
                            @if($numMes <= $mesActualNum)
                                <option value="{{ $numMes }}" {{ $numMes == $mesActualNum ? 'selected' : '' }}>{{ $nombreMes }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="select-wrapper">
                    <select id="anioReporte">
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026" selected>2026</option>
                    </select>
                </div>
                <button class="btn-premium-action btn-generate-pdf" onclick="generarPDF()">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </button>
            </div>
        </div>

        <div class="reports-stats-grid">
            <div class="reports-stat-card border-glow-blue">
                <div class="stat-icon-box icon-blue"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $totalPrestamos }}</div>
                    <div class="stat-label">Total Préstamos</div>
                </div>
            </div>
            
            <div class="reports-stat-card border-glow-teal">
                <div class="stat-icon-box icon-teal"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-info">
                    <div class="stat-number">${{ number_format($totalIngresos, 2) }}</div>
                    <div class="stat-label">Ingresos Totales</div>
                </div>
            </div>
            
            <div class="reports-stat-card border-glow-orange">
                <div class="stat-icon-box icon-orange"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $prestamosActivos }}</div>
                    <div class="stat-label">Préstamos Activos</div>
                </div>
            </div>

            <div class="reports-stat-card border-glow-purple">
                <div class="stat-icon-box icon-purple"><i class="fas fa-chart-pie"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ number_format(($prestamosActivos / max($totalPrestamos, 1)) * 100, 1) }}%</div>
                    <div class="stat-label">Tasa de Activos</div>
                </div>
            </div>
        </div>

        <div class="charts-streaming-grid">
            <div class="chart-premium-box">
                <h3 class="chart-box-title"><i class="fas fa-chart-bar"></i> Préstamos e Ingresos Anuales</h3>
                <div class="canvas-wrapper">
                    <canvas id="prestamosChart"></canvas>
                </div>
            </div>
            <div class="chart-premium-box">
                <h3 class="chart-box-title"><i class="fas fa-chart-pie"></i> Distribución de Canales de Pago</h3>
                <div class="canvas-wrapper">
                    <canvas id="pagosChart"></canvas>
                </div>
            </div>
        </div>

        <div class="premium-table-wrapper">
            <div class="table-premium-header">
                <h3><i class="fas fa-table"></i> Historial Consolidador Mensual</h3>
            </div>
            <table class="premium-data-table">
                <thead>
                    <tr>
                        <th>Mes de Gestión</th>
                        <th>Préstamos Procesados</th>
                        <th>Ingresos por Alquiler</th>
                        <th>Recargos por Multas</th>
                        <th style="text-align: right;">Total Neto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mesesAnio as $numMes => $nombreMes)
                        @if($numMes <= $mesActualNum)
                            @php
                                $cantPrestamos = $conteosMensuales[$numMes] ?? 0;
                                $ingAlquiler = $ingresosMensuales[$numMes] ?? 0;
                                $moraMultas = $multasMensuales[$numMes] ?? 0;
                                $totalNetoFila = $ingAlquiler + $moraMultas;
                            @endphp
                            <tr>
                                <td class="td-month"><strong>{{ $nombreMes }}</strong></td>
                                <td>{{ $cantPrestamos }} ords.</td>
                                <td class="text-white-50">${{ number_format($ingAlquiler, 2) }}</td>
                                <td class="text-danger-fine">${{ number_format($moraMultas, 2) }}</td>
                                <td class="td-total-net">${{ number_format($totalNetoFila, 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.color = '#8a8a8a';
    Chart.defaults.font.family = "'Segoe UI', sans-serif";

    // Rebanamos los arreglos para que los gráficos de Chart.js terminen simétricamente en el mes actual
    const mesCorte = {{ $mesActualNum }};
    const conteosReales = @json(array_values($conteosMensuales)).slice(0, mesCorte);
    const ingresosReales = @json(array_values($ingresosMensuales)).slice(0, mesCorte);
    const etiquetasMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'].slice(0, mesCorte);

    const alquilerPuroReal = {{ $totalAlquilerPuro }};
    const multasHistoricasReales = {{ $totalMultasHistorico }};

    // 1. Gráfico de Barras Combinado Dinámico
    const ctx = document.getElementById('prestamosChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: etiquetasMeses,
            datasets: [{
                label: 'Préstamos Emitidos',
                data: conteosReales,
                backgroundColor: 'rgba(255, 65, 108, 0.85)',
                borderRadius: 6,
                borderSkipped: false
            }, {
                label: 'Ingresos Netos ($)',
                data: ingresosReales,
                backgroundColor: 'rgba(46, 196, 182, 0.85)',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 12, font: { weight: '600' } } }
            },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: 'rgba(255, 255, 255, 0.04)' } }
            }
        }
    });

    // 2. Gráfico Circular Estilizado Dinámico
    const ctx2 = document.getElementById('pagosChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Alquileres de Portada', 'Multas por Mora'],
            datasets: [{
                data: [alquilerPuroReal, multasHistoricasReales],
                backgroundColor: ['#ff4b2b', '#2ec4b6'],
                borderWidth: 4,
                borderColor: '#1a1d24',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { weight: '600' } } }
            },
            cutout: '70%'
        }
    });
});

function generarPDF() {
    const nombreMes = document.getElementById('mesReporte').options[document.getElementById('mesReporte').selectedIndex].text;
    const anio = document.getElementById('anioReporte').value;
    
    Swal.fire({
        title: 'Procesando Documento',
        text: `Compilando balance financiero de ${nombreMes} ${anio}...`,
        icon: 'info',
        background: '#1a1d24',
        color: '#ffffff',
        showConfirmButton: false,
        timer: 2000
    });

    const element = document.createElement('div');
    element.innerHTML = `
        <div style="padding: 40px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #2b2b2b; background-color: #ffffff;">
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 40px;">
                <tr>
                    <td>
                        <h1 style="margin: 0; color: #ff4b2b; font-size: 26px; font-weight: 800; letter-spacing: -0.5px;">MOVIESPACE</h1>
                        <p style="margin: 4px 0 0 0; color: #6c757d; font-size: 12px; font-weight: 600; text-transform: uppercase;">Sistema de Control de Inventarios</p>
                    </td>
                    <td style="text-align: right; vertical-align: top;">
                        <h2 style="margin: 0; color: #1a1d24; font-size: 18px; font-weight: 700;">REPORTE FINANCIERO</h2>
                        <p style="margin: 4px 0 0 0; color: #ff4b2b; font-size: 14px; font-weight: 700;">Período: ${nombreMes} — ${anio}</p>
                    </td>
                </tr>
            </table>

            <div style="height: 1px; background-color: #e9ecef; margin-bottom: 30px;"></div>

            <table style="width: 100%; margin-bottom: 40px; font-size: 13px; color: #495057;">
                <tr>
                    <td><strong>Sucursal Operativa:</strong> Jayaque, El Salvador</td>
                    <td style="text-align: right;"><strong>Fecha Emisión:</strong> ${new Date().toLocaleDateString('es-SV')}</td>
                </tr>
                <tr>
                    <td><strong>Estado de Auditoría:</strong> Cierre mensual consolidado</td>
                    <td style="text-align: right;"><strong>Hora Registro:</strong> ${new Date().toLocaleTimeString('es-SV', {hour: '2-digit', minute:'2-digit'})}</td>
                </tr>
            </table>

            <div style="display: flex; justify-content: space-between; gap: 20px; margin-bottom: 45px;">
                <div style="flex: 1; border: 1px solid #dee2e6; padding: 20px; text-align: center; border-radius: 12px; background-color: #f8f9fa;">
                    <span style="font-size: 11px; font-weight: 700; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Total Préstamos</span>
                    <span style="font-size: 28px; font-weight: 800; color: #1a1d24;">{{ $totalPrestamos }}</span>
                </div>
                <div style="flex: 1; border: 1px solid #dee2e6; padding: 20px; text-align: center; border-radius: 12px; background-color: #f8f9fa;">
                    <span style="font-size: 11px; font-weight: 700; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Ingresos Brutos</span>
                    <span style="font-size: 28px; font-weight: 800; color: #2ec4b6;">$${parseFloat('{{ $totalIngresos }}').toFixed(2)}</span>
                </div>
                <div style="flex: 1; border: 1px solid #dee2e6; padding: 20px; text-align: center; border-radius: 12px; background-color: #f8f9fa;">
                    <span style="font-size: 11px; font-weight: 700; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Cuentas Activas</span>
                    <span style="font-size: 28px; font-weight: 800; color: #ff9f43;">{{ $prestamosActivos }}</span>
                </div>
            </div>

            <div style="background-color: #fff9db; border-left: 4px solid #fcc419; padding: 15px; border-radius: 6px; font-size: 12px; line-height: 1.5; color: #664d03; margin-bottom: 50px;">
                <strong>Nota de Certificación:</strong> Este documento constituye un balance financiero algorítmico generado por el software MovieSpace basándose en las transacciones vigentes en bases de datos relacionales. Válido para reviews de contabilidad operativa de fin de mes.
            </div>

            <table style="width: 100%; margin-top: 100px; font-size: 12px; color: #6c757d;">
                <tr>
                    <td style="text-align: center; width: 50%;">
                        <div style="width: 180px; border-bottom: 1px solid #dee2e6; margin: 0 auto 8px auto;"></div>
                        Firma de Administrador General
                    </td>
                    <td style="text-align: center; width: 50%;">
                        <div style="width: 180px; border-bottom: 1px solid #dee2e6; margin: 0 auto 8px auto;"></div>
                        Sello de Auditoría Interna
                    </td>
                </tr>
            </table>
        </div>
    `;
    
    const opt = {
        margin: [0.3, 0.3, 0.3, 0.3],
        filename: `Reporte_Financiero_${nombreMes}_${anio}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    
    setTimeout(() => {
        html2pdf().set(opt).from(element).save();
    }, 500);
}
</script>

<style>
    .reports-dark-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem;
        padding: 3rem 0 5rem 0;
        color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .reports-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .reports-main-title {
        font-size: 2.6rem;
        font-weight: 800;
        margin: 0 0 0.4rem 0;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .reports-main-title i {
        color: #ff4b2b;
        -webkit-text-fill-color: initial;
        margin-right: 10px;
    }

    .reports-main-subtitle {
        color: #6c757d;
        font-size: 0.98rem;
        margin: 0;
    }

    .header-filters-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .header-filters-group select {
        padding: 11px 35px 11px 16px;
        background-color: #1a1d24;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 600;
        outline: none;
        cursor: pointer;
        font-family: inherit;
        transition: border 0.2s;
    }

    .header-filters-group select:focus { border-color: #ff4b2b; }

    .select-wrapper { position: relative; }
    .header-filters-group select { appearance: none; -webkit-appearance: none; }
    .select-wrapper::after {
        content: '\f078'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        font-size: 0.7rem; color: #6c757d; position: absolute; right: 14px; top: 50%;
        transform: translateY(-50%); pointer-events: none;
    }

    .btn-generate-pdf {
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
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-generate-pdf:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.4);
    }

    .reports-stats-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)) !important;
        gap: 1.5rem !important;
        margin-bottom: 3rem;
        width: 100%;
    }

    .reports-stat-card {
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
    .stat-number { font-size: 1.8rem; font-weight: 800; color: #fff; margin: 0; }
    .stat-label { font-size: 0.82rem; color: #6c757d; font-weight: 600; }

    .charts-streaming-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)) !important;
        gap: 2rem !important;
        margin-bottom: 3rem;
        width: 100%;
    }

    .chart-premium-box {
        background-color: #1a1d24;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.02);
    }

    .chart-box-title {
        color: #ffffff;
        font-size: 1.15rem;
        font-weight: 700;
        margin: 0 0 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-box-title i { color: #ff4b2b; }
    .canvas-wrapper { position: relative; height: 260px; width: 100%; }

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

    .td-month { color: #ffffff; }
    .text-danger-fine { color: #ef5350; font-family: monospace; font-weight: 600; }
    .td-total-net { text-align: right; color: #2ec4b6 !important; font-weight: 700; font-family: monospace; font-size: 1.05rem; }

    @media (max-width: 768px) {
        .reports-page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .header-filters-group { width: 100%; }
        .header-filters-group .select-wrapper, .btn-generate-pdf { flex: 1; width: 100%; }
        .charts-streaming-grid { grid-template-columns: 1fr !important; }
    }
</style>
@endsection