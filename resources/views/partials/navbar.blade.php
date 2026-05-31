<nav class="navbar-premium">
    <div class="navbar-container">
        
        <div class="navbar-brand-premium">
            <a href="{{ route('home') }}">
                <div class="brand-icon-wrapper">
                    <i class="fas fa-film"></i>
                </div>
                <span class="brand-text">MovieSpace</span>
            </a>
        </div>
        
        <div class="navbar-menu-premium">
            @auth
                @if(auth()->user()->rol === 'admin')
                    <a href="{{ route('admin.peliculas.index') }}" class="nav-link-premium {{ request()->routeIs('admin.peliculas.*') ? 'link-active' : '' }}">
                        <i class="fas fa-film"></i> Catálogo
                    </a>
                @else
                    <a href="{{ route('peliculas.index') }}" class="nav-link-premium {{ request()->routeIs('peliculas.*') ? 'link-active' : '' }}">
                        <i class="fas fa-film"></i> Catálogo
                    </a>
                @endif
            @else
                <a href="{{ route('peliculas.index') }}" class="nav-link-premium">
                    <i class="fas fa-film"></i> Catálogo
                </a>
            @endauth
            
            @auth
                @if(auth()->user()->rol !== 'cliente')
                    <a href="{{ route('prestamos.index') }}" class="nav-link-premium {{ request()->routeIs('prestamos.*') ? 'link-active' : '' }}">
                        <i class="fas fa-exchange-alt"></i> Préstamos
                    </a>
                    <a href="{{ route('pagos.index') }}" class="nav-link-premium {{ request()->routeIs('pagos.*') ? 'link-active' : '' }}">
                        <i class="fas fa-cash-register"></i> Caja
                    </a>
                @endif
                
                @if(auth()->user()->rol === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-link-premium {{ request()->routeIs('admin.dashboard') ? 'link-active' : '' }}">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.usuarios.index') }}" class="nav-link-premium {{ request()->routeIs('admin.usuarios.*') ? 'link-active' : '' }}">
                        <i class="fas fa-users"></i> Usuarios
                    </a>
                    <a href="{{ route('admin.reportes.index') }}" class="nav-link-premium {{ request()->routeIs('admin.reportes.*') ? 'link-active' : '' }}">
                        <i class="fas fa-chart-line"></i> Reportes
                    </a>
                @endif
            @endauth
        </div>
        
        <div class="navbar-user-premium">
            @auth
                <div class="user-dropdown-premium">
                    <button class="user-btn-premium">
                        <div class="user-avatar-placeholder">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <span class="user-name-text">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down chevron-indicator"></i>
                    </button>
                    
                    <div class="dropdown-menu-premium">
                        <a href="{{ route('perfil') }}">
                            <i class="fas fa-id-card"></i> Mi Perfil
                        </a>
                        @if(auth()->user()->rol === 'cliente')
                            <a href="{{ route('mis-prestamos') }}">
                                <i class="fas fa-history"></i> Mis Préstamos
                            </a>
                        @endif
                        <div class="dropdown-premium-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-logout-premium">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-premium-login">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="btn-premium-register">Registrarse</a>
            @endauth
        </div>
    </div>
</nav>

<style>
    .navbar-premium {
        background-color: rgba(26, 29, 36, 0.96); /* Gris ebanizado translúcido */
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        position: sticky;
        top: 0;
        z-index: 2000;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .navbar-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0.85rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-sizing: border-box;
    }

    /* Logotipo Estilizado */
    .navbar-brand-premium a {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .brand-icon-wrapper {
        width: 36px;
        height: 36px;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(255, 65, 108, 0.3);
    }

    .brand-icon-wrapper i {
        color: #ffffff;
        font-size: 1.1rem;
    }

    .brand-text {
        color: #ffffff;
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    /* Menú de Enlaces */
    .navbar-menu-premium {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .nav-link-premium {
        color: #b3b3b3;
        text-decoration: none;
        font-size: 0.92rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .nav-link-premium i {
        color: #6c757d;
        font-size: 0.95rem;
        transition: color 0.2s;
    }

    .nav-link-premium:hover {
        color: #ffffff;
        background-color: rgba(255, 255, 255, 0.02);
    }

    .nav-link-premium:hover i {
        color: #ff4b2b;
    }

    /* Enlace de Ruta Activa */
    .link-active {
        color: #ffffff !important;
        background-color: rgba(255, 65, 108, 0.06) !important;
        border: 1px solid rgba(255, 65, 108, 0.15);
    }
    
    .link-active i {
        color: #ff4b2b !important;
    }

    /* Botones de Autenticación Invitado */
    .navbar-user-premium {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .btn-premium-login {
        color: #cdcdcd;
        text-decoration: none;
        font-size: 0.92rem;
        font-weight: 600;
        transition: color 0.2s;
    }

    .btn-premium-login:hover {
        color: #ffffff;
    }

    .btn-premium-register {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff;
        text-decoration: none;
        font-size: 0.92rem;
        font-weight: 600;
        padding: 9px 18px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(255, 65, 108, 0.2);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-premium-register:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(255, 65, 108, 0.3);
    }

    /* Dropdown de Sesión Activa */
    .user-dropdown-premium {
        position: relative;
    }

    .user-btn-premium {
        background-color: #111317;
        border: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 30px;
        color: #ffffff;
        cursor: pointer;
        padding: 6px 16px 6px 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.2s;
    }

    .user-btn-premium:hover {
        background-color: #16191f;
    }

    .user-avatar-placeholder {
        font-size: 1.25rem;
        color: #ff4b2b;
        display: flex;
        align-items: center;
    }

    .user-name-text {
        font-weight: 600;
        font-size: 0.88rem;
        max-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chevron-indicator {
        font-size: 0.72rem;
        color: #6c757d;
        transition: transform 0.2s;
    }

    /* Caja Desplegable Oscura */
    .dropdown-menu-premium {
        position: absolute;
        right: 0;
        top: calc(100% + 8px);
        background-color: #1a1d24;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.04);
        min-width: 200px;
        display: none;
        overflow: hidden;
        z-index: 2500;
        animation: dropdownPulse 0.2s ease;
    }

    @keyframes dropdownPulse {
        from { transform: translateY(-5px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .user-dropdown-premium:hover .dropdown-menu-premium {
        display: block;
    }

    .user-dropdown-premium:hover .chevron-indicator {
        transform: rotate(180deg);
    }

    .dropdown-menu-premium a, 
    .dropdown-logout-premium {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 16px;
        color: #cdcdcd;
        text-decoration: none;
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 0.88rem;
        font-weight: 600;
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    .dropdown-menu-premium a i {
        color: #6c757d;
        font-size: 0.95rem;
    }

    .dropdown-menu-premium a:hover {
        background-color: rgba(255, 255, 255, 0.02);
        color: #ffffff;
    }

    .dropdown-menu-premium a:hover i {
        color: #ff4b2b;
    }

    .dropdown-premium-divider {
        height: 1px;
        background-color: rgba(255, 255, 255, 0.04);
        margin: 4px 0;
    }

    .dropdown-logout-premium {
        color: #ef5350;
    }

    .dropdown-logout-premium:hover {
        background-color: rgba(239, 83, 80, 0.06);
        color: #f44336;
    }

    /* Consultas de Adaptación Responsiva */
    @media (max-width: 992px) {
        .navbar-container {
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
        }
        .navbar-menu-premium {
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .navbar-user-premium {
            width: 100%;
            justify-content: center;
        }
    }
</style>