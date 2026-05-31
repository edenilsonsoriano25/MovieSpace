@extends('layouts.app')

@section('title', 'Registrarse')

@section('content')
<div class="container">
    <div class="form-container" style="max-width: 500px;">
        <h2 style="text-align: center; margin-bottom: 2rem;">
            <i class="fas fa-user-plus"></i> Registro de Cliente
        </h2>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Nombre Completo</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name')
                    <div style="color: red; font-size: 0.8rem;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                @error('email')
                    <div style="color: red; font-size: 0.8rem;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required>
                @error('password')
                    <div style="color: red; font-size: 0.8rem;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>
            
            <div class="form-group">
                <label for="dui">DUI (Opcional)</label>
                <input type="text" name="dui" id="dui" value="{{ old('dui') }}" placeholder="XXXXXXXX-X">
            </div>
            
            <div class="form-group">
                <label for="telefono">Teléfono (Opcional)</label>
                <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}">
            </div>
            
            <div class="form-group">
                <label for="direccion">Dirección (Opcional)</label>
                <textarea name="direccion" id="direccion" rows="2">{{ old('direccion') }}</textarea>
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-user-plus"></i> Registrarse
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 1rem;">
            <p>¿Ya tienes cuenta? <a href="{{ route('login') }}" style="color: #667eea;">Inicia Sesión</a></p>
        </div>
    </div>
</div>
@endsection