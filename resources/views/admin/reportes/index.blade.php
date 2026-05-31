@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> Reportes Financieros</h1>
        <div class="header-buttons">
            <select id="mesReporte" class="form-select">
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
            </select>
            <select id="anioReporte" class="form-select">
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026" selected>2026</option>
            </select>
            <button class="btn-primary" onclick="generarPDF()">
                <i class="fas fa-file-pdf"></i> Generar PDF
            </button>
        </div>
    </div>

    <div class="stats-cards">
        <div class="stat-card blue">
            <i class="fas fa-shopping-cart"></i>
            <div class="number">{{ $totalPrestamos }}</div>
            <div class="label">Total Préstamos</div>
        </div>
        <div class="stat-card green">
            <i class="fas fa-dollar-sign"></i>
            <div class="number">${{ number_format($totalIngresos, 2) }}</div>
            <div class="label">Ingresos Totales</div>
        </div>
        <div class="stat-card orange">
            <i class="fas fa-clock"></i>
            <div class="number">{{ $prestamosActivos }}</div>
            <div class="label">Préstamos Activos</div>
        </div>
        <div class="stat-card purple">
            <i class="fas fa-chart-pie"></i>
            <div class="number">{{ number_format(($prestamosActivos / max($totalPrestamos, 1)) * 100, 1) }}%</div>
            <div class="label">Tasa Activos</div>
        </div>
    </div>

    <div class="charts-container">
        <div class="chart-box">
            <h3><i class="fas fa-chart-bar"></i> Préstamos por Mes</h3>
            <canvas id="prestamosChart"></canvas>
        </div>
        <div class="chart-box">
            <h3><i class="fas fa-chart-pie"></i> Distribución de Pagos</h3>
            <canvas id="pagosChart"></canvas>
        </div>
    </div>

    <div class="data-table">
        <h3><i class="fas fa-table"></i> Resumen Mensual</h3>
        <table>
            <thead>
                <tr>
                    <th>Mes</th>
                    <th>Préstamos</th>
                    <th>Ingresos</th>
                    <th>Multas</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                @endphp
                @foreach($meses as $index => $mes)
                <tr>
                    <td>{{ $mes }}</td>
                    <td>{{ rand(5, 30) }}</td>
                    <td>${{ number_format(rand(50, 300), 2) }}</td>
                    <td>${{ number_format(rand(0, 50), 2) }}</td>
                    <td class="text-success">${{ number_format(rand(50, 350), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
// Gráfico de barras
const ctx = document.getElementById('prestamosChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        datasets: [{
            label: 'Préstamos',
            data: [12, 19, 15, 17, 14, 22, 25, 28, 20, 18, 15, 10],
            backgroundColor: '#667eea',
            borderRadius: 5
        }, {
            label: 'Ingresos ($)',
            data: [120, 190, 150, 170, 140, 220, 250, 280, 200, 180, 150, 100],
            backgroundColor: '#4caf50',
            borderRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        }
    }
});

// Gráfico circular
const ctx2 = document.getElementById('pagosChart').getContext('2d');
new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: ['Alquileres', 'Multas'],
        datasets: [{
            data: [85, 15],
            backgroundColor: ['#667eea', '#ff9800'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

function generarPDF() {
    const mes = document.getElementById('mesReporte').value;
    const anio = document.getElementById('anioReporte').value;
    const nombreMes = document.getElementById('mesReporte').options[document.getElementById('mesReporte').selectedIndex].text;
    
    const element = document.createElement('div');
    element.innerHTML = `
        <div style="padding: 2rem; font-family: Arial, sans-serif;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1>📊 MovieSpace - Reporte Financiero</h1>
                <h3>${nombreMes} ${anio}</h3>
                <p>Fecha de generación: ${new Date().toLocaleString()}</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem;">
                <div style="border: 1px solid #ddd; padding: 1rem; text-align: center; border-radius: 10px;">
                    <h3>Total Préstamos</h3>
                    <p style="font-size: 2rem; color: #667eea;">{{ $totalPrestamos }}</p>
                </div>
                <div style="border: 1px solid #ddd; padding: 1rem; text-align: center; border-radius: 10px;">
                    <h3>Ingresos Totales</h3>
                    <p style="font-size: 2rem; color: #4caf50;">${{ number_format($totalIngresos, 2) }}</p>
                </div>
                <div style="border: 1px solid #ddd; padding: 1rem; text-align: center; border-radius: 10px;">
                    <h3>Préstamos Activos</h3>
                    <p style="font-size: 2rem; color: #ff9800;">{{ $prestamosActivos }}</p>
                </div>
            </div>
            <div style="border-top: 2px solid #667eea; padding-top: 1rem;">
                <p style="text-align: center;">Reporte generado por MovieSpace - Sistema de Alquiler de Películas</p>
            </div>
        </div>
    `;
    
    const opt = {
        margin: [1, 1, 1, 1],
        filename: `reporte_${nombreMes}_${anio}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };
    
    html2pdf().set(opt).from(element).save();
    
    alert(`📄 Generando reporte de ${nombreMes} ${anio}...`);
}
</script>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    color: white;
    flex-wrap: wrap;
    gap: 1rem;
}
.header-buttons {
    display: flex;
    gap: 1rem;
    align-items: center;
}
.form-select {
    padding: 0.5rem;
    border-radius: 5px;
    border: 1px solid #ddd;
    background: white;
}
.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.stat-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}
.stat-card:hover { transform: translateY(-5px); }
.stat-card.blue { border-bottom: 4px solid #2196f3; }
.stat-card.green { border-bottom: 4px solid #4caf50; }
.stat-card.orange { border-bottom: 4px solid #ff9800; }
.stat-card.purple { border-bottom: 4px solid #9c27b0; }
.stat-card i { font-size: 2rem; margin-bottom: 0.5rem; }
.stat-card .number { font-size: 2rem; font-weight: bold; }
.stat-card .label { color: #666; }
.charts-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.chart-box {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.chart-box h3 { margin-bottom: 1rem; color: #333; }
.data-table { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.data-table h3 { padding: 1rem; margin: 0; background: #f8f9fa; }
table { width: 100%; border-collapse: collapse; }
th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
th { background: #667eea; color: white; }
tr:hover { background: #f5f5f5; }
.text-success { color: #4caf50; font-weight: bold; }
.btn-primary { background: #667eea; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px; cursor: pointer; }
</style>
@endsection