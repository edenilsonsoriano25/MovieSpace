@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="container">
    <div class="form-container" style="max-width: 400px;">
        <h2 style="text-align: center; margin-bottom: 2rem;">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
        </h2>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div style="color: red; font-size: 0.8rem; margin-top: 0.2rem;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required>
                @error('password')
                    <div style="color: red; font-size: 0.8rem; margin-top: 0.2rem;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember"> Recordarme
                </label>
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 1rem;">
            <p>¿No tienes cuenta? <a href="{{ route('register') }}" style="color: #667eea;">Regístrate aquí</a></p>
        </div>
    </div>
</div>
@endsection