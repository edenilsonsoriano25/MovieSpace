@extends('layouts.app')

@section('title', 'Dashboard Administrador')

@section('content')
<div class="dash-dark-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="dash-header">
            <h1 class="dash-title">
                <i class="fas fa-chart-line"></i> Panel de Administración
            </h1>
            <p class="dash-subtitle">Bienvenido al centro de control de MovieSpace. Monitorea transacciones, inventarios y operaciones en Jayaque.</p>
        </div>

        <div class="metrics-grid">
            
            <div class="metric-card card-revenue">
                <div class="metric-icon-box">
                    <i class="fas fa-cash-register"></i>
                </div>
                <div class="metric-data">
                    <span class="metric-label">Caja del Día</span>
                    <h2 class="metric-value">${{ number_format($cajaHoy ?? 0, 2) }}</h2>
                    <span class="metric-trend text-success"><i class="fas fa-arrow-up"></i> Flujo activo</span>
                </div>
            </div>

            <div class="metric-card card-rentals">
                <div class="metric-icon-box">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="metric-data">
                    <span class="metric-label">CDs en Custodia</span>
                    <h2 class="metric-value">{{ $prestamosActivos ?? 0 }}</h2>
                    <span class="metric-trend text-muted">Préstamos vigentes</span>
                </div>
            </div>

            <div class="metric-card card-inventory">
                <div class="metric-icon-box">
                    <i class="fas fa-film"></i>
                </div>
                <div class="metric-data">
                    <span class="metric-label">Títulos Registrados</span>
                    <h2 class="metric-value">{{ $totalPeliculas ?? 0 }}</h2>
                    <span class="metric-trend text-info">Copias en estante</span>
                </div>
            </div>

            <div class="metric-card card-alerts">
                <div class="metric-icon-box">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="metric-data">
                    <span class="metric-label">Devoluciones Retrasadas</span>
                    <h2 class="metric-value {{ ($devolucionesRetrasadas ?? 0) > 0 ? 'text-danger' : '' }}">
                        {{ $devolucionesRetrasadas ?? 0 }}
                    </h2>
                    <span class="metric-trend text-danger">⚠️ Requiere auditoría</span>
                </div>
            </div>

        </div>

        <h3 class="dash-section-divider"><i class="fas fa-th-large"></i> Accesos y Herramientas de Gestión</h3>

        <div class="dashboard-grid">
            
            <div class="dashboard-premium-card">
                <div class="card-glow-bg"></div>
                <div class="card-premium-content">
                    <div class="premium-card-icon"><i class="fas fa-clapperboard"></i></div>
                    <h3>Gestionar Catálogo</h3>
                    <p>Agrega nuevos ingresos de películas, actualiza precios y controla el stock físico.</p>
                    <a href="{{ route('admin.peliculas.index') }}" class="premium-card-btn">
                        <span>Configurar</span> <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="dashboard-premium-card">
                <div class="card-glow-bg"></div>
                <div class="card-premium-content">
                    <div class="premium-card-icon"><i class="fas fa-users"></i></div>
                    <h3>Gestionar Usuarios</h3>
                    <p>Administra las cuentas de accesos, roles y perfiles del personal técnico y clientes.</p>
                    <a href="{{ route('admin.usuarios.index') }}" class="premium-card-btn">
                        <span>Configurar</span> <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="dashboard-premium-card">
                <div class="card-glow-bg"></div>
                <div class="card-premium-content">
                    <div class="premium-card-icon"><i class="fas fa-chart-bar"></i></div>
                    <h3>Reportes y Métricas</h3>
                    <p>Genera archivos PDF de contabilidad, revisa ingresos históricos y estadísticas de arriendos.</p>
                    <a href="{{ route('admin.reportes.index') }}" class="premium-card-btn">
                        <span>Configurar</span> <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="dashboard-premium-card">
                <div class="card-glow-bg"></div>
                <div class="card-premium-content">
                    <div class="premium-card-icon"><i class="fas fa-exchange-alt"></i></div>
                    <h3>Control de Préstamos</h3>
                    <p>Monitorea todas las salidas de CDs, fechas de expiración y recepciones en mostrador.</p>
                    <a href="{{ route('prestamos.index') }}" class="premium-card-btn">
                        <span>Configurar</span> <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="dashboard-premium-card">
                <div class="card-glow-bg"></div>
                <div class="card-premium-content">
                    <div class="premium-card-icon"><i class="fas fa-cash-register"></i></div>
                    <h3>Auditoría de Caja</h3>
                    <p>Verifica transacciones del día, métodos de pago procesados y balances financieros.</p>
                    <a href="{{ route('pagos.index') }}" class="premium-card-btn">
                        <span>Configurar</span> <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>

<style>
    .dash-dark-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem;
        padding: 3rem 0 5rem 0;
        color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .dash-header {
        margin-bottom: 3rem;
    }

    .dash-title {
        font-size: 2.6rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .dash-title i {
        color: #ff4b2b;
        -webkit-text-fill-color: initial;
        margin-right: 12px;
    }

    .dash-subtitle {
        color: #6c757d;
        font-size: 1rem;
        margin: 0;
    }

    .metrics-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)) !important;
        gap: 1.5rem !important;
        margin-bottom: 4rem;
        width: 100%;
    }

    .metric-card {
        background-color: #1a1d24;
        border-radius: 14px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.02);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .metric-icon-box {
        width: 50px;
        height: 50px;
        background-color: #111317;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #ff4b2b;
    }

    .metric-data {
        display: flex;
        flex-direction: column;
    }

    .metric-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metric-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0.1rem 0;
    }

    .metric-trend {
        font-size: 0.78rem;
        font-weight: 600;
    }

    .text-success { color: #2ec4b6; }
    .text-info { color: #ff416c; }
    .text-muted { color: #495057; }
    .text-danger { color: #f03e3e !important; }

    .dash-section-divider {
        color: #ffffff;
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1.75rem;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        padding-bottom: 0.75rem;
    }

    .dash-section-divider i {
        color: #ff4b2b;
    }

    .dashboard-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
        gap: 2rem !important;
        width: 100%;
    }

    .dashboard-premium-card {
        background-color: #1a1d24;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.02);
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .dashboard-premium-card:hover {
        transform: translateY(-6px);
    }

    .card-glow-bg {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at top right, rgba(255, 75, 43, 0.15), transparent 60%);
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 1;
    }

    .dashboard-premium-card:hover .card-glow-bg {
        opacity: 1;
    }

    .card-premium-content {
        padding: 2rem;
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-sizing: border-box;
    }

    .premium-card-icon {
        font-size: 2.2rem;
        color: #ff4b2b;
        margin-bottom: 1.25rem;
        display: inline-block;
    }

    .card-premium-content h3 {
        color: #ffffff;
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0 0 0.6rem 0;
    }

    .card-premium-content p {
        color: #8a8a8a;
        font-size: 0.9rem;
        line-height: 1.5;
        margin: 0 0 1.75rem 0;
    }

    .premium-card-btn {
        margin-top: auto;
        background-color: #111317;
        color: #d1d1d1;
        border: 1px solid rgba(255,255,255,0.05);
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
    }

    .dashboard-premium-card:hover .premium-card-btn {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
    }

    .premium-card-btn i {
        font-size: 0.75rem;
        transition: transform 0.2s;
    }

    .dashboard-premium-card:hover .premium-card-btn i {
        transform: translateX(3px);
    }
</style>
@endsection