@extends('layouts.app')

@section('title', 'Dashboard Administrador')

@section('content')
<div class="container">
    <h1 style="color: white; margin-bottom: 2rem;">
        <i class="fas fa-chart-line"></i> Panel de Administración
    </h1>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <i class="fas fa-film"></i>
            <h3>Gestionar Catálogo</h3>
            <p>Agregar, editar o eliminar películas</p>
            <a href="{{ route('admin.peliculas.index') }}" class="card-btn">Ir →</a>
        </div>
        
        <div class="dashboard-card">
            <i class="fas fa-users"></i>
            <h3>Gestionar Usuarios</h3>
            <p>Administrar clientes y trabajadores</p>
            <a href="{{ route('admin.usuarios.index') }}" class="card-btn">Ir →</a>
        </div>
        
        <div class="dashboard-card">
            <i class="fas fa-chart-bar"></i>
            <h3>Reportes</h3>
            <p>Ver estadísticas y generar reportes</p>
            <a href="{{ route('admin.reportes.index') }}" class="card-btn">Ir →</a>
        </div>
        
        <div class="dashboard-card">
            <i class="fas fa-exchange-alt"></i>
            <h3>Préstamos</h3>
            <p>Gestionar préstamos activos</p>
            <a href="{{ route('prestamos.index') }}" class="card-btn">Ir →</a>
        </div>
        
        <div class="dashboard-card">
            <i class="fas fa-cash-register"></i>
            <h3>Caja</h3>
            <p>Ver transacciones y caja diaria</p>
            <a href="{{ route('pagos.index') }}" class="card-btn">Ir →</a>
        </div>
    </div>
</div>

<style>
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}
.dashboard-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}
.dashboard-card:hover {
    transform: translateY(-5px);
}
.dashboard-card i {
    font-size: 2.5rem;
    color: #667eea;
    margin-bottom: 1rem;
}
.dashboard-card h3 {
    margin-bottom: 0.5rem;
    color: #333;
}
.dashboard-card p {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}
.card-btn {
    display: inline-block;
    background: #667eea;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    text-decoration: none;
    transition: background 0.3s;
}
.card-btn:hover {
    background: #5a67d8;
}
</style>
@endsection