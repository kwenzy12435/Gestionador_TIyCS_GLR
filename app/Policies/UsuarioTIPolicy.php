<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\UsuarioTI;

class UsuarioTIPolicy
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
    public function view(UsuarioTI $user, UsuarioTI $usuarioTI): bool
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
    public function update(UsuarioTI $user, UsuarioTI $usuarioTI): bool
    {
        // Solo admin puede editar otros usuarios, o el propio usuario puede editarse
        return $user->isAdmin() || $user->id === $usuarioTI->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(UsuarioTI $user, UsuarioTI $usuarioTI): bool
    {
          return $user->isAdmin() && $user->id !== $usuarioTI->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(UsuarioTI $user, UsuarioTI $usuarioTI): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(UsuarioTI $user, UsuarioTI $usuarioTI): bool
    {
        return false;
    }
}
