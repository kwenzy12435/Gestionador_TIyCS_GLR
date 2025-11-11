<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        // Ajusta a tu esquema real (is_admin/rol/isAdmin())
        $esAdmin =
            (method_exists($user, 'isAdmin') && $user->isAdmin())
            || ($user->is_admin ?? false)
            || (($user->rol ?? null) === 'admin');

        if (! $esAdmin) {
            abort(403, 'Solo administradores.');
        }

        return $next($request);
    }
}
