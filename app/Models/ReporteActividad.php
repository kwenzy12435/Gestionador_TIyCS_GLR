<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteActividad extends Model
{
    use HasFactory;

    protected $table = 'reporte_actividades';
    
    protected $fillable = [
        'fecha',
        'colaborador_id',
        'actividad',
        'descripcion',
        'canal_id',
        'naturaleza_id',
        'usuario_ti_id'
    ];

    protected $casts = [
        'fecha' => 'date'
    ];

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }

    public function canal()
    {
        return $this->belongsTo(Canal::class, 'canal_id');
    }

    public function naturaleza()
    {
        return $this->belongsTo(Naturaleza::class, 'naturaleza_id');
    }

    public function usuarioTi()
    {
        return $this->belongsTo(UsuarioTI::class, 'usuario_ti_id');
    }
}