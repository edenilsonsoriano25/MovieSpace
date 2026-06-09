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
            <a href="{{ route('prestamos.index') }}" class="btn-premium-back">
                <i class="fas fa-arrow-left"></i> Ver Préstamos Activos
            </a>
        </div>

        @if(session('success'))
            <div class="toast-alert alert-success-premium">
                <div class="toast-icon-box"><i class="fas fa-check-circle"></i></div>
                <div class="toast-content">{{ session('success') }}</div>
            </div>
        @endif

        <div class="premium-table-wrapper">
            <table class="premium-data-table">
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
                    <tr>
                        <td class="td-id">#{{ str_pad($solicitud->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <strong>{{ $solicitud->usuario->name }}</strong><br>
                            <small>{{ $solicitud->usuario->email }}</small>
                        </td>
                        <td>
                            <strong>{{ $pelicula ? $pelicula->titulo : 'N/A' }}</strong><br>
                            <small class="text-muted">${{ number_format($detalle->precio_alquiler_momento ?? 0, 2) }}/día</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($solicitud->fecha_salida)->diffInDays($solicitud->fecha_limite) }} días</td>
                        <td>{{ \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y H:i') }}</td>
                        <td>
                            @php
                                $metodo = session('metodo_pago_' . $solicitud->id, 'efectivo');
                            @endphp
                            @if($metodo == 'efectivo') 💵 Efectivo
                            @elseif($metodo == 'tarjeta') 💳 Tarjeta
                            @else 🏦 Transferencia
                            @endif
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
                                    <i class="fas fa-inbox"></i>
                                    <p>No hay solicitudes pendientes de aprobación.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.btn-action-approve {
    background-color: rgba(46, 196, 182, 0.12);
    color: #2ec4b6;
    border: 1px solid rgba(46, 196, 182, 0.2);
}
.btn-action-approve:hover {
    background-color: #2ec4b6;
    color: white;
}
.btn-action-reject {
    background-color: rgba(244, 67, 54, 0.12);
    color: #ef5350;
    border: 1px solid rgba(244, 67, 54, 0.2);
}
.btn-action-reject:hover {
    background-color: #ef5350;
    color: white;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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