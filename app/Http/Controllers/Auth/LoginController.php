<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UsuarioTI;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

   public function login(Request $request)
    {
    // Validar los datos del formulario
    $credentials = $request->validate([
        'usuario' => 'required|string',
        'contrasena' => 'required|string'
    ]);

    // Buscar el usuario en la base de datos
    $user = UsuarioTI::where('usuario', $credentials['usuario'])->first();

    // Verificar si el usuario existe y la contraseña es correcta
    if ($user && Hash::check($credentials['contrasena'], $user->contrasena)) {
        // Iniciar sesión
        Auth::login($user);
        
        // Regenerar la sesión
        $request->session()->regenerate();
        
        // Redirigir AL DASHBOARD después del login exitoso
        return redirect()->route('dashboard')->with('success', '¡Bienvenido ' . $user->nombres . '!');
    }

    // Si las credenciales son incorrectas
    return back()->withErrors([
        'usuario' => 'Las credenciales proporcionadas no son válidas.',
    ]);
    }
    public function logout(Request $request)
    {
        // Cerrar sesión
        Auth::logout();
        
        // Invalidar la sesión
        $request->session()->invalidate();
        
        // Regenerar el token CSRF
        $request->session()->regenerateToken();
        
        // Redirigir al login
        return redirect('/login')->with('status', 'Sesión cerrada correctamente.');
    }
}