<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (session('usuario_ti_id')) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'contrasena' => 'required|string'
        ]);

        $user = DB::table('usuarios_ti')
                  ->where('usuario', $request->usuario)
                  ->first();

        if ($user && Hash::check($request->contrasena, $user->contrasena)) {
            session([
                'usuario_ti_id' => $user->id,
                'usuario_ti_nombre' => $user->nombres . ' ' . $user->apellidos,
                'usuario_ti_rol' => $user->rol,
            ]);

            return redirect()->route('dashboard')->with('success', '¡Bienvenido!');
        }

        return back()->with('error', 'Credenciales inválidas')->withInput();
    }

    public function dashboard()
    {
        if (!session('usuario_ti_id')) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión');
        }

        return view('dashboard', [
            'user' => (object) [
                'nombre' => session('usuario_ti_nombre'),
                'rol' => session('usuario_ti_rol'),
            ]
        ]);
    }

    public function logout(Request $request)
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}