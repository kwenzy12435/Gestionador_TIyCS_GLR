<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InventarioDispositivo extends Model
{
    use HasFactory;

    protected $table = 'inventario_dispositivos';
    
    protected $fillable = [
        'estado',
        'tipo_id',
        'marca_id',
        'mac',
        'modelo',
        'serie',
        'numero_serie',
        'procesador',
        'memoria_ram',
        'ssd',
        'hdd',
        'color',
        'costo',
        'fecha_compra',
        'garantia_hasta',
        'colaborador_id'
    ];

    protected $casts = [
        'costo' => 'decimal:2',
        'fecha_compra' => 'date',
        'garantia_hasta' => 'date'
    ];

  
    public function scopeActivos($query)
    {
        return $query->whereIn('estado', ['nuevo', 'asignado']);
    }

    public function scopeEnReparacion($query)
    {
        return $query->where('estado', 'reparación');
    }

    public function scopeDeBaja($query)
    {
        return $query->where('estado', 'baja');
    }

    public function scopeAsignados($query)
    {
        return $query->where('estado', 'asignado');
    }

    public function scopePorVencerGarantia($query, $dias = 30)
    {
        return $query->whereNotNull('garantia_hasta')
                    ->where('garantia_hasta', '>=', Carbon::today())
                    ->where('garantia_hasta', '<=', Carbon::today()->addDays($dias));
    }


    public function getNombreCompletoAttribute()
    {
        return "{$this->marca->nombre} {$this->modelo} - {$this->numero_serie}";
    }

    public function getDiasGarantiaRestantesAttribute()
    {
        if (!$this->garantia_hasta) {
            return null;
        }

        return Carbon::parse($this->garantia_hasta)->diffInDays(Carbon::today());
    }

    public function getGarantiaPorVencerAttribute()
    {
        return $this->dias_garantia_restantes !== null && $this->dias_garantia_restantes <= 30;
    }

    // Relaciones
    public function tipo()
    {
        return $this->belongsTo(TipoDispositivo::class, 'tipo_id');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }
}