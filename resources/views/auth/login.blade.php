@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="auth-dark-wrapper">
    <div class="auth-card-premium">
        
        <div class="auth-card-header">
            <div class="auth-icon-badge">
                <i class="fas fa-user-lock"></i>
            </div>
            <h2>Bienvenido de nuevo</h2>
            <p>Ingresa tus credenciales para acceder a MovieSpace</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf
            
            <div class="auth-input-group">
                <label for="email"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="ejemplo@correo.com" required autofocus>
                @error('email')
                    <div class="auth-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            
            <div class="auth-input-group">
                <label for="password"><i class="fas fa-key"></i> Contraseña</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </div>
                @error('password')
                    <div class="auth-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            
            <div class="auth-extra-options">
                <label class="remember-me-checkbox">
                    <input type="checkbox" name="remember">
                    <span class="checkbox-custom"></span>
                    Recordarme en este equipo
                </label>
            </div>
            
            <button type="submit" class="btn-auth-submit">
                <span>Ingresar al Sistema</span> <i class="fas fa-arrow-right"></i>
            </button>
        </form>
        
        <div class="auth-card-footer">
            <p>¿Eres un cliente nuevo? <a href="{{ route('register') }}">Crea una cuenta aquí</a></p>
        </div>
    </div>
</div>

<style>
    .auth-dark-wrapper {
        background-color: #0f1115;
        min-height: calc(100vh - 140px); /* Ajusta dinámicamente según el navbar y footer */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .auth-card-premium {
        background-color: #1a1d24;
        width: 100%;
        max-width: 420px;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.03);
        transition: transform 0.3s ease;
    }

    .auth-card-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .auth-icon-badge {
        width: 60px;
        height: 60px;
        background: linear-gradient(45deg, rgba(255, 65, 108, 0.1), rgba(255, 75, 43, 0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
        border: 1px solid rgba(255, 75, 43, 0.2);
    }

    .auth-icon-badge i {
        font-size: 1.5rem;
        color: #ff4b2b;
    }

    .auth-card-header h2 {
        color: #ffffff;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .auth-card-header p {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }

    .auth-form {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .auth-input-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .auth-input-group label {
        color: #cdcdcd;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .auth-input-group label i {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .auth-input-group input {
        width: 100%;
        padding: 12px 16px;
        background-color: #111317;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        color: #ffffff;
        font-size: 0.95rem;
        outline: none;
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    .auth-input-group input:focus {
        border-color: #ff4b2b;
        background-color: #14171c;
        box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.15);
    }

    /* Mensajes de Error de Validación */
    .auth-error-message {
        color: #ff5252;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 0.1rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Checkbox personalizado */
    .auth-extra-options {
        margin: 0.2rem 0;
    }

    .remember-me-checkbox {
        color: #9a9a9a;
        font-size: 0.88rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        user-select: none;
    }

    .remember-me-checkbox input {
        display: none;
    }

    .checkbox-custom {
        width: 18px;
        height: 18px;
        background-color: #111317;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        position: relative;
        transition: all 0.2s;
    }

    .remember-me-checkbox input:checked + .checkbox-custom {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        border-color: transparent;
    }

    .remember-me-checkbox input:checked + .checkbox-custom::after {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        font-size: 0.7rem;
        color: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Botón de Envío Gradiente */
    .btn-auth-submit {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff;
        border: none;
        padding: 14px;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
        transition: all 0.2s ease;
        margin-top: 0.5rem;
    }

    .btn-auth-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.4);
    }

    .btn-auth-submit:active {
        transform: translateY(0);
    }

    /* Footer de la tarjeta */
    .auth-card-footer {
        text-align: center;
        margin-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 1.5rem;
    }

    .auth-card-footer p {
        color: #8a8a8a;
        font-size: 0.9rem;
        margin: 0;
    }

    .auth-card-footer a {
        color: #ff4b2b;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    .auth-card-footer a:hover {
        color: #ff6b6b;
        text-decoration: underline;
    }
</style>
@endsection