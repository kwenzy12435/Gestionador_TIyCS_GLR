<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

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

    public function getContrasenaDesencriptadaAttribute()
    {
        try {
            return Crypt::decryptString($this->contrasena);
        } catch (\Exception $e) {
            return 'Error al desencriptar';
        }
    }

    public function getEstadoAttribute()
    {
        if (!$this->expiracion) {
            return 'Activa';
        }

        $hoy = Carbon::today();
        $expiracion = Carbon::parse($this->expiracion);

        if ($expiracion->isPast()) {
            return 'Expirada';
        } elseif ($expiracion->diffInDays($hoy) <= 7) {
            return 'Por expirar';
        } else {
            return 'Activa';
        }
    }

    public function scopeActivas($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expiracion')
              ->orWhere('expiracion', '>=', Carbon::today());
        });
    }

    public function scopePorExpiar($query, $dias = 30)
    {
        return $query->whereNotNull('expiracion')
                    ->where('expiracion', '>=', Carbon::today())
                    ->where('expiracion', '<=', Carbon::today()->addDays($dias));
    }

    public function scopeExpiradas($query)
    {
        return $query->whereNotNull('expiracion')
                    ->where('expiracion', '<', Carbon::today());
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }

    public function plataforma()
    {
        return $this->belongsTo(Plataforma::class, 'plataforma_id');
    }

    public function estaActiva()
    {
        return !$this->expiracion || $this->expiracion >= Carbon::today();
    }

    public function estaPorExpiar($dias = 7)
    {
        return $this->expiracion && 
               $this->expiracion >= Carbon::today() && 
               $this->expiracion <= Carbon::today()->addDays($dias);
    }
}
