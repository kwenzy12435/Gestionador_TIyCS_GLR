<?php

namespace App\Policies;

use App\Models\UsuarioTI;
use App\Models\MonitoreoRed;
use Illuminate\Auth\Access\Response;

class MonitoreoRedPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(UsuarioTI $user): bool
    {
        // Todos los usuarios autenticados pueden ver la lista de monitoreos
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(UsuarioTI $user, MonitoreoRed $monitoreoRed): bool
    {
        // Pueden ver el monitoreo: admin, personal-ti, o el responsable del registro
        return $user->isAdmin() || 
               $user->isAuxiliarTI() || 
               $user->rol === 'PERSONAL-TI' || 
               $user->id === $monitoreoRed->responsable;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(UsuarioTI $user): bool
    {
        // Pueden crear: admin, auxiliar-ti, personal-ti
        return $user->isAdmin() || 
               $user->isAuxiliarTI() || 
               $user->rol === 'PERSONAL-TI';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(UsuarioTI $user, MonitoreoRed $monitoreoRed): bool
    {
        // Pueden actualizar: admin, o el responsable del registro
        return $user->isAdmin() || $user->id === $monitoreoRed->responsable;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(UsuarioTI $user, MonitoreoRed $monitoreoRed): bool
    {
        // Solo admin puede eliminar registros (para mantener historial)
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(UsuarioTI $user, MonitoreoRed $monitoreoRed): bool
    {
        // Solo admin puede restaurar registros eliminados
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(UsuarioTI $user, MonitoreoRed $monitoreoRed): bool
    {
        // Solo admin puede eliminar permanentemente
        return $user->isAdmin();
    }
}