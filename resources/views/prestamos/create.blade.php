@extends('layouts.app')

@section('title', 'Nuevo Préstamo')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> Nuevo Préstamo</h1>
        <a href="{{ route('prestamos.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('prestamos.store') }}">
            @csrf
            
            <div class="form-section">
                <h3><i class="fas fa-user"></i> Datos del Cliente</h3>
                <div class="form-group">
                    <label for="id_usuario">Seleccionar Cliente</label>
                    <select name="id_usuario" id="id_usuario" required>
                        <option value="">-- Seleccione un cliente --</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->name }} - {{ $cliente->email }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-film"></i> Películas a Alquilar</h3>
                <div class="peliculas-grid">
                    @foreach($peliculas as $pelicula)
                    <div class="pelicula-checkbox">
                        <label>
                            <input type="checkbox" name="peliculas[]" value="{{ $pelicula->id }}">
                            <div class="pelicula-info">
                                <strong>{{ $pelicula->titulo }}</strong>
                                <span class="price">${{ number_format($pelicula->precio_alquiler, 2) }}</span>
                                <span class="stock">📀 {{ $pelicula->copias_en_estante }} disponibles</span>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
                @if($peliculas->isEmpty())
                    <div class="alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> No hay películas disponibles en stock.
                    </div>
                @endif
            </div>

            <div class="form-section">
                <h3><i class="fas fa-calendar"></i> Configuración del Préstamo</h3>
                <div class="form-group">
                    <label for="dias_prestamo">Días de Préstamo (1-7 días)</label>
                    <select name="dias_prestamo" id="dias_prestamo" required>
                        <option value="1">1 día</option>
                        <option value="2">2 días</option>
                        <option value="3">3 días</option>
                        <option value="4">4 días</option>
                        <option value="5">5 días</option>
                        <option value="6">6 días</option>
                        <option value="7">7 días</option>
                    </select>
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-money-bill"></i> Método de Pago</h3>
                <div class="form-group">
                    <label for="metodo_pago">Seleccionar Método</label>
                    <select name="metodo_pago" id="metodo_pago" required>
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="tarjeta">💳 Tarjeta</option>
                        <option value="transferencia">🏦 Transferencia Bancaria</option>
                    </select>
                </div>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-success">
                    <i class="fas fa-check-circle"></i> Registrar Préstamo
                </button>
                <a href="{{ route('prestamos.index') }}" class="btn-danger">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
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
.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    text-decoration: none;
}
.form-card {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #eee;
}
.form-section h3 {
    margin-bottom: 1rem;
    color: #333;
}
.form-section h3 i {
    color: #667eea;
    margin-right: 0.5rem;
}
.form-group {
    margin-bottom: 1rem;
}
.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
    color: #333;
}
.form-group select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}
.peliculas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
    max-height: 400px;
    overflow-y: auto;
    padding: 0.5rem;
}
.pelicula-checkbox {
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 0.5rem;
    transition: all 0.3s;
}
.pelicula-checkbox:hover {
    background: #f5f5f5;
    border-color: #667eea;
}
.pelicula-checkbox label {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
}
.pelicula-checkbox input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}
.pelicula-info {
    flex: 1;
}
.pelicula-info strong {
    display: block;
    font-size: 1rem;
    color: #333;
}
.price {
    display: inline-block;
    color: #4caf50;
    font-weight: bold;
    font-size: 0.9rem;
    margin-right: 1rem;
}
.stock {
    display: inline-block;
    color: #666;
    font-size: 0.8rem;
}
.alert-warning {
    background: #fff3cd;
    border: 1px solid #ffc107;
    color: #856404;
    padding: 1rem;
    border-radius: 5px;
    text-align: center;
}
.form-buttons {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}
.btn-success {
    background: #4caf50;
    color: white;
    padding: 0.5rem 1.5rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    text-decoration: none;
}
.btn-danger {
    background: #f44336;
    color: white;
    padding: 0.5rem 1.5rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    text-decoration: none;
    display: inline-block;
}
.btn-success:hover { background: #45a049; }
.btn-danger:hover { background: #da190b; }
</style>
@endsection