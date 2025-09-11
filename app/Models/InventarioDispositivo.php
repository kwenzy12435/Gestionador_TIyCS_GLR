<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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