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
        return view('usuarios-ti.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios-ti.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:100|unique:usuarios_ti,usuario',
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
            ],
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
            'contrasena' => Hash::make($request->contrasena),
        ]);

        return redirect()->route('usuarios-ti.index')
            ->with('success', 'Usuario TI creado exitosamente.');
    }

    public function show(UsuarioTI $usuarios_ti)
    {
        return view('usuarios-ti.show', compact('usuarios_ti'));
    }

    public function edit(UsuarioTI $usuarios_ti)
    {
        return view('usuarios-ti.edit', compact('usuarios_ti'));
    }

    // CORRECCIÓN: Cambiar $usuarioTI por $usuarios_ti dentro del método
    public function update(Request $request, UsuarioTI $usuarios_ti)
    {
        // Validación: 'contrasena' es opcional en actualización
        $request->validate([
            'usuario' => 'required|string|max:100|unique:usuarios_ti,usuario,' . $usuarios_ti->id, // CAMBIADO
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
            ],
        ], [
            'contrasena.regex' => 'La contraseña debe contener al menos una letra minúscula, una letra mayúscula y un número.',
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'contrasena.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        try {
            $data = [
                'usuario' => $request->usuario,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'puesto' => $request->puesto,
                'telefono' => $request->telefono,
                'rol' => $request->rol,
            ];

            if ($request->filled('contrasena')) {
                $data['contrasena'] = Hash::make($request->contrasena);
            }

            $usuarios_ti->update($data); // CAMBIADO

            return redirect()->route('usuarios-ti.index')
                ->with('success', 'Usuario TI actualizado exitosamente.');
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['error' => 'Ocurrió un error al actualizar el usuario.']);
        }
    }

    public function destroy(UsuarioTI $usuarios_ti)
    {
        try {
            if ($usuarios_ti->id === auth()->id()) {
                return redirect()->route('usuarios-ti.index')
                    ->with('error', 'No puedes eliminar tu propio usuario.');
            }

            $usuarios_ti->delete();

            return redirect()->route('usuarios-ti.index')
                ->with('success', 'Usuario TI eliminado exitosamente.');
        } catch (\Throwable $e) {
            return redirect()->route('usuarios-ti.index')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    public function actualizarCredenciales(Request $request)
    {
        try {
            $usuario = UsuarioTI::findOrFail(auth()->id());

            $request->validate([
                'usuario' => 'required|string|max:100|unique:usuarios_ti,usuario,' . $usuario->id,
                'contrasena_actual' => 'required',
                'nueva_contrasena' => 'nullable|min:8|confirmed|different:contrasena_actual',
            ], [
                'nueva_contrasena.different' => 'La nueva contraseña debe ser distinta de la contraseña actual.',
            ]);

            if (!Hash::check($request->contrasena_actual, $usuario->contrasena)) {
                return back()->withErrors(['contrasena_actual' => 'La contraseña actual es incorrecta.']);
            }

            $data = ['usuario' => $request->usuario];

            if ($request->filled('nueva_contrasena')) {
                $data['contrasena'] = Hash::make($request->nueva_contrasena);
            }

            $usuario->update($data);

            return redirect()->route('dashboard')
                ->with('success', 'Tus credenciales se actualizaron correctamente.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al actualizar credenciales: ' . $e->getMessage());
        }
    }
}