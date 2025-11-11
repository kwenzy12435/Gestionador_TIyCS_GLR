<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class Colaborador extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'colaboradores';

    protected $fillable = [
        'usuario',
        'departamento_id',
        'nombre',
        'apellidos',
        'puesto',
        'anydesk_id',
        // Nota: 'nombres' no se pone en fillable; es derivada
    ];

    // Relaciones
    public function inventarios()
    {
        return $this->hasMany(InventarioDispositivo::class, 'colaborador_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    // Accessor: nombre completo
    public function getNombreCompletoAttribute(): string
    {
        return trim(($this->nombre ?? '').' '.($this->apellidos ?? ''));
    }

    // Scope: ordenar por nombre completo
    public function scopeOrderByNombreCompleto($query)
    {
        return $query->orderByRaw("CONCAT_WS(' ', nombre, apellidos) ASC");
    }

    // Mantener sincronizada la columna 'nombres' si existe en la BD
    protected static function booted()
    {
        static::saving(function (self $model) {
            if (Schema::hasColumn($model->getTable(), 'nombres')) {
                $model->nombres = trim(($model->nombre ?? '').' '.($model->apellidos ?? ''));
            }
        });
    }
}
