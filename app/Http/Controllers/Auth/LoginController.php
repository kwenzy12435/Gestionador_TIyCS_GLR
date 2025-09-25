<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UsuarioTI;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => 'required|string',
            'contrasena' => 'required|string'
        ]);

        $user = UsuarioTI::where('usuario', $credentials['usuario'])->first();

        if ($user && password_verify($credentials['contrasena'], $user->contrasena)) {
            Auth::login($user, $request->has('remember'));
            
            $request->session()->regenerate();
            
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'usuario' => 'Las credenciales proporcionadas no son válidas.',
        ])->withInput($request->except('contrasena'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
    public function verificarPassword(Request $request)
{
    $request->validate([
        'password' => 'required|string',
    ]);

    $user = Auth::user();

    if (! $user) {
        return response()->json(['success' => false], 401);
    }

    // Tu proyecto usa password_verify en login, así usamos lo mismo:
    if (password_verify($request->password, $user->contrasena)) {
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 403);
}
}