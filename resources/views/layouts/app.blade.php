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
    <div class="app">
        <!-- BARRA DE NAVEGACIÓN PRINCIPAL -->
        <nav class="navbar">
            <div class="container">
                <div class="navbar-brand">
                    @auth
                        @if(auth()->user()->rol === 'admin')
                            <a href="{{ route('admin.dashboard') }}" style="color: #ffd700; text-decoration: none;">
                                <i class="fas fa-film"></i>
                                <span>MovieSpace</span>
                            </a>
                        @else
                            <a href="{{ route('home') }}" style="color: #ffd700; text-decoration: none;">
                                <i class="fas fa-film"></i>
                                <span>MovieSpace</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('home') }}" style="color: #ffd700; text-decoration: none;">
                            <i class="fas fa-film"></i>
                            <span>MovieSpace</span>
                        </a>
                    @endauth
                </div>
                
                <div class="navbar-menu">
                    @auth
                        @if(auth()->user()->rol === 'admin')
                            <!-- Admin: SIN Dashboard en el menú, solo el logo lleva al dashboard -->
                            <a href="{{ route('admin.peliculas.index') }}" class="nav-link">
                                <i class="fas fa-movie"></i> Catálogo
                            </a>
                            <a href="{{ route('prestamos.index') }}" class="nav-link">
                                <i class="fas fa-exchange-alt"></i> Préstamos
                            </a>
                            <a href="{{ route('pagos.index') }}" class="nav-link">
                                <i class="fas fa-cash-register"></i> Caja
                            </a>
                            <a href="{{ route('admin.usuarios.index') }}" class="nav-link">
                                <i class="fas fa-users"></i> Usuarios
                            </a>
                            <a href="{{ route('admin.reportes.index') }}" class="nav-link">
                                <i class="fas fa-chart-line"></i> Reportes
                            </a>
                        @elseif(auth()->user()->rol === 'trabajador')
                            <a href="{{ route('peliculas.index') }}" class="nav-link">
                                <i class="fas fa-movie"></i> Catálogo
                            </a>
                            <a href="{{ route('prestamos.index') }}" class="nav-link">
                                <i class="fas fa-exchange-alt"></i> Préstamos
                            </a>
                            <a href="{{ route('pagos.index') }}" class="nav-link">
                                <i class="fas fa-cash-register"></i> Caja
                            </a>
                        @else
                            <a href="{{ route('peliculas.index') }}" class="nav-link">
                                <i class="fas fa-movie"></i> Catálogo
                            </a>
                        @endif
                    @else
                        <a href="{{ route('peliculas.index') }}" class="nav-link">
                            <i class="fas fa-movie"></i> Catálogo
                        </a>
                    @endauth
                </div>
                
                <div class="navbar-user">
                    @auth
                        <div class="user-dropdown">
                            <button class="user-btn">
                                <i class="fas fa-user-circle"></i>
                                {{ auth()->user()->name }}
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('perfil') }}">
                                    <i class="fas fa-id-card"></i> Mi Perfil
                                </a>
                                @if(auth()->user()->rol === 'cliente')
                                    <a href="{{ route('mis-prestamos') }}">
                                        <i class="fas fa-history"></i> Mis Préstamos
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="dropdown-logout">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
                    @endauth
                </div>
            </div>
        </nav>
        
        <!-- Alertas -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif
        
        <!-- Contenido principal -->
        <main class="main-content">
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <p>&copy; 2024 MovieSpace - Sistema de Alquiler de Películas</p>
            </div>
        </footer>
    </div>
    
    <style>
        .navbar {
            background: rgba(0, 0, 0, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .navbar-brand a {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffd700;
            text-decoration: none;
        }
        .navbar-brand i {
            color: #ff6b6b;
            margin-right: 10px;
        }
        .navbar-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .nav-link {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
            padding: 0.5rem;
        }
        .nav-link:hover {
            color: #ffd700;
        }
        .nav-link i {
            margin-right: 5px;
        }
        .btn-login, .btn-register {
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-login {
            color: white;
        }
        .btn-login:hover {
            color: #ffd700;
        }
        .btn-register {
            background: #ff6b6b;
            color: white;
            margin-left: 10px;
        }
        .btn-register:hover {
            background: #ff5252;
        }
        .user-dropdown {
            position: relative;
        }
        .user-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            min-width: 200px;
            display: none;
            z-index: 1000;
        }
        .user-dropdown:hover .dropdown-menu {
            display: block;
        }
        .dropdown-menu a, .dropdown-logout {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .dropdown-menu a:hover, .dropdown-logout:hover {
            background: #f0f0f0;
        }
        .dropdown-logout {
            color: #ff6b6b;
        }
        .alert {
            position: fixed;
            top: 80px;
            right: 20px;
            padding: 1rem;
            border-radius: 5px;
            color: white;
            z-index: 1100;
            animation: slideIn 0.3s ease-out;
        }
        .alert-success {
            background: #4caf50;
        }
        .alert-error {
            background: #f44336;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .main-content {
            min-height: calc(100vh - 140px);
            padding: 2rem 0;
        }
        .footer {
            background: rgba(0, 0, 0, 0.9);
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 2rem;
        }
        @media (max-width: 768px) {
            .navbar .container {
                flex-direction: column;
                gap: 1rem;
            }
            .navbar-menu {
                justify-content: center;
                gap: 1rem;
            }
        }
    </style>
    
    @stack('scripts')
</body>
</html>