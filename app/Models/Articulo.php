<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;

    protected $table = 'articulos';

    protected $fillable = [
        'categoria_id',
        'subcategoria_id',
        'nombre',
        'descripcion',
        'cantidad',
        'unidades',
        'ubicacion',
        'fecha_ingreso',
        'estado'
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'cantidad' => 'integer'
    ];

    
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class, 'subcategoria_id');
    }
}
