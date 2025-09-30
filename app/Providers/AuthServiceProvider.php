<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\MonitoreoRed;
use App\Policies\MonitoreoRedPolicy;
use App\Models\Colaborador;
use App\Policies\ColaboradorPolicy;
usE App\Models\inventarioDispositivo;
use App\Policies\InventarioDispositivoPolicy;
use App\Models\licencia;
use App\Policies\LicenciaPolicy;
use App\Models\ReporteActividad;
use App\Policies\ReporteActividadPolicy;
use App\Models\usuarioTI;
use App\Policies\UsuarioTIPolicy;
use App\Models\BitacoraRespaldo;
use App\Policies\BitacoraRespaldoPolicy;


class AuthServiceProvider extends ServiceProvider
{
protected $policies = [
    MonitoreoRed::class => MonitoreoRedPolicy::class,
    Colaborador::class => ColaboradorPolicy::class,
    inventarioDispositivo::class => InventarioDispositivoPolicy::class,
    licencia::class => LicenciaPolicy::class,
    ReporteActividad::class => ReporteActividadPolicy::class,
    usuarioTI::class => UsuarioTIPolicy::class,
    Colaborador::class => ColaboradorPolicy::class,
    BitacoraRespaldo::class => BitacoraRespaldoPolicy::class,
    // Agrega más modelos y políticas según sea necesario
];

    public function boot(): void
    {
        $this->registerPolicies();

        

    }
    
}
