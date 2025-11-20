<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UsuarioTI;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Verificar límite de intentos
        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        // Validar campos requeridos
   $credentials = $request->validate(
    [
        'usuario'    => 'required|string|max:50',
        'contrasena' => 'required|string|min:6',
    ],
    [
        'required' => 'El campo :attribute es obligatorio.',
        'string'   => 'El campo :attribute debe ser texto.',
        'max'      => 'El campo :attribute no debe exceder :max caracteres.',
        'min'      => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    [
        'usuario'    => 'usuario',
        'contrasena' => 'contraseña',
    ]
);

        // Buscar usuario (con eager loading si necesitas relaciones)
        $user = UsuarioTI::where('usuario', $credentials['usuario'])->first();

        // Verificar usuario, contraseña y estado activo
        if ($user && Hash::check($credentials['contrasena'], $user->contrasena)) {
            
            // Verificar si el usuario está activo (si tienes campo de estado)
            if (isset($user->activo) && !$user->activo) {
                return back()->withErrors([
                    'credenciales' => 'Su cuenta está desactivada. Contacte al administrador.',
                ])->withInput();
            }

            // Login exitoso
            Auth::login($user, $request->boolean('remember')); // Soporte para "recordarme"
            $request->session()->regenerate();
            
            // Limpiar intentos fallidos
            $this->clearLoginAttempts($request);

            // Redirección con mensaje de éxito
            return redirect()->intended(route('dashboard'))
                ->with('success', '¡Bienvenido ' . $user->nombres . '!');
        }

        // Incrementar intentos fallidos
        $this->incrementLoginAttempts($request);

        // Credenciales incorrectas - mensaje general de seguridad
        return back()->withErrors([
            'credenciales' => 'Usuario o contraseña incorrectos.',
        ])->withInput($request->except('contrasena')); // No mantener la contraseña en el input
    }

    public function logout(Request $request)
    {        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Sesión cerrada correctamente.');
    }

    /**
     * Métodos para el rate limiting (límite de intentos)
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return RateLimiter::tooManyAttempts(
            $this->throttleKey($request), 
            5, // 5 intentos máximos
            1 * 60 // 1 minuto de bloqueo
        );
    }

    protected function incrementLoginAttempts(Request $request)
    {
        RateLimiter::hit($this->throttleKey($request));
    }

    protected function clearLoginAttempts(Request $request)
    {
        RateLimiter::clear($this->throttleKey($request));
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        return back()->withErrors([
            'credenciales' => 'Demasiados intentos fallidos. Por favor espere ' . $seconds . ' segundos.',
        ])->withInput($request->except('contrasena'));
    }

    protected function throttleKey(Request $request)
    {
        return Str::transliterate(Str::lower($request->input('usuario')).'|'.$request->ip());
    }
}