@extends('layouts.app')

@section('title', 'Mis Préstamos')

@section('content')
<div class="container">
    <h1 style="color: white; margin-bottom: 2rem;">
        <i class="fas fa-history"></i> Mis Préstamos
    </h1>
    
    @if($prestamos->isEmpty())
        <div style="background: white; border-radius: 10px; padding: 3rem; text-align: center;">
            <i class="fas fa-film" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
            <h3 style="color: #666;">No tienes préstamos registrados</h3>
            <p style="color: #999; margin-top: 0.5rem;">Alquila tu primera película en el catálogo</p>
            <a href="{{ route('peliculas.index') }}" style="display: inline-block; margin-top: 1rem; padding: 0.5rem 1.5rem; background: #667eea; color: white; text-decoration: none; border-radius: 5px;">
                Ver Catálogo
            </a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
            @foreach($prestamos as $prestamo)
            <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1rem; text-align: center;">
                    <i class="fas fa-ticket-alt" style="font-size: 2rem; color: white;"></i>
                </div>
                <div style="padding: 1rem;">
                    <h3 style="margin-bottom: 1rem; color: #333;">Préstamo #{{ $prestamo->id }}</h3>
                    
                    @if($prestamo->detalles->count() > 0)
                        @foreach($prestamo->detalles as $detalle)
                            <p style="margin: 0.5rem 0;">
                                <strong>🎬 Película:</strong> 
                                {{ $detalle->pelicula->titulo ?? 'Película no encontrada' }}
                            </p>
                            <p style="margin: 0.5rem 0;">
                                <strong>💰 Precio:</strong> 
                                ${{ number_format($detalle->precio_alquiler_momento, 2) }}
                            </p>
                        @endforeach
                    @else
                        <p>No hay detalles del préstamo</p>
                    @endif
                    
                    <p style="margin: 0.5rem 0;">
                        <strong>📅 Fecha salida:</strong> 
                        {{ \Carbon\Carbon::parse($prestamo->fecha_salida)->format('d/m/Y') }}
                    </p>
                    <p style="margin: 0.5rem 0;">
                        <strong>⏰ Fecha límite:</strong> 
                        {{ \Carbon\Carbon::parse($prestamo->fecha_limite)->format('d/m/Y') }}
                    </p>
                    
                    @if($prestamo->fecha_entrega_real)
                        <p style="margin: 0.5rem 0;">
                            <strong>✅ Fecha devolución:</strong> 
                            {{ \Carbon\Carbon::parse($prestamo->fecha_entrega_real)->format('d/m/Y') }}
                        </p>
                    @endif
                    
                    <div style="margin-top: 1rem;">
                        @if($prestamo->estado_prestamo == 'activo')
                            <span style="display: inline-block; padding: 0.3rem 0.8rem; background: #4caf50; color: white; border-radius: 5px;">
                                <i class="fas fa-check"></i> Activo
                            </span>
                            @if(\Carbon\Carbon::now()->gt($prestamo->fecha_limite))
                                <span style="display: inline-block; padding: 0.3rem 0.8rem; background: #f44336; color: white; border-radius: 5px; margin-left: 0.5rem;">
                                    ⚠️ Retrasado
                                </span>
                            @endif
                        @else
                            <span style="display: inline-block; padding: 0.3rem 0.8rem; background: #9e9e9e; color: white; border-radius: 5px;">
                                <i class="fas fa-check-double"></i> Completado
                            </span>
                        @endif
                    </div>
                    
                    @if($prestamo->multa_total > 0)
                        <p style="color: #f44336; font-weight: bold; margin-top: 1rem;">
                            💰 Multa: ${{ number_format($prestamo->multa_total, 2) }}
                        </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection