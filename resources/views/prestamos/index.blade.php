@extends('layouts.app')

@section('title', 'Préstamos')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-exchange-alt"></i> Gestión de Préstamos</h1>
        <a href="{{ route('prestamos.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Nuevo Préstamo
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error-custom">{{ session('error') }}</div>
    @endif

    <div class="stats-cards">
        <div class="stat-card blue">
            <i class="fas fa-ticket-alt"></i>
            <div class="number">{{ $prestamos->total() }}</div>
            <div class="label">Total Préstamos</div>
        </div>
        <div class="stat-card green">
            <i class="fas fa-check-circle"></i>
            <div class="number">{{ $prestamos->where('estado_prestamo', 'activo')->count() }}</div>
            <div class="label">Préstamos Activos</div>
        </div>
        <div class="stat-card red">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="number">{{ $prestamos->where('estado_prestamo', 'activo')->filter(function($p) { return now()->gt($p->fecha_limite); })->count() }}</div>
            <div class="label">Retrasados</div>
        </div>
    </div>

    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Películas</th>
                    <th>F. Salida</th>
                    <th>F. Límite</th>
                    <th>Estado</th>
                    <th>Multa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prestamos as $prestamo)
                <tr>
                    <td>#{{ $prestamo->id }}</td>
                    <td>
                        <strong>{{ $prestamo->usuario->name }}</strong><br>
                        <small>{{ $prestamo->usuario->email }}</small>
                    </td>
                    <td>
                        @foreach($prestamo->detalles as $detalle)
                            <span class="badge">{{ $detalle->pelicula->titulo }}</span>
                        @endforeach
                    </td>
                    <td>{{ \Carbon\Carbon::parse($prestamo->fecha_salida)->format('d/m/Y') }}</td>
                    <td class="{{ now()->gt($prestamo->fecha_limite) && $prestamo->estado_prestamo == 'activo' ? 'text-danger' : '' }}">
                        {{ \Carbon\Carbon::parse($prestamo->fecha_limite)->format('d/m/Y') }}
                        @if(now()->gt($prestamo->fecha_limite) && $prestamo->estado_prestamo == 'activo')
                            <span class="badge-danger">Retrasado</span>
                        @endif
                    </td>
                    <td>
                        @if($prestamo->estado_prestamo == 'activo')
                            <span class="status-active"><i class="fas fa-circle"></i> Activo</span>
                        @else
                            <span class="status-completed"><i class="fas fa-check-circle"></i> Completado</span>
                        @endif
                    </td>
                    <td class="text-danger">${{ number_format($prestamo->multa_total, 2) }}</td>
                    <td>
                        @if($prestamo->estado_prestamo == 'activo')
                            <button class="btn-return" onclick="confirmDevolucion({{ $prestamo->id }})">
                                <i class="fas fa-undo-alt"></i> Devolver
                            </button>
                            <form action="{{ route('prestamos.devolucion', $prestamo) }}" method="POST" style="display: none;" id="form-devolucion-{{ $prestamo->id }}">
                                @csrf
                            </form>
                        @endif
                        <button class="btn-view" onclick="verDetalle({{ $prestamo->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $prestamos->links() }}
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
.btn-primary {
    background: #667eea;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    text-decoration: none;
}
.alert-success-custom {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    border-left: 4px solid #4caf50;
    animation: slideIn 0.3s ease;
}
.alert-error-custom {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    border-left: 4px solid #f44336;
    animation: slideIn 0.3s ease;
}
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
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
.stat-card.blue { border-top: 4px solid #2196f3; }
.stat-card.green { border-top: 4px solid #4caf50; }
.stat-card.red { border-top: 4px solid #f44336; }
.stat-card i { font-size: 2rem; margin-bottom: 0.5rem; }
.stat-card .number { font-size: 2rem; font-weight: bold; }
.stat-card .label { color: #666; }
.data-table {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}
th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
tr:hover {
    background: #f5f5f5;
}
.badge {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    background: #e0e0e0;
    border-radius: 3px;
    font-size: 0.8rem;
    margin: 0.2rem;
}
.badge-danger {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    background: #f44336;
    color: white;
    border-radius: 3px;
    font-size: 0.7rem;
    margin-left: 0.5rem;
}
.status-active {
    color: #4caf50;
    font-weight: bold;
}
.status-completed {
    color: #9e9e9e;
}
.text-danger {
    color: #f44336;
    font-weight: bold;
}
.btn-return {
    background: #2196f3;
    color: white;
    border: none;
    padding: 0.3rem 0.8rem;
    border-radius: 3px;
    cursor: pointer;
}
.btn-view {
    background: #9e9e9e;
    color: white;
    border: none;
    padding: 0.3rem 0.8rem;
    border-radius: 3px;
    cursor: pointer;
}
.pagination {
    padding: 1rem;
    text-align: center;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDevolucion(id) {
    Swal.fire({
        title: '¿Registrar devolución?',
        text: "Confirma que el cliente ha devuelto las películas",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2196f3',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, registrar devolución',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`form-devolucion-${id}`).submit();
        }
    });
}

function verDetalle(id) {
    Swal.fire({
        title: 'Detalle del Préstamo',
        text: `Préstamo #${id}`,
        icon: 'info',
        confirmButtonColor: '#667eea',
        confirmButtonText: 'Cerrar'
    });
}

setTimeout(function() {
    let alerts = document.querySelectorAll('.alert-success-custom, .alert-error-custom');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
    });
}, 3000);
</script>
@endsection