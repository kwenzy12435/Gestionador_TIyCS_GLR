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

    // Usamos route-model binding para mayor claridad y seguridad
    public function show(UsuarioTI $usuarioTI)
    {
        return view('usuarios-ti.show', compact('usuarioTI'));
    }

    public function edit(UsuarioTI $usuarioTI)
    {
        return view('usuarios-ti.edit', compact('usuarioTI'));
    }

    // NOTE: recibe el modelo directamente (route-model binding)
    public function update(Request $request, UsuarioTI $usuarioTI)
    {
        // Validación: 'contrasena' es opcional en actualización
        $request->validate([
            'usuario' => 'required|string|max:100|unique:usuarios_ti,usuario,' . $usuarioTI->id,
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

            $usuarioTI->update($data);

            return redirect()->route('usuarios-ti.index')
                ->with('success', 'Usuario TI actualizado exitosamente.');
        } catch (\Throwable $e) {
            // Loguea si lo deseas: Log::error(...)
            return back()->withInput()->withErrors(['error' => 'Ocurrió un error al actualizar el usuario.']);
        }
    }

    // destroy con route-model binding
    public function destroy(UsuarioTI $usuarioTI)
    {
        try {
            // Prevenir auto-eliminación
            if ($usuarioTI->id === auth()->id()) {
                return redirect()->route('usuarios-ti.index')
                    ->with('error', 'No puedes eliminar tu propio usuario.');
            }

            $usuarioTI->delete();

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
