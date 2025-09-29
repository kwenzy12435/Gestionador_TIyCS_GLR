<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ReporteActividad;
use App\Models\UsuarioTI;

class ReporteActividadPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(UsuarioTI $usuarioTI): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(UsuarioTI $usuarioTI, ReporteActividad $reporteActividad): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(UsuarioTI $usuarioTI): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(UsuarioTI $usuarioTI, ReporteActividad $reporteActividad): bool
    {
         return $user->id === $reporte->usuario_ti_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(UsuarioTI $usuarioTI, ReporteActividad $reporteActividad): bool
    {
        return $user->id === $reporte->usuario_ti_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(UsuarioTI $usuarioTI, ReporteActividad $reporteActividad): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(UsuarioTI $usuarioTI, ReporteActividad $reporteActividad): bool
    {
        return false;
    }
}
