<?php

namespace App\Policies;

use App\Models\Colaborador;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ColaboradorPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        // Los administradores pueden hacer todo
        if ($user->rol === 'ADMIN') {
            return true;
        }
    }

    public function viewAny($user)
    {
        return false;
    }

    public function view($user, Colaborador $colaborador)
    {
        return false;
    }

    public function create($user)
    {
        return false;
    }

    public function update($user, Colaborador $colaborador)
    {
        return false;
    }

    public function delete($user, Colaborador $colaborador)
    {
        return false;
    }
}
