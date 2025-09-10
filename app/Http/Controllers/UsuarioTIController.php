<?php

namespace App\Http\Controllers;

use App\Models\UsuarioTI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioTIController extends Controller
{
    public function index()
    {
        $usuarios = UsuarioTI::all();
        return view('usuarios_ti.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios_ti.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:100|unique:usuarios_ti',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'nullable|string|max:100',
            'puesto' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:ADMIN,AUXILIAR-TI,PERSONAL-TI',
            'contrasena' => 'required|string|min:6|confirmed'
        ]);

        UsuarioTI::create([
            'usuario' => $request->usuario,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'puesto' => $request->puesto,
            'telefono' => $request->telefono,
            'rol' => $request->rol,
            'contrasena' => Hash::make($request->contrasena)
        ]);

        return redirect()->route('usuarios-ti.index')
            ->with('success', 'Usuario TI creado exitosamente.');
    }

    public function show(UsuarioTI $usuarioTI)
    {
        return view('usuarios_ti.show', compact('usuarioTI'));
    }

    public function edit(UsuarioTI $usuarioTI)
    {
        return view('usuarios_ti.edit', compact('usuarioTI'));
    }

    public function update(Request $request, UsuarioTI $usuarioTI)
    {
        $request->validate([
            'usuario' => 'required|string|max:100|unique:usuarios_ti,usuario,' . $usuarioTI->id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'nullable|string|max:100',
            'puesto' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:ADMIN,AUXILIAR-TI,PERSONAL-TI',
            'contrasena' => 'nullable|string|min:6|confirmed'
        ]);

        $data = [
            'usuario' => $request->usuario,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'puesto' => $request->puesto,
            'telefono' => $request->telefono,
            'rol' => $request->rol
        ];

        if ($request->filled('contrasena')) {
            $data['contrasena'] = Hash::make($request->contrasena);
        }

        $usuarioTI->update($data);

        return redirect()->route('usuarios-ti.index')
            ->with('success', 'Usuario TI actualizado exitosamente.');
    }

     public function destroy($id)
    {
        try {
            $usuarioTI = UsuarioTI::findOrFail($id);
            $usuarioTI->delete();

            return redirect()->route('usuarios-ti.index')
                ->with('success', 'Usuario TI eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('usuarios-ti.index')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}