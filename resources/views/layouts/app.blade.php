<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MovieSpace - @yield('title', 'Sistema de Alquiler de Películas')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="app-layout">
        
        <nav class="navbar-premium">
            <div class="navbar-container">
                
                <div class="navbar-brand">
                    @auth
                        @if(auth()->user()->rol === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                                <i class="fas fa-film"></i>
                                <span>MovieSpace</span>
                            </a>
                        @else
                            <a href="{{ route('home') }}" class="brand-link">
                                <i class="fas fa-film"></i>
                                <span>MovieSpace</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('home') }}" class="brand-link">
                            <i class="fas fa-film"></i>
                            <span>MovieSpace</span>
                        </a>
                    @endauth
                </div>
                
                <div class="navbar-menu">
                    @auth
                        @if(auth()->user()->rol === 'admin')
                            <a href="{{ route('admin.peliculas.index') }}" class="nav-link {{ request()->routeIs('admin.peliculas.*') ? 'active' : '' }}">
                                <i class="fas fa-clapperboard"></i> Catálogo
                            </a>
                            <a href="{{ route('prestamos.index') }}" class="nav-link {{ request()->routeIs('prestamos.*') ? 'active' : '' }}">
                                <i class="fas fa-exchange-alt"></i> Préstamos
                            </a>
                            <!-- 👈 NUEVO: Enlace a Solicitudes con badge -->
                            <a href="{{ route('solicitudes.pendientes') }}" class="nav-link {{ request()->routeIs('solicitudes.*') ? 'active' : '' }}">
                                <i class="fas fa-paper-plane"></i> Solicitudes
                                @php
                                    $pendientesCount = \App\Models\Prestamo::where('estado_prestamo', 'pendiente')->count();
                                @endphp
                                @if($pendientesCount > 0)
                                    <span class="badge-solicitudes">{{ $pendientesCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('pagos.index') }}" class="nav-link {{ request()->routeIs('pagos.*') ? 'active' : '' }}">
                                <i class="fas fa-cash-register"></i> Caja
                            </a>
                            <a href="{{ route('admin.usuarios.index') }}" class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i> Usuarios
                            </a>
                            <a href="{{ route('admin.reportes.index') }}" class="nav-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-line"></i> Reportes
                            </a>
                        @elseif(auth()->user()->rol === 'trabajador')
                            <a href="{{ route('peliculas.index') }}" class="nav-link {{ request()->routeIs('peliculas.*') ? 'active' : '' }}">
                                <i class="fas fa-clapperboard"></i> Catálogo
                            </a>
                            <a href="{{ route('prestamos.index') }}" class="nav-link {{ request()->routeIs('prestamos.*') ? 'active' : '' }}">
                                <i class="fas fa-exchange-alt"></i> Préstamos
                            </a>
                            <!-- 👈 NUEVO: Enlace a Solicitudes con badge para trabajador -->
                            <a href="{{ route('solicitudes.pendientes') }}" class="nav-link {{ request()->routeIs('solicitudes.*') ? 'active' : '' }}">
                                <i class="fas fa-paper-plane"></i> Solicitudes
                                @php
                                    $pendientesCount = \App\Models\Prestamo::where('estado_prestamo', 'pendiente')->count();
                                @endphp
                                @if($pendientesCount > 0)
                                    <span class="badge-solicitudes">{{ $pendientesCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('pagos.index') }}" class="nav-link {{ request()->routeIs('pagos.*') ? 'active' : '' }}">
                                <i class="fas fa-cash-register"></i> Caja
                            </a>
                        @else
                            <a href="{{ route('peliculas.index') }}" class="nav-link {{ request()->routeIs('peliculas.*') ? 'active' : '' }}">
                                <i class="fas fa-clapperboard"></i> Catálogo
                            </a>
                        @endif
                    @else
                        <a href="{{ route('peliculas.index') }}" class="nav-link {{ request()->routeIs('peliculas.*') ? 'active' : '' }}">
                            <i class="fas fa-clapperboard"></i> Catálogo
                        </a>
                    @endauth
                </div>
                
                <div class="navbar-user-section">
                    @auth
                        <div class="user-dropdown-wrapper">
                            <button class="user-dropdown-toggle" id="userMenuBtn">
                                <i class="fas fa-user-circle"></i>
                                <span class="user-name-text">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down arrow-icon"></i>
                            </button>
                            <div class="dropdown-premium-menu" id="userDropdownMenu">
                                <a href="{{ route('perfil') }}" class="dropdown-item">
                                    <i class="fas fa-id-card"></i> Mi Perfil
                                </a>
                                @if(auth()->user()->rol === 'cliente')
                                    <a href="{{ route('mis-prestamos') }}" class="dropdown-item">
                                        <i class="fas fa-history"></i> Mis Préstamos
                                    </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger-btn">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="auth-buttons-group">
                            <a href="{{ route('login') }}" class="btn-link-login">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="btn-premium-register">Registrarse</a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>
        
        <div class="toast-notifications-container">
            @if(session('success'))
                <div class="toast-alert alert-success-premium">
                    <div class="toast-icon-box"><i class="fas fa-check-circle"></i></div>
                    <div class="toast-content">{{ session('success') }}</div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="toast-alert alert-error-premium">
                    <div class="toast-icon-box"><i class="fas fa-exclamation-circle"></i></div>
                    <div class="toast-content">{{ session('error') }}</div>
                </div>
            @endif
        </div>
        
        <main class="main-viewport">
            @yield('content')
        </main>
        
        <footer class="footer-premium">
            <div class="footer-container">
                <p>&copy; {{ date('Y') }} <span class="highlight">MovieSpace</span> - Sistema de Alquiler de Películas. Sucursal Jayaque.</p>
            </div>
        </footer>
    </div>
    
    <style>
        :root {
            --bg-main: #0f1115;
            --bg-card: #1a1d24;
            --primary-gradient: linear-gradient(45deg, #ff416c, #ff4b2b);
            --text-main: #ffffff;
            --text-muted: #6c757d;
            --border-color: rgba(255, 255, 255, 0.05);
        }

        body {
            margin: 0;
            background-color: #0f1115;
            color: #ffffff;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .app-layout {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Estilo Streaming */
        .navbar-premium {
            background-color: rgba(15, 17, 21, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0.8rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Marca / Logo */
        .brand-link {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.6rem;
            text-decoration: none;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .brand-link i {
            background: var(--primary-gradient);
            -webkit-background-clip: initial;
            -webkit-text-fill-color: initial;
            color: #ff4b2b;
        }

        /* Enlaces del Menú */
        .navbar-menu {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-link {
            color: #b3b3b3;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
        }

        .nav-link:hover {
            color: var(--text-main);
            background-color: rgba(255, 255, 255, 0.03);
        }

        .nav-link.active {
            color: #ffffff;
            background-color: rgba(255, 75, 43, 0.15);
        }

        /* 👈 NUEVO: Estilos para el badge de solicitudes */
        .badge-solicitudes {
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            color: white;
            border-radius: 50px;
            padding: 2px 8px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-left: 5px;
            animation: pulse 1.5s infinite;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.9;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Área de Autenticación */
        .auth-buttons-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-link-login {
            color: #b3b3b3;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .btn-link-login:hover { color: #fff; }

        .btn-premium-register {
            background: var(--primary-gradient);
            color: #ffffff;
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 4px 15px rgba(255, 65, 108, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-premium-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 65, 108, 0.35);
        }

        /* Dropdown Control de Usuario */
        .user-dropdown-wrapper {
            position: relative;
        }

        .user-dropdown-toggle {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            color: #ffffff;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.2s;
        }

        .user-dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .user-dropdown-toggle i:first-child {
            color: #ff4b2b;
            font-size: 1.1rem;
        }

        .arrow-icon {
            font-size: 0.75rem;
            color: var(--text-muted);
            transition: transform 0.2s;
        }

        .dropdown-premium-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: #16191e;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            min-width: 210px;
            display: none;
            overflow: hidden;
            z-index: 1010;
            animation: dropdownFadeIn 0.2s ease-out;
        }

        @keyframes dropdownFadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            color: #d1d1d1;
            text-decoration: none;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
        }

        .dropdown-divider {
            height: 1px;
            background-color: var(--border-color);
            margin: 4px 0;
        }

        .text-danger-btn { color: #ff5252 !important; }
        .text-danger-btn:hover { background: rgba(255, 82, 82, 0.08) !important; }

        /* Sistema Inteligente de Toasts / Alertas */
        .toast-notifications-container {
            position: fixed;
            top: 90px;
            right: 25px;
            z-index: 1100;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border-radius: 10px;
            color: #fff;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
            font-weight: 600;
            font-size: 0.9rem;
            min-width: 300px;
            max-width: 450px;
            animation: toastSlideIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes toastSlideIn {
            from { transform: translateX(110%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .alert-success-premium { background: #0ca678; border-left: 5px solid #02b875; }
        .alert-error-premium { background: #f03e3e; border-left: 5px solid #ff1a1a; }
        .toast-icon-box { font-size: 1.2rem; }

        /* Contenedor Principal de Vistas */
        .main-viewport {
            flex-grow: 1;
            padding: 0 2rem;
            background-color: #0f1115;
        }
        
        @media (min-width: 1200px) {
            .main-viewport {
                padding: 0 4rem; 
            }
        }

        /* Footer */
        .footer-premium {
            background-color: #0b0c10;
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            text-align: center;
            padding: 1.5rem 0;
            font-size: 0.88rem;
            font-weight: 500;
        }

        .footer-premium .highlight { color: #ff4b2b; font-weight: 600; }

        /* Responsivo */
        @media (max-width: 992px) {
            .navbar-container { flex-direction: column; gap: 1.2rem; padding: 1.2rem 1rem; }
            .navbar-menu { justify-content: center; flex-wrap: wrap; gap: 0.8rem; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('userMenuBtn');
            const dropMenu = document.getElementById('userDropdownMenu');

            if(toggleBtn && dropMenu) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isVisible = dropMenu.style.display === 'block';
                    dropMenu.style.display = isVisible ? 'none' : 'block';
                });

                document.addEventListener('click', function() {
                    dropMenu.style.display = 'none';
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>