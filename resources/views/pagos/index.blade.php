@extends('layouts.app')

@section('title', 'Caja y Pagos')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-cash-register"></i> Caja Chica</h1>
        <div class="header-buttons">
            <button class="btn-primary" onclick="window.location.href='{{ route('caja.index') }}'">
                <i class="fas fa-chart-line"></i> Resumen del Día
            </button>
        </div>
    </div>

    <div class="stats-cards">
        <div class="stat-card blue">
            <i class="fas fa-dollar-sign"></i>
            <div class="number">${{ number_format($pagos->sum('monto'), 2) }}</div>
            <div class="label">Total Recaudado</div>
        </div>
        <div class="stat-card green">
            <i class="fas fa-money-bill"></i>
            <div class="number">${{ number_format($pagos->where('metodo_pago', 'efectivo')->sum('monto'), 2) }}</div>
            <div class="label">Efectivo</div>
        </div>
        <div class="stat-card orange">
            <i class="fas fa-credit-card"></i>
            <div class="number">${{ number_format($pagos->where('metodo_pago', 'tarjeta')->sum('monto'), 2) }}</div>
            <div class="label">Tarjeta</div>
        </div>
        <div class="stat-card purple">
            <i class="fas fa-exchange-alt"></i>
            <div class="number">${{ number_format($pagos->where('metodo_pago', 'transferencia')->sum('monto'), 2) }}</div>
            <div class="label">Transferencia</div>
        </div>
    </div>

    <div class="data-table">
        <h3 style="padding: 1rem; margin: 0;"><i class="fas fa-history"></i> Historial de Transacciones</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagos as $pago)
                <tr>
                    <td>#{{ $pago->id }}</td>
                    <td>
                        <span class="badge {{ $pago->concepto == 'alquiler' ? 'badge-success' : 'badge-warning' }}">
                            {{ $pago->concepto == 'alquiler' ? '📀 Alquiler' : '⚠️ Multa' }}
                        </span>
                    </td>
                    <td class="text-success">${{ number_format($pago->monto, 2) }}</td>
                    <td>
                        @if($pago->metodo_pago == 'efectivo')
                            <span class="payment-cash">💵 Efectivo</span>
                        @elseif($pago->metodo_pago == 'tarjeta')
                            <span class="payment-card">💳 Tarjeta</span>
                        @else
                            <span class="payment-transfer">🏦 Transferencia</span>
                        @endif
                    </td>
                    <td>{{ $pago->usuario->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}</td>
                    <td><span class="status-completed"><i class="fas fa-check-circle"></i> Pagado</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $pagos->links() }}
        </div>
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
.data-table { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
table { width: 100%; border-collapse: collapse; }
th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
th { background: #667eea; color: white; }
tr:hover { background: #f5f5f5; }
.badge-success { background: #4caf50; color: white; padding: 0.2rem 0.5rem; border-radius: 5px; }
.badge-warning { background: #ff9800; color: white; padding: 0.2rem 0.5rem; border-radius: 5px; }
.payment-cash { color: #4caf50; font-weight: bold; }
.payment-card { color: #2196f3; font-weight: bold; }
.payment-transfer { color: #9c27b0; font-weight: bold; }
.text-success { color: #4caf50; font-weight: bold; }
.status-completed { color: #4caf50; }
.btn-primary { background: #667eea; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; }
.pagination { padding: 1rem; text-align: center; }
</style>
@endsection