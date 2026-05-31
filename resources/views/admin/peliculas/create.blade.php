@extends('layouts.app')

@section('title', 'Agregar Película')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> Agregar Nueva Película</h1>
        <a href="{{ route('admin.peliculas.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.peliculas.store') }}">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label for="titulo">Título *</label>
                    <input type="text" name="titulo" id="titulo" required value="{{ old('titulo') }}">
                </div>
                
                <div class="form-group">
                    <label for="genero">Género *</label>
                    <select name="genero" id="genero" required>
                        <option value="">Seleccionar</option>
                        <option value="Acción">Acción</option>
                        <option value="Comedia">Comedia</option>
                        <option value="Drama">Drama</option>
                        <option value="Ciencia Ficción">Ciencia Ficción</option>
                        <option value="Terror">Terror</option>
                        <option value="Romance">Romance</option>
                        <option value="Animación">Animación</option>
                        <option value="Superhéroes">Superhéroes</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="director">Director *</label>
                    <input type="text" name="director" id="director" required value="{{ old('director') }}">
                </div>
                
                <div class="form-group">
                    <label for="año">Año *</label>
                    <input type="number" name="año" id="año" required min="1900" max="2026" value="{{ old('año', date('Y')) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="sinopsis">Sinopsis *</label>
                <textarea name="sinopsis" id="sinopsis" rows="4" required>{{ old('sinopsis') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="precio_alquiler">Precio de Alquiler *</label>
                    <input type="number" step="0.01" name="precio_alquiler" id="precio_alquiler" required value="{{ old('precio_alquiler', 2.50) }}">
                </div>
                
                <div class="form-group">
                    <label for="copias_totales">Copias Totales *</label>
                    <input type="number" name="copias_totales" id="copias_totales" required min="1" value="{{ old('copias_totales', 5) }}">
                </div>
                
                <div class="form-group">
                    <label for="copias_en_estante">Copias Disponibles *</label>
                    <input type="number" name="copias_en_estante" id="copias_en_estante" required min="0" value="{{ old('copias_en_estante', 5) }}">
                </div>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Guardar Película
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
.btn-save {
    background: #4caf50;
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