<?php

namespace App\Http\Controllers;

use App\Models\UsuarioTI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioTIController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            // Búsqueda usando SQL directo con LIKE para múltiples campos
            $usuarios = UsuarioTI::whereRaw("
                usuario LIKE ? OR 
                nombres LIKE ? OR 
                apellidos LIKE ? OR 
                puesto LIKE ? OR 
                telefono LIKE ? OR 
                rol LIKE ?
            ", array_fill(0, 6, "%$search%"))->get();
        } else {
            $usuarios = UsuarioTI::all();
        }
        
        return view('usuarios-ti.index', compact('usuarios', 'search'));
    }

    public function create()
    {
        return view('usuarios-ti.create'); 
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
            'contrasena' => [
                'required', 
                'string', 
                'min:8', 
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ]
        ], [
            'contrasena.regex' => 'La contraseña debe contener al menos una letra minúscula, una letra mayúscula y un número.',
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'contrasena.confirmed' => 'La confirmación de la contraseña no coincide.',
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

    public function show(UsuarioTI $usuario)
    {
        return view('usuarios-ti.show', compact('usuario'));
    }

    public function edit(UsuarioTI $usuario)
    {
        return view('usuarios-ti.edit', compact('usuario'));
    }

    public function update(Request $request, UsuarioTI $usuario)
    {
        $request->validate([
            'usuario' => 'required|string|max:100|unique:usuarios_ti,usuario,' . $usuario->id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'nullable|string|max:100',
            'puesto' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:ADMIN,AUXILIAR-TI,PERSONAL-TI',
            'contrasena' => [
                'nullable', 
                'string', 
                'min:8', 
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ]
        ], [
            'contrasena.regex' => 'La contraseña debe contener al menos una letra minúscula, una letra mayúscula y un número.',
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'contrasena.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $data = $request->only(['usuario','nombres','apellidos','puesto','telefono','rol']);

        if ($request->filled('contrasena')) {
            $data['contrasena'] = Hash::make($request->contrasena);
        }

        $usuario->update($data);

        return redirect()->route('usuarios-ti.index')
            ->with('success', 'Usuario TI actualizado exitosamente.');
    }

    public function destroy(UsuarioTI $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios-ti.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();

        return redirect()->route('usuarios-ti.index')
            ->with('success', 'Usuario TI eliminado exitosamente.');
    }

    public function actualizarCredenciales(Request $request)
    {
        $usuario = auth()->user();

        $request->validate([
            'usuario' => 'required|string|max:100|unique:usuarios_ti,usuario,' . $usuario->id,
            'contrasena_actual' => 'required',
            'nueva_contrasena' => 'nullable|min:8|confirmed|different:contrasena_actual'
        ]);

        if (!Hash::check($request->contrasena_actual, $usuario->contrasena)) {
            return back()->withErrors(['contrasena_actual' => 'La contraseña actual es incorrecta.']);
        }

        $data = ['usuario' => $request->usuario];

        if ($request->filled('nueva_contrasena')) {
            $data['contrasena'] = Hash::make($request->nueva_contrasena);
        }

        $usuario->update($data);

        return redirect()->route('dashboard')->with('success', 'Tus credenciales se actualizaron correctamente.');
    }
}