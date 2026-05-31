@extends('layouts.app')

@section('title', 'Confirmar Alquiler')

@section('content')
<div class="container">
    <div class="form-container" style="max-width: 500px; margin: 0 auto;">
        <h2 style="text-align: center; margin-bottom: 2rem;">
            <i class="fas fa-shopping-cart"></i> Confirmar Alquiler
        </h2>
        
        <div style="background: white; border-radius: 10px; overflow: hidden; margin-bottom: 2rem;">
            <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-video" style="font-size: 3rem; color: white;"></i>
            </div>
            <div style="padding: 1rem;">
                <h3 style="font-size: 1.2rem; font-weight: bold;">{{ $pelicula->titulo }}</h3>
                <div style="color: #666;">{{ $pelicula->genero }}</div>
                <div style="color: #ff6b6b; font-weight: bold; font-size: 1.2rem; margin: 0.5rem 0;">${{ number_format($pelicula->precio_alquiler, 2) }}</div>
                <div style="display: inline-block; padding: 0.2rem 0.5rem; border-radius: 3px; background: #4caf50; color: white;">
                    <i class="fas fa-check"></i> {{ $pelicula->copias_en_estante }} copias disponibles
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('prestamos.cliente.store') }}">
            @csrf
            <input type="hidden" name="pelicula_id" value="{{ $pelicula->id }}">
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Días de préstamo (1-7 días)</label>
                <select name="dias_prestamo" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="1">1 día - ${{ number_format($pelicula->precio_alquiler, 2) }}</option>
                    <option value="2">2 días - ${{ number_format($pelicula->precio_alquiler, 2) }}</option>
                    <option value="3">3 días - ${{ number_format($pelicula->precio_alquiler, 2) }}</option>
                    <option value="4">4 días - ${{ number_format($pelicula->precio_alquiler, 2) }}</option>
                    <option value="5">5 días - ${{ number_format($pelicula->precio_alquiler, 2) }}</option>
                    <option value="6">6 días - ${{ number_format($pelicula->precio_alquiler, 2) }}</option>
                    <option value="7">7 días - ${{ number_format($pelicula->precio_alquiler, 2) }}</option>
                </select>
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Método de pago</label>
                <select name="metodo_pago" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="transferencia">Transferencia</option>
                </select>
            </div>
            
            <button type="submit" style="width: 100%; padding: 0.8rem; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem;">
                <i class="fas fa-check-circle"></i> Confirmar Alquiler
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 1rem;">
            <a href="{{ route('peliculas.index') }}" style="color: #667eea;">← Cancelar y volver al catálogo</a>
        </div>
    </div>
</div>
@endsection