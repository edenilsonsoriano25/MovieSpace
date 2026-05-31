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

// Ruta principal - Redirige según el rol
Route::get('/', function () {
    if (auth()->check() && auth()->user()->rol === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return app(PeliculaController::class)->index();
})->name('home');

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rutas públicas
Route::get('/peliculas', [PeliculaController::class, 'index'])->name('peliculas.index');
Route::get('/buscar-peliculas', [PeliculaController::class, 'search'])->name('peliculas.search');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('perfil.update');
    Route::get('/mis-prestamos', [PrestamoController::class, 'misPrestamos'])->name('mis-prestamos');
    Route::get('/alquilar/{pelicula}', [PrestamoController::class, 'alquilar'])->name('alquilar');
    Route::post('/prestamos/cliente/store', [PrestamoController::class, 'clienteStore'])->name('prestamos.cliente.store');
    Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');
    Route::get('/prestamos/create', [PrestamoController::class, 'create'])->name('prestamos.create');
    Route::post('/prestamos', [PrestamoController::class, 'store'])->name('prestamos.store');
    Route::post('/prestamos/{prestamo}/devolucion', [PrestamoController::class, 'devolucion'])->name('prestamos.devolucion');
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/caja', [PagoController::class, 'caja'])->name('caja.index');
    
    // Admin
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index');
    Route::post('/admin/usuarios', [UsuarioController::class, 'store'])->name('admin.usuarios.store');
    Route::delete('/admin/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy');
    Route::get('/admin/reportes', [ReporteController::class, 'index'])->name('admin.reportes.index');
    Route::get('/admin/peliculas', [PeliculaController::class, 'adminIndex'])->name('admin.peliculas.index');
    Route::get('/admin/peliculas/create', [PeliculaController::class, 'create'])->name('admin.peliculas.create');
    Route::post('/admin/peliculas', [PeliculaController::class, 'store'])->name('admin.peliculas.store');
    Route::get('/admin/peliculas/{pelicula}/edit', [PeliculaController::class, 'edit'])->name('admin.peliculas.edit');
    Route::put('/admin/peliculas/{pelicula}', [PeliculaController::class, 'update'])->name('admin.peliculas.update');
    Route::delete('/admin/peliculas/{pelicula}', [PeliculaController::class, 'destroy'])->name('admin.peliculas.destroy');
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});