<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'dui',
        'telefono',
        'direccion'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    // Verificar roles
    public function isAdmin()
    {
        return $this->rol === 'admin';
    }
    
    public function isTrabajador()
    {
        return $this->rol === 'trabajador';
    }
    
    public function isCliente()
    {
        return $this->rol === 'cliente';
    }
    
    // Relación con préstamos como cliente
    public function prestamosCliente()
    {
        return $this->hasMany(Prestamo::class, 'id_usuario');
    }
    
    // Relación con préstamos como trabajador
    public function prestamosTrabajador()
    {
        return $this->hasMany(Prestamo::class, 'id_trabajador');
    }
    
    // Relación con pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_usuario');
    }
    
    // Verificar si tiene préstamos activos
    public function tienePrestamoActivo()
    {
        return $this->prestamosCliente()
            ->where('estado_prestamo', 'activo')
            ->exists();
    }
}