@extends('layouts.app')

@section('title', 'Mis Alquileres')

@section('content')
<div class="client-loans-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="client-page-header">
            <div class="header-left">
                <span class="header-badge"><i class="fas fa-user-shield"></i> Panel de Afiliado</span>
                <h1 class="client-main-title"><i class="fas fa-history"></i> Mis Solicitudes y Alquileres</h1>
                <p class="client-main-subtitle">Revisa el estado de tus solicitudes, películas apartadas y controla tus fechas límite de devolución.</p>
            </div>
            <a href="{{ route('peliculas.index') }}" class="btn-premium-back">
                <i class="fas fa-film"></i> Volver a la Cartelera
            </a>
        </div>

        <div class="premium-table-wrapper">
            <div class="table-premium-header">
                <h3><i class="fas fa-disc"></i> Registro de Solicitudes y Copias Físicas</h3>
            </div>
            
            <table class="premium-data-table">
                <thead>
                    <tr>
                        <th>Código Folio</th>
                        <th>Película</th>
                        <th>Fecha Solicitud</th>
                        <th>Fecha Límite</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Monto / Multas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestamos as $prestamo)
                    @php
                        $detalle = $prestamo->detalles->first();
                        $pelicula = $detalle ? $detalle->pelicula : null;
                    @endphp
                    <tr>
                        <td class="td-id">#{{ str_pad($prestamo->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="td-movies">
                            @if($pelicula)
                                <span class="badge-movie-pill"><i class="fas fa-ticket"></i> {{ $pelicula->titulo }}</span>
                                <br>
                                <small class="text-muted-email">${{ number_format($detalle->precio_alquiler_momento ?? 0, 2) }}/día</small>
                            @else
                                <span class="badge-movie-pill">Película no disponible</span>
                            @endif
                        </td>
                        <td class="td-date">{{ \Carbon\Carbon::parse($prestamo->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="td-date-limit">
                            @if($prestamo->estado_prestamo == 'pendiente')
                                <span class="text-muted">Pendiente de aprobación</span>
                            @else
                                {{ \Carbon\Carbon::parse($prestamo->fecha_limite)->format('d/m/Y') }}
                                @if(now()->gt($prestamo->fecha_limite) && $prestamo->estado_prestamo == 'activo')
                                    <span class="badge-danger-pill">Vencido</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($prestamo->estado_prestamo == 'pendiente')
                                <span class="status-pill status-pill-pending">
                                    <i class="fas fa-clock"></i> Pendiente de aprobación
                                </span>
                            @elseif($prestamo->estado_prestamo == 'rechazado')
                                <span class="status-pill status-pill-rejected">
                                    <i class="fas fa-times-circle"></i> Rechazado
                                </span>
                            @elseif($prestamo->estado_prestamo == 'activo')
                                <span class="status-pill status-pill-active">
                                    <span class="pulse-dot"></span> En mi posesión
                                </span>
                            @else
                                <span class="status-pill status-pill-completed">
                                    <i class="fas fa-check-circle"></i> Devuelto a Tienda
                                </span>
                            @endif
                        </td>
                        <td class="td-fine {{ $prestamo->multa_total > 0 ? 'text-danger-fine' : 'text-fine-zero' }}" style="text-align: right;">
                            @if($prestamo->estado_prestamo == 'pendiente')
                                <span class="text-muted">Pendiente de pago</span>
                            @else
                                ${{ number_format($prestamo->multa_total, 2) }}
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="td-empty-state">
                            <div class="empty-state-box">
                                <i class="fas fa-coins"></i>
                                <p>Aún no has realizado ninguna solicitud de alquiler.</p>
                                <a href="{{ route('peliculas.index') }}" class="btn-explore-now">Explorar Cartelera</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if(method_exists($prestamos, 'hasPages') && $prestamos->hasPages())
                <div class="premium-pagination-box">
                    {{ $prestamos->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<style>
    .client-loans-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem;
        padding: 3rem 0 5rem 0;
        color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .client-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        gap: 1.5rem;
    }

    .header-badge {
        background-color: rgba(255, 65, 108, 0.08);
        color: #ff4b2b;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 0.5rem;
    }

    .client-main-title {
        font-size: 2.6rem;
        font-weight: 800;
        margin: 0 0 0.4rem 0;
        background: linear-gradient(45deg, #ffffff, #b3b3b3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .client-main-title i {
        color: #ff4b2b;
        -webkit-text-fill-color: initial;
        margin-right: 12px;
    }

    .client-main-subtitle {
        color: #6c757d;
        font-size: 0.98rem;
        margin: 0;
    }

    .btn-premium-back {
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
    }

    .btn-premium-back:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.4);
    }

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

    .premium-data-table { width: 100%; border-collapse: collapse; text-align: left; }
    
    .premium-data-table th {
        background: linear-gradient(135deg, #1f232b 0%, #14161c 100%);
        color: #ffffff; font-size: 0.88rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.6px; padding: 16px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
    }

    .premium-data-table td {
        padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,0.02);
        color: #b3b3b3; font-size: 0.95rem; vertical-align: middle;
        background-color: #1a1d24 !important;
    }

    .premium-data-table tbody tr:hover td {
        background-color: #222731 !important; color: #ffffff !important;
    }

    .td-id { font-family: monospace; color: #ff4b2b !important; font-weight: 600; }
    .td-date { color: #cdcdcd; font-family: monospace; }
    .td-date-limit { font-family: monospace; }

    .badge-danger-pill {
        background-color: rgba(244, 67, 54, 0.15); color: #ef5350;
        padding: 2px 6px; border-radius: 4px; font-size: 0.72rem;
        font-weight: 700; margin-left: 6px; text-transform: uppercase;
    }

    .badge-movie-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; background-color: #111317; color: #cdcdcd;
        border-radius: 6px; font-size: 0.8rem; margin: 2px;
        border: 1px solid rgba(255,255,255,0.03);
    }

    .status-pill {
        font-size: 0.76rem; font-weight: 700; padding: 5px 12px;
        border-radius: 6px; display: inline-flex; align-items: center;
        gap: 5px; text-transform: uppercase;
    }
    .status-pill-pending { background-color: rgba(255, 152, 0, 0.12); color: #ffb74d; }
    .status-pill-rejected { background-color: rgba(244, 67, 54, 0.12); color: #ef5350; }
    .status-pill-active { background-color: rgba(46, 196, 182, 0.12); color: #2ec4b6; }
    .status-pill-completed { background-color: rgba(108, 117, 125, 0.15); color: #8a8a8a; }

    .pulse-dot {
        width: 6px; height: 6px; background-color: #2ec4b6;
        border-radius: 50%; display: inline-block;
    }

    .text-danger-fine { color: #ef5350 !important; font-weight: 700; font-family: monospace; }
    .text-fine-zero { color: #495057; font-family: monospace; }
    .text-muted { color: #6c757d; }
    .text-muted-email { color: #6c757d; font-size: 0.75rem; }

    .td-empty-state { padding: 5rem 0 !important; text-align: center; }
    .empty-state-box { color: #6c757d; }
    .empty-state-box i { font-size: 3rem; margin-bottom: 1rem; color: #3a404a; }
    .empty-state-box p { font-size: 1.1rem; font-weight: 600; margin: 0 0 1.5rem 0; }
    
    .btn-explore-now {
        background-color: #111317; color: #ffffff !important;
        padding: 10px 20px; border-radius: 8px; text-decoration: none;
        font-weight: 600; font-size: 0.9rem; border: 1px solid rgba(255,255,255,0.05);
        transition: background-color 0.2s;
    }
    .btn-explore-now:hover { background-color: #222731; }

    .premium-pagination-box {
        padding: 1.5rem; border-top: 1px solid rgba(255, 255, 255, 0.04);
        display: flex !important; justify-content: center !important;
        align-items: center !important; background-color: #1a1d24 !important;
        width: 100% !important; box-sizing: border-box;
    }

    @media (max-width: 768px) {
        .client-page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .btn-premium-back { width: 100%; justify-content: center; }
    }
</style>
@endsection