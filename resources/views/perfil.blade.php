@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="profile-dark-wrapper">
    <div class="profile-card-premium">
        
        <div class="profile-card-header">
            <div class="profile-avatar-badge">
                <i class="fas fa-user"></i>
            </div>
            <h2>{{ auth()->user()->name }}</h2>
            
            <div class="role-badge-box">
                @if(auth()->user()->rol === 'admin')
                    <span class="badge-profile-role role-admin-gradient"><i class="fas fa-crown"></i> Administrador</span>
                @elseif(auth()->user()->rol === 'trabajador')
                    <span class="badge-profile-role role-worker-gradient"><i class="fas fa-user-tie"></i> Personal Técnico</span>
                @else
                    <span class="badge-profile-role role-client-gradient"><i class="fas fa-user-tag"></i> Cliente de Jayaque</span>
                @endif
            </div>
        </div>

        <div class="profile-card-body">
            
            <h4 class="profile-section-title"><i class="fas fa-shield-alt"></i> Seguridad y Cuenta</h4>
            <div class="profile-info-grid">
                <div class="profile-data-item">
                    <span class="profile-data-label"><i class="fas fa-id-badge"></i> Nombre Completo</span>
                    <span class="profile-data-value">{{ auth()->user()->name }}</span>
                </div>
                
                <div class="profile-data-item">
                    <span class="profile-data-label"><i class="fas fa-envelope"></i> Correo Electrónico</span>
                    <span class="profile-data-value">{{ auth()->user()->email }}</span>
                </div>
            </div>

            <div class="profile-divider"></div>

            <h4 class="profile-section-title"><i class="fas fa-address-card"></i> Información Personal</h4>
            <div class="profile-info-grid">
                
                <div class="profile-data-item">
                    <span class="profile-data-label"><i class="fas fa-id-card"></i> Documento Único de Identidad (DUI)</span>
                    <span class="profile-data-value">
                        {{ auth()->user()->dui ? auth()->user()->dui : 'No registrado' }}
                    </span>
                </div>

                <div class="profile-data-item">
                    <span class="profile-data-label"><i class="fas fa-phone-alt"></i> Número de Teléfono</span>
                    <span class="profile-data-value">
                        {{ auth()->user()->telefono ? auth()->user()->telefono : 'No registrado' }}
                    </span>
                </div>

            </div>

        </div>

        <div class="profile-card-footer">
            <a href="{{ route('peliculas.index') }}" class="btn-profile-back">
                <i class="fas fa-arrow-left"></i> Volver a la Cartelera
            </a>
        </div>

    </div>
</div>

<style>
    .profile-dark-wrapper {
        background-color: #0f1115;
        min-height: calc(100vh - 140px);
        margin-top: -2rem; /* Sincroniza con el padding de app.blade.php */
        padding: 4rem 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .profile-card-premium {
        background-color: #1a1d24;
        width: 100%;
        max-width: 600px;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    /* Avatar y Cabecera */
    .profile-card-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .profile-avatar-badge {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #232731, #0f1115);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem auto;
        border: 2px solid rgba(255, 75, 43, 0.3);
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }

    .profile-avatar-badge i {
        font-size: 2.5rem;
        color: #ff4b2b;
    }

    .profile-card-header h2 {
        color: #ffffff;
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.75rem 0;
    }

    /* Badges Estilizados de Roles */
    .role-badge-box {
        display: inline-block;
    }

    .badge-profile-role {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 30px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .role-admin-gradient {
        background: linear-gradient(45deg, #ffd700, #ffa500);
        color: #111317;
    }

    .role-worker-gradient {
        background: linear-gradient(45deg, #2ec4b6, #009688);
        color: #ffffff;
    }

    .role-client-gradient {
        background-color: #2a2e35;
        color: #d1d1d1;
        border: 1px solid rgba(255,255,255,0.05);
    }

    /* Organización de Bloques de Datos */
    .profile-card-body {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .profile-section-title {
        color: #ff4b2b;
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profile-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
    }

    .profile-data-item {
        background-color: #111317;
        padding: 1.25rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.02);
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .profile-data-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 600;
        text-uppercase: uppercase;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .profile-data-label i {
        color: #4a5262;
    }

    .profile-data-value {
        color: #ffffff;
        font-size: 1.05rem;
        font-weight: 600;
        word-break: break-all;
    }

    .profile-divider {
        height: 1px;
        background-color: rgba(255, 255, 255, 0.04);
        margin: 0.75rem 0;
    }

    /* Footer Enlace */
    .profile-card-footer {
        text-align: center;
        margin-top: 3rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 1.5rem;
    }

    .btn-profile-back {
        color: #6c757d;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-profile-back:hover {
        color: #ffffff;
    }
</style>
@endsection