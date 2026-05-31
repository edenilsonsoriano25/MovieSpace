@extends('layouts.app')

@section('title', 'Editar Película')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Editar Película</h1>
        <a href="{{ route('admin.peliculas.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.peliculas.update', $pelicula) }}">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label for="titulo">Título *</label>
                    <input type="text" name="titulo" id="titulo" required value="{{ old('titulo', $pelicula->titulo) }}">
                </div>
                
                <div class="form-group">
                    <label for="genero">Género *</label>
                    <select name="genero" id="genero" required>
                        <option value="">Seleccionar</option>
                        <option value="Acción" {{ $pelicula->genero == 'Acción' ? 'selected' : '' }}>Acción</option>
                        <option value="Comedia" {{ $pelicula->genero == 'Comedia' ? 'selected' : '' }}>Comedia</option>
                        <option value="Drama" {{ $pelicula->genero == 'Drama' ? 'selected' : '' }}>Drama</option>
                        <option value="Ciencia Ficción" {{ $pelicula->genero == 'Ciencia Ficción' ? 'selected' : '' }}>Ciencia Ficción</option>
                        <option value="Terror" {{ $pelicula->genero == 'Terror' ? 'selected' : '' }}>Terror</option>
                        <option value="Romance" {{ $pelicula->genero == 'Romance' ? 'selected' : '' }}>Romance</option>
                        <option value="Animación" {{ $pelicula->genero == 'Animación' ? 'selected' : '' }}>Animación</option>
                        <option value="Superhéroes" {{ $pelicula->genero == 'Superhéroes' ? 'selected' : '' }}>Superhéroes</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="director">Director *</label>
                    <input type="text" name="director" id="director" required value="{{ old('director', $pelicula->director) }}">
                </div>
                
                <div class="form-group">
                    <label for="año">Año *</label>
                    <input type="number" name="año" id="año" required min="1900" max="2026" value="{{ old('año', $pelicula->año) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="sinopsis">Sinopsis *</label>
                <textarea name="sinopsis" id="sinopsis" rows="4" required>{{ old('sinopsis', $pelicula->sinopsis) }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="precio_alquiler">Precio de Alquiler *</label>
                    <input type="number" step="0.01" name="precio_alquiler" id="precio_alquiler" required value="{{ old('precio_alquiler', $pelicula->precio_alquiler) }}">
                </div>
                
                <div class="form-group">
                    <label for="copias_totales">Copias Totales *</label>
                    <input type="number" name="copias_totales" id="copias_totales" required min="1" value="{{ old('copias_totales', $pelicula->copias_totales) }}">
                </div>
                
                <div class="form-group">
                    <label for="copias_en_estante">Copias Disponibles *</label>
                    <input type="number" name="copias_en_estante" id="copias_en_estante" required min="0" value="{{ old('copias_en_estante', $pelicula->copias_en_estante) }}">
                </div>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-update">
                    <i class="fas fa-save"></i> Actualizar Película
                </button>
                <a href="{{ route('admin.peliculas.index') }}" class="btn-cancel">
                    Cancelar
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
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
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
.form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 5px;
}
.form-buttons {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}
.btn-update {
    background: #ff9800;
    color: white;
    padding: 0.5rem 1.5rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.btn-cancel {
    background: #9e9e9e;
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 5px;
    text-decoration: none;
}
</style>
@endsection