<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licencia extends Model
{
    use HasFactory;

    protected $table = 'licencias';
    
    protected $fillable = [
        'colaborador_id',
        'cuenta',
        'contrasena',
        'plataforma_id',
        'expiracion'
    ];

    protected $hidden = [
        'contrasena'
    ];

    protected $casts = [
        'expiracion' => 'date'
    ];

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }

    public function plataforma()
    {
        return $this->belongsTo(Plataforma::class, 'plataforma_id');
    }
}