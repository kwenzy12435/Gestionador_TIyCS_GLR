<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoreoRed extends Model
{
    use HasFactory;

    protected $table = 'monitoreo_red';

    protected $fillable = [
        'fecha',
        'hora',
        'velocidad_descarga',
        'velocidad_subida',
        'porcentaje_experiencia_wifi',
        'clientes_conectados',
        'observaciones',
        'responsable'
    ];

    protected $casts = [
        'fecha' => 'date',
        'velocidad_descarga' => 'decimal:2',
        'velocidad_subida' => 'decimal:2',
        'porcentaje_experiencia_wifi' => 'decimal:2',
        'clientes_conectados' => 'integer'
    ];

    // Relación con el usuario responsable
    public function usuarioResponsable()
    {
        return $this->belongsTo(UsuarioTi::class, 'responsable');
    }
}