<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Colaborador extends Authenticatable
{
    use HasFactory, Notifiable;

   protected $table = 'colaboradores';
    protected $fillable = [
        'usuario', 'departamento_id', 'nombre', 'apellidos', 'puesto', 'anydesk_id'
    ];

public function inventarios()
    {
        return $this->hasMany(InventarioDispositivo::class, 'colaborador_id');
    }
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}