<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->rol !== 'ADMIN') {
            abort(403, 'Acceso no autorizado. Solo administradores pueden acceder a esta sección.');
        }

        return $next($request);
    }
}