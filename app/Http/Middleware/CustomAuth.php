<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado en nuestra tabla usuarios_ti
        if (!session('usuario_ti_id')) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión primero');
        }

        return $next($request);
    }
}