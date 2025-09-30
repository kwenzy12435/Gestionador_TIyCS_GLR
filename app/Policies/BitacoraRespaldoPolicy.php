<?php

namespace App\Policies;

use App\Models\BitacoraRespaldo;
use App\Models\UsuarioTi;
use Illuminate\Auth\Access\HandlesAuthorization;

class BitacoraRespaldoPolicy
{
    use HandlesAuthorization;

    // Método helper para verificar rol ADMIN o AUXILIAR
    private function isAdminOrAuxiliar(UsuarioTi $user)
    {
        return in_array($user->rol, ['ADMIN', 'AUXILIAR']);
    }

    public function viewAny(UsuarioTi $user)
    {
        return $this->isAdminOrAuxiliar($user);
    }

    public function view(UsuarioTi $user, BitacoraRespaldo $bitacora)
    {
        return $this->isAdminOrAuxiliar($user);
    }

    public function create(UsuarioTi $user)
    {
        return $this->isAdminOrAuxiliar($user);
    }

    public function update(UsuarioTi $user, BitacoraRespaldo $bitacora)
    {
        return $this->isAdminOrAuxiliar($user);
    }

    public function delete(UsuarioTi $user, BitacoraRespaldo $bitacora)
    {
        return $this->isAdminOrAuxiliar($user);
    }
}
