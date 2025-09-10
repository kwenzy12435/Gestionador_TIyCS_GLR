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
}