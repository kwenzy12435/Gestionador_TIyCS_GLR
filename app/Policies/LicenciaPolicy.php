<?php

namespace App\Policies;

use App\Models\UsuarioTI;
use App\Models\Licencia;
use Illuminate\Auth\Access\Response;

class LicenciaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(UsuarioTI $user): bool
    {
        
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(UsuarioTI $user, Licencia $licencia): bool
    {
        
        return true;
    }

    /**
     * Determine whether the user can view passwords.
     */
    public function viewPassword(UsuarioTI $user, Licencia $licencia): bool
    {
      
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(UsuarioTI $user): bool
    {
        // Solo admin y auxiliar-ti pueden crear licencias
        return $user->isAdmin() || $user->isAuxiliarTI();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(UsuarioTI $user, Licencia $licencia): bool
    {
        // Solo admin puede actualizar licencias
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(UsuarioTI $user, Licencia $licencia): bool
    {
        // Solo admin puede eliminar licencias
        return $user->isAdmin();
    }
}