<?php

namespace App\Policies;

use App\Models\UsuarioTI;
use App\Models\InventarioDispositivo;
use Illuminate\Auth\Access\Response;

class InventarioDispositivoPolicy
{
    public function viewAny(UsuarioTI $user): bool
    {
       
        return true;
    }

    public function view(UsuarioTI $user, InventarioDispositivo $inventarioDispositivo): bool
    {
       
        return true;
    }

    public function create(UsuarioTI $user): bool
    {
     
        return true;
    }

    public function update(UsuarioTI $user, InventarioDispositivo $inventarioDispositivo): bool
    {
       
        return true;
    }

    public function delete(UsuarioTI $user, InventarioDispositivo $inventarioDispositivo): bool
    {

        return true;
    }

    public function generarQR(UsuarioTI $user, InventarioDispositivo $inventarioDispositivo): bool
    {
       
        return true;
    }
}