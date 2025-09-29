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


    public function usuarioResponsable()
    {
        return $this->belongsTo(UsuarioTI::class, 'responsable');
    }

    // 
    public function scopeDelDia($query, $fecha = null)
    {
        $fecha = $fecha ?: now()->format('Y-m-d');
        return $query->where('fecha', $fecha);
    }

    public function scopeConProblemas($query)
    {
        return $query->where('porcentaje_experiencia_wifi', '<', 80)
                    ->orWhere('velocidad_descarga', '<', 10);
    }

    public function scopePorResponsable($query, $responsableId)
    {
        return $query->where('responsable', $responsableId);
    }

 
    public function getEstadoRedAttribute()
    {
        if ($this->porcentaje_experiencia_wifi < 60) {
            return 'Crítico';
        } elseif ($this->porcentaje_experiencia_wifi < 80) {
            return 'Regular';
        } else {
            return 'Óptimo';
        }
    }


    public function esConsistente()
    {
        return $this->velocidad_descarga >= 0 && 
               $this->velocidad_subida >= 0 && 
               $this->porcentaje_experiencia_wifi >= 0 && 
               $this->porcentaje_experiencia_wifi <= 100 &&
               $this->clientes_conectados >= 0;
    }
}