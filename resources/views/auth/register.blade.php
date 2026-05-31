@extends('layouts.app')

@section('title', 'Registrarse')

@section('content')
<div class="auth-dark-wrapper">
    <div class="auth-card-premium" style="max-width: 520px;">
        
        <div class="auth-card-header">
            <div class="auth-icon-badge">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2>Crea tu Cuenta</h2>
            <p>Regístrate para explorar el catálogo y gestionar tus préstamos en MovieSpace</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf
            
            <div class="auth-input-group">
                <label for="name"><i class="fas fa-user"></i> Nombre Completo</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Tu nombre y apellido" required>
                @error('name')
                    <div class="auth-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            
            <div class="auth-input-group">
                <label for="email"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required>
                @error('email')
                    <div class="auth-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            
            <div class="auth-form-row">
                <div class="auth-input-group">
                    <label for="password"><i class="fas fa-key"></i> Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Mínimo 8 caracteres" required>
                    @error('password')
                        <div class="auth-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                
                <div class="auth-input-group">
                    <label for="password_confirmation"><i class="fas fa-lock"></i> Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repite tu contraseña" required>
                </div>
            </div>
            
            <div class="auth-form-row">
                <div class="auth-input-group">
                    <label for="dui"><i class="fas fa-id-card"></i> DUI <span class="optional-text">(Opcional)</span></label>
                    <input type="text" name="dui" id="dui" value="{{ old('dui') }}" placeholder="00000000-0">
                    @error('dui')
                        <div class="auth-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                
                <div class="auth-input-group">
                    <label for="telefono"><i class="fas fa-phone"></i> Teléfono <span class="optional-text">(Opcional)</span></label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" placeholder="7000-0000">
                    @error('telefono')
                        <div class="auth-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="auth-input-group">
                <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección de Residencia <span class="optional-text">(Opcional)</span></label>
                <textarea name="direccion" id="direccion" rows="2" placeholder="Tu dirección exacta de domicilio...">{{ old('direccion') }}</textarea>
                @error('direccion')
                    <div class="auth-error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn-auth-submit" style="margin-top: 1rem;">
                <span>Completar Registro</span> <i class="fas fa-user-check"></i>
            </button>
        </form>
        
        <div class="auth-card-footer">
            <p>¿Ya tienes una cuenta registrada? <a href="{{ route('login') }}">Inicia Sesión aquí</a></p>
        </div>
    </div>
</div>

<style>
    .auth-dark-wrapper {
        background-color: #0f1115;
        min-height: calc(100vh - 140px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .auth-card-premium {
        background-color: #1a1d24;
        width: 100%;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    .auth-card-header {
        text-align: center;
        margin-bottom: 2.2rem;
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
        font-size: 1.4rem;
        color: #ff4b2b;
    }

    .auth-card-header h2 {
        color: #ffffff;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .auth-card-header p {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.4;
    }

    .auth-form {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* Soporte para dos columnas alineadas */
    .auth-form-row {
        display: flex;
        gap: 1.25rem;
    }

    .auth-form-row .auth-input-group {
        flex: 1;
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
        width: 15px;
        text-align: center;
    }

    .optional-text {
        color: #555e6d;
        font-weight: 500;
        font-size: 0.78rem;
    }

    .auth-input-group input, 
    .auth-input-group textarea {
        width: 100%;
        padding: 12px 16px;
        background-color: #111317;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        color: #ffffff;
        font-size: 0.95rem;
        outline: none;
        box-sizing: border-box;
        font-family: inherit;
        transition: all 0.2s ease;
    }

    .auth-input-group textarea {
        resize: none;
    }

    .auth-input-group input:focus,
    .auth-input-group textarea:focus {
        border-color: #ff4b2b;
        background-color: #14171c;
        box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.15);
    }

    .auth-error-message {
        color: #ff5252;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 0.1rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Botón Principal */
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
    }

    .btn-auth-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.4);
    }

    /* Footer link */
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

    /* Ajuste para pantallas pequeñas */
    @media (max-width: 576px) {
        .auth-form-row {
            flex-direction: column;
            gap: 1.25rem;
        }
        .auth-card-premium {
            padding: 1.5rem;
        }
    }
</style>
@endsection