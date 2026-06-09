<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ClientePrestamoController;
use App\Http\Controllers\SolicitudController;

/*
|--------------------------------------------------------------------------
| 1. Rutas Públicas (Visitantes y Clientes sin loguear)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->rol === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->rol === 'trabajador') {
            return redirect()->route('prestamos.index');
        }
    }
    return app(PeliculaController::class)->index();
})->name('home');

// Autenticación Nativa
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro de Cuentas
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Consultas del Catálogo
Route::get('/peliculas', [PeliculaController::class, 'index'])->name('peliculas.index');
Route::get('/buscar-peliculas', [PeliculaController::class, 'search'])->name('peliculas.search');


/*
|--------------------------------------------------------------------------
| 2. Zona Exclusiva del Cliente (¡Priorizada arriba para evitar colisiones!)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Configuración de Perfil (General)
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('perfil.update');

    // Módulo del Cliente mapeado al ClientePrestamoController de forma independiente
    Route::get('/historial-alquileres', [ClientePrestamoController::class, 'misPrestamos'])->name('mis-prestamos');
    Route::get('/alquilar/{pelicula}', [ClientePrestamoController::class, 'alquilar'])->name('alquilar');
    Route::post('/prestamos/cliente/store', [ClientePrestamoController::class, 'clienteStore'])->name('prestamos.cliente.store');

    // 👈 NUEVO: Cliente puede enviar solicitud de alquiler
    Route::post('/solicitudes', [SolicitudController::class, 'store'])->name('solicitudes.store');
});


/*
|--------------------------------------------------------------------------
| 3. Rutas Operativas (Solo Trabajadores y Administradores)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Este filtro saca a los clientes si intentan usar el panel de control de mostrador
    Route::group(['middleware' => function ($request, $next) {
        if (auth()->user()->rol === 'cliente') {
            return redirect()->route('mis-prestamos')->with('error', 'No tienes permisos para acceder a las funciones de personal.');
        }
        return $next($request);
    }], function () {

        // Gestión de alquileres físicos en sucursal Jayaque
        Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');
        Route::get('/prestamos/create', [PrestamoController::class, 'create'])->name('prestamos.create');
        Route::post('/prestamos', [PrestamoController::class, 'store'])->name('prestamos.store');
        Route::post('/prestamos/{prestamo}/devolucion', [PrestamoController::class, 'devolucion'])->name('prestamos.devolucion');

        // Auditoría de Dinero y Finanzas
        Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
        Route::get('/caja', [PagoController::class, 'caja'])->name('caja.index');

        // 👈 NUEVO: Gestión de solicitudes (solo para trabajadores y admin)
        Route::get('/solicitudes/pendientes', [SolicitudController::class, 'pendientes'])->name('solicitudes.pendientes');
        Route::post('/solicitudes/{id}/aprobar', [SolicitudController::class, 'aprobar'])->name('solicitudes.aprobar');
        Route::post('/solicitudes/{id}/rechazar', [SolicitudController::class, 'rechazar'])->name('solicitudes.rechazar');
    });
});


/*
|--------------------------------------------------------------------------
| 4. Zona de Control del Administrador (Nivel Maestro)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::group([
        'middleware' => function ($request, $next) {
            if (auth()->user()->rol !== 'admin') {
                return redirect()->route('home')->with('error', 'Zona denegada. Se requieren credenciales de Administrador.');
            }
            return $next($request);
        },
        'prefix' => 'admin',
        'as' => 'admin.'
    ], function () {

        // Dashboard Gerencial
        Route::get('/dashboard', [ReporteController::class, 'dashboard'])->name('dashboard');

        // Control de Personal y Cuentas
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        
        // =============================================================
        // 👇 NUEVAS RUTAS PARA EDITAR USUARIOS (COPIAR ESTAS 2 LÍNEAS)
        // =============================================================
        Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        // =============================================================
        
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

        // Reportes Ejecutivos PDF
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');

        // CRUD del Catálogo Multimedia
        Route::get('/peliculas', [PeliculaController::class, 'adminIndex'])->name('peliculas.index');
        Route::get('/peliculas/create', [PeliculaController::class, 'create'])->name('peliculas.create');
        Route::post('/peliculas', [PeliculaController::class, 'store'])->name('peliculas.store');
        Route::get('/peliculas/{pelicula}/edit', [PeliculaController::class, 'edit'])->name('peliculas.edit');
        Route::put('/peliculas/{pelicula}', [PeliculaController::class, 'update'])->name('peliculas.update');
        Route::delete('/peliculas/{pelicula}', [PeliculaController::class, 'destroy'])->name('peliculas.destroy');
    });
});