@extends('layouts.app')

@section('title', 'Resumen de Caja')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> Resumen del Día</h1>
        <div class="header-buttons">
            <button class="btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimir Reporte
            </button>
            <button class="btn-secondary" onclick="window.location.href='{{ route('pagos.index') }}'">
                <i class="fas fa-arrow-left"></i> Ver Historial
            </button>
        </div>
    </div>

    <div class="stats-cards">
        <div class="stat-card blue">
            <i class="fas fa-calendar-day"></i>
            <div class="number">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
            <div class="label">Fecha</div>
        </div>
        <div class="stat-card green">
            <i class="fas fa-dollar-sign"></i>
            <div class="number">${{ number_format($totalHoy, 2) }}</div>
            <div class="label">Total del Día</div>
        </div>
        <div class="stat-card orange">
            <i class="fas fa-receipt"></i>
            <div class="number">{{ $pagosHoy->count() }}</div>
            <div class="label">Transacciones</div>
        </div>
    </div>

    <div class="data-table">
        <h3 style="padding: 1rem; margin: 0;"><i class="fas fa-list"></i> Transacciones de Hoy</h3>
        @if($pagosHoy->isEmpty())
            <div class="empty-state">
                <i class="fas fa-coins"></i>
                <p>No hay transacciones registradas hoy</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagosHoy as $pago)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($pago->created_at)->format('H:i:s') }}</td>
                        <td>
                            <span class="badge {{ $pago->concepto == 'alquiler' ? 'badge-success' : 'badge-warning' }}">
                                {{ $pago->concepto == 'alquiler' ? '📀 Alquiler' : '⚠️ Multa' }}
                            </span>
                        </td>
                        <td class="text-success">${{ number_format($pago->monto, 2) }}</td>
                        <td>
                            @if($pago->metodo_pago == 'efectivo')
                                💵 Efectivo
                            @elseif($pago->metodo_pago == 'tarjeta')
                                💳 Tarjeta
                            @else
                                🏦 Transferencia
                            @endif
                        </td>
                        <td>{{ $pago->usuario->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2"><strong>TOTAL</strong></td>
                        <td><strong>${{ number_format($totalHoy, 2) }}</strong></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        @endif
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    color: white;
}
.header-buttons {
    display: flex;
    gap: 1rem;
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
}
.stat-card.blue { border-bottom: 4px solid #2196f3; }
.stat-card.green { border-bottom: 4px solid #4caf50; }
.stat-card.orange { border-bottom: 4px solid #ff9800; }
.stat-card i { font-size: 2rem; margin-bottom: 0.5rem; }
.stat-card .number { font-size: 2rem; font-weight: bold; }
.stat-card .label { color: #666; }
.data-table { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
table { width: 100%; border-collapse: collapse; }
th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
th { background: #667eea; color: white; }
.total-row { background: #f0f0f0; font-weight: bold; }
.empty-state { text-align: center; padding: 3rem; color: #999; }
.empty-state i { font-size: 3rem; margin-bottom: 1rem; }
.text-success { color: #4caf50; font-weight: bold; }
.badge-success { background: #4caf50; color: white; padding: 0.2rem 0.5rem; border-radius: 5px; }
.badge-warning { background: #ff9800; color: white; padding: 0.2rem 0.5rem; border-radius: 5px; }
.btn-primary { background: #667eea; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px; cursor: pointer; }
.btn-secondary { background: #6c757d; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px; cursor: pointer; }
</style>
@endsection