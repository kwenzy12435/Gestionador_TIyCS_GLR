<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificar si el usuario tiene permisos (ejemplo: solo admin)
        if (session('usuario_ti_rol') !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos');
        }

        $users = DB::table('usuarios_ti')->get();
        
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (session('usuario_ti_rol') !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos');
        }

        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (session('usuario_ti_rol') !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos');
        }

        // Validación
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|unique:usuarios_ti|max:50',
            'nombres' => 'required|max:100',
            'apellidos' => 'nullable|max:100',
            'contrasena' => 'required|min:6',
            'puesto' => 'nullable|max:100',
            'rol' => 'required|in:admin,tecnico,supervisor'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Insertar en la base de datos
        DB::table('usuarios_ti')->insert([
            'usuario' => $request->usuario,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'contrasena' => Hash::make($request->contrasena),
            'puesto' => $request->puesto,
            'rol' => $request->rol,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (session('usuario_ti_rol') !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos');
        }

        $user = DB::table('usuarios_ti')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado');
        }

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (session('usuario_ti_rol') !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos');
        }

        $user = DB::table('usuarios_ti')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (session('usuario_ti_rol') !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos');
        }

        $validator = Validator::make($request->all(), [
            'usuario' => 'required|max:50|unique:usuarios_ti,usuario,' . $id,
            'nombres' => 'required|max:100',
            'apellidos' => 'nullable|max:100',
            'puesto' => 'nullable|max:100',
            'rol' => 'required|in:admin,tecnico,supervisor'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'usuario' => $request->usuario,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'puesto' => $request->puesto,
            'rol' => $request->rol,
            'updated_at' => now(),
        ];

        // Solo actualizar contraseña si se proporcionó
        if ($request->filled('contrasena')) {
            $data['contrasena'] = Hash::make($request->contrasena);
        }

        DB::table('usuarios_ti')->where('id', $id)->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (session('usuario_ti_rol') !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos');
        }

        $user = DB::table('usuarios_ti')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado');
        }

        DB::table('usuarios_ti')->where('id', $id)->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente');
    }
}