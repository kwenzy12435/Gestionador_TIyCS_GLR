<?php

namespace App\Policies;

use App\Models\UsuarioTI;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfigSistemaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can access system configuration.
     */
    public function accessSystemConfig(UsuarioTI $user): bool
    {
        return $user->rol === 'ADMIN';
    }

    /**
     * Determine whether the user can view any configuration models.
     */
    public function viewAny(UsuarioTI $user): bool
    {
        return $user->rol === 'ADMIN';
    }

    /**
     * Determine whether the user can create configuration records.
     */
    public function create(UsuarioTI $user): bool
    {
        return $user->rol === 'ADMIN';
    }

    /**
     * Determine whether the user can update configuration records.
     */
    public function update(UsuarioTI $user): bool
    {
        return $user->rol === 'ADMIN';
    }

    /**
     * Determine whether the user can delete configuration records.
     */
    public function delete(UsuarioTI $user): bool
    {
        return $user->rol === 'ADMIN';
    }

    /**
     * Determine whether the user can manage specific table.
     */
    public function manageTable(UsuarioTI $user, string $table): bool
    {
        // Additional table-specific logic if needed
        return $user->rol === 'ADMIN';
    }
}