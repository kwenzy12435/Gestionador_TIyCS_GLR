<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDispositivo extends Model
{
    use HasFactory;

    protected $table = 'tipos_dispositivo';
    protected $fillable = ['nombre'];

    public function inventarios()
    {
        return $this->hasMany(InventarioDispositivo::class, 'tipo_id');
    }
}


