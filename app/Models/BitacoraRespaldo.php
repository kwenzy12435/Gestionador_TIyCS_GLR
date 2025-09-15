<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraRespaldo extends Model
{
    use HasFactory;

    // Nombre exacto de la tabla en la base de datos
    protected $table = 'bitacora_respaldo_conpaq';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'empresa_id',
        'usuario_ti_id',
        'respaldo_nominas',
        'respaldo_contabilidad',
        'fecha_respaldo',
        'estado',
        'ubicacion_guardado',
        'acciones_alternativas'
    ];

    // Conversiones de tipos de datos
    protected $casts = [
        'respaldo_nominas' => 'boolean',
        'respaldo_contabilidad' => 'boolean',
        'fecha_respaldo' => 'date'
    ];

    // Relación con usuario TI (si existe el modelo)
    public function usuarioTi()
    {
        return $this->belongsTo(UsuarioTi::class, 'usuario_ti_id');
    }
}