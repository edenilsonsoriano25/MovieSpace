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

/*
|--------------------------------------------------------------------------
| 1. Rutas Públicas (Acceso para Visitantes y Clientes)
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

// Autenticación de Usuarios
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro de Clientes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Catálogo y Buscador
Route::get('/peliculas', [PeliculaController::class, 'index'])->name('peliculas.index');
Route::get('/buscar-peliculas', [PeliculaController::class, 'search'])->name('peliculas.search');


/*
|--------------------------------------------------------------------------
| 2. Rutas Protegidas Generales (Cualquier usuario logueado)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Configuración de Perfil
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('perfil.update');

    // Rutas de visualización y apartado/alquiler del cliente (¡Restauradas!)
    Route::get('/mis-prestamos', [PrestamoController::class, 'misPrestamos'])->name('mis-prestamos');
    Route::get('/alquilar/{pelicula}', [PrestamoController::class, 'alquilar'])->name('alquilar');
    Route::post('/prestamos/cliente/store', [PrestamoController::class, 'clienteStore'])->name('prestamos.cliente.store');
});


/*
|--------------------------------------------------------------------------
| 3. Rutas Operativas (Bloqueado para Clientes - Solo Admin y Trabajador)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Este grupo valida el rol y saca a los clientes de las páginas de gestión
    Route::group(['middleware' => function ($request, $next) {
        if (auth()->user()->rol === 'cliente') {
            return redirect()->route('peliculas.index')->with('error', 'No tienes permisos operativos.');
        }
        return $next($request);
    }], function () {

        // Gestión de Alquileres y Devoluciones en Sucursal
        Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');
        Route::get('/prestamos/create', [PrestamoController::class, 'create'])->name('prestamos.create');
        Route::post('/prestamos', [PrestamoController::class, 'store'])->name('prestamos.store');
        Route::post('/prestamos/{prestamo}/devolucion', [PrestamoController::class, 'devolucion'])->name('prestamos.devolucion');

        // Control de Auditoría de Cajas y Pagos
        Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
        Route::get('/caja', [PagoController::class, 'caja'])->name('caja.index');
    });
});


/*
|--------------------------------------------------------------------------
| 4. Rutas Exclusivas del Administrador (Bloqueado para Clientes y Trabajadores)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Este grupo valida estrictamente que sea 'admin' para entrar
    Route::group([
        'middleware' => function ($request, $next) {
            if (auth()->user()->rol !== 'admin') {
                return redirect()->route('home')->with('error', 'Acceso denegado. Se requieren permisos de administrador.');
            }
            return $next($request);
        },
        'prefix' => 'admin',
        'as' => 'admin.'
    ], function () {

        // Dashboard Principal
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Módulo de Gestión de Usuarios y Personal
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

        // Reportes Financieros en PDF
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');

        // CRUD de Inventario del Catálogo de Películas
        Route::get('/peliculas', [PeliculaController::class, 'adminIndex'])->name('peliculas.index');
        Route::get('/peliculas/create', [PeliculaController::class, 'create'])->name('peliculas.create');
        Route::post('/peliculas', [PeliculaController::class, 'store'])->name('peliculas.store');
        Route::get('/peliculas/{pelicula}/edit', [PeliculaController::class, 'edit'])->name('peliculas.edit');
        Route::put('/peliculas/{pelicula}', [PeliculaController::class, 'update'])->name('peliculas.update');
        Route::delete('/peliculas/{pelicula}', [PeliculaController::class, 'destroy'])->name('peliculas.destroy');
    });
});
