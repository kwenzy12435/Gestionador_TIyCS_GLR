<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UsuarioTI extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios_ti';
    
    protected $fillable = [
        'usuario',
        'nombres', 
        'apellidos',
        'puesto',
        'telefono',
        'rol',
        'contrasena' 
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];



    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    // ✅ Helper para roles
    public function isAdmin()
    {
        return $this->rol === 'ADMIN';
    }

    public function isAuxiliarTI()
    {
        return $this->rol === 'AUXILIAR-TI';
    }
}