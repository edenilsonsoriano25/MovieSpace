@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="admin-dark-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="admin-page-header">
            <div class="header-left">
                <h1 class="admin-main-title"><i class="fas fa-user-edit"></i> Editar Usuario</h1>
                <p class="admin-main-subtitle">Modifica la información del usuario, su rol y datos personales.</p>
            </div>
            <a href="{{ route('admin.usuarios.index') }}" class="btn-premium-back">
                <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
        </div>

        <div class="premium-form-card">
            <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST" class="premium-interactive-form">
                @csrf
                @method('PUT')
                
                <div class="form-premium-row">
                    <div class="form-premium-group">
                        <label for="name"><i class="fas fa-user"></i> Nombre Completo *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}" required>
                        @error('name') <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                    </div>
                    
                    <div class="form-premium-group">
                        <label for="email"><i class="fas fa-envelope"></i> Correo Electrónico *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" required>
                        @error('email') <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-premium-row">
                    <div class="form-premium-group">
                        <label for="dui"><i class="fas fa-id-card"></i> DUI</label>
                        <input type="text" name="dui" id="dui" value="{{ old('dui', $usuario->dui) }}" placeholder="00000000-0">
                        @error('dui') <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                    </div>
                    
                    <div class="form-premium-group">
                        <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $usuario->telefono) }}" placeholder="7000-0000">
                        @error('telefono') <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-premium-group">
                    <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $usuario->direccion) }}" placeholder="Dirección completa">
                    @error('direccion') <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                </div>

                <div class="form-premium-group">
                    <label for="rol"><i class="fas fa-user-tag"></i> Rol del Sistema *</label>
                    <div class="premium-select-wrapper">
                        <select name="rol" id="rol" required>
                            <option value="cliente" {{ old('rol', $usuario->rol) == 'cliente' ? 'selected' : '' }}>Cliente</option>
                            <option value="trabajador" {{ old('rol', $usuario->rol) == 'trabajador' ? 'selected' : '' }}>Trabajador</option>
                            <option value="admin" {{ old('rol', $usuario->rol) == 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>
                    @error('rol') <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                </div>

                <div class="form-premium-group">
                    <label for="password"><i class="fas fa-lock"></i> Nueva Contraseña (dejar en blanco para no cambiar)</label>
                    <input type="password" name="password" id="password" placeholder="Mínimo 8 caracteres">
                    @error('password') <div class="field-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                </div>

                <div class="form-premium-group">
                    <label for="password_confirmation"><i class="fas fa-lock"></i> Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repite la nueva contraseña">
                </div>

                <div class="form-premium-actions">
                    <a href="{{ route('admin.usuarios.index') }}" class="btn-form-cancel">Cancelar</a>
                    <button type="submit" class="btn-form-save">
                        <i class="fas fa-save"></i> Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<style>
    .admin-dark-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem;
        padding: 3rem 0 5rem 0;
        color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .admin-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        gap: 1.5rem;
    }

    .admin-main-title {
        font-size: 2.6rem;
        font-weight: 800;
        margin: 0 0 0.4rem 0;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .admin-main-title i {
        color: #ff4b2b;
        -webkit-text-fill-color: initial;
        margin-right: 12px;
    }

    .admin-main-subtitle {
        color: #6c757d;
        font-size: 0.98rem;
        margin: 0;
    }

    .btn-premium-back {
        background-color: #1a1d24;
        color: #b3b3b3 !important;
        border: 1px solid rgba(255,255,255,0.05);
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.88rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-premium-back:hover {
        background-color: #242933;
        color: #ffffff !important;
    }

    .premium-form-card {
        background-color: #1a1d24;
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.02);
    }

    .premium-interactive-form {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .form-premium-row {
        display: flex;
        gap: 1.5rem;
    }

    .form-premium-row .form-premium-group {
        flex: 1;
    }

    .form-premium-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-premium-group label {
        color: #cdcdcd;
        font-size: 0.88rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-premium-group label i {
        color: #6c757d;
        font-size: 0.9rem;
        width: 16px;
        text-align: center;
    }

    .form-premium-group input, 
    .form-premium-group select {
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

    .form-premium-group input:focus,
    .form-premium-group select:focus {
        border-color: #ff4b2b;
        background-color: #14171c;
        box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.15);
    }

    .premium-select-wrapper {
        position: relative;
        width: 100%;
    }

    .premium-select-wrapper select {
        appearance: none;
        -webkit-appearance: none;
        padding-right: 40px;
        cursor: pointer;
    }

    .premium-select-wrapper::after {
        content: '\f078';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        font-size: 0.72rem;
        color: #6c757d;
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .field-error-msg {
        color: #ef5350;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 0.1rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-premium-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1rem;
        border-top: 1px solid rgba(255,255,255,0.04);
        padding-top: 1.5rem;
    }

    .btn-form-cancel {
        background-color: #2a2e35;
        color: #b3b3b3 !important;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.92rem;
        transition: all 0.2s;
    }

    .btn-form-cancel:hover {
        background-color: #343a44;
        color: #ffffff !important;
    }

    .btn-form-save {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff;
        border: none;
        padding: 12px 26px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.92rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.2);
        transition: all 0.2s ease;
    }

    .btn-form-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(255, 65, 108, 0.3);
    }

    @media (max-width: 768px) {
        .admin-page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .form-premium-row { flex-direction: column; gap: 1rem; }
        .premium-form-card { padding: 1.5rem; }
    }
</style>
@endsection