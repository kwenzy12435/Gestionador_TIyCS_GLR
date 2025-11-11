<?php

namespace App\Http\Controllers;

use App\Models\UsuarioTI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioTIController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $usuarios = UsuarioTI::when($search, function($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('usuario', 'LIKE', "%{$search}%")
                      ->orWhere('nombres', 'LIKE', "%{$search}%")
                      ->orWhere('apellidos', 'LIKE', "%{$search}%")
                      ->orWhere('puesto', 'LIKE', "%{$search}%")
                      ->orWhere('telefono', 'LIKE', "%{$search}%")
                      ->orWhere('rol', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('nombres')
            ->orderBy('apellidos')
            ->paginate(15); // ✅ Paginación para mejor rendimiento
        
        return view('usuarios-ti.index', compact('usuarios', 'search'));
    }

    public function create()
    {
        $roles = ['ADMIN', 'AUXILIAR-TI', 'PERSONAL-TI'];
        return view('usuarios-ti.create', compact('roles')); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario' => 'required|string|max:100|unique:usuarios_ti,usuario',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'nullable|string|max:100',
            'puesto' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'rol' => ['required', Rule::in(['ADMIN', 'AUXILIAR-TI', 'PERSONAL-TI'])],
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

        try {
            UsuarioTI::create([
                'usuario' => $validated['usuario'],
                'nombres' => $validated['nombres'],
                'apellidos' => $validated['apellidos'],
                'puesto' => $validated['puesto'],
                'telefono' => $validated['telefono'],
                'rol' => $validated['rol'],
                'contrasena' => Hash::make($validated['contrasena'])
            ]);

            return redirect()->route('usuarios-ti.index')
                ->with('success', 'Usuario TI creado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function show(UsuarioTI $usuario)
    {
        return view('usuarios-ti.show', compact('usuario'));
    }

    public function edit(UsuarioTI $usuario)
    {
        $roles = ['ADMIN', 'AUXILIAR-TI', 'PERSONAL-TI'];
        return view('usuarios-ti.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, UsuarioTI $usuario)
    {
        $validated = $request->validate([
            'usuario' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('usuarios_ti', 'usuario')->ignore($usuario->id)
            ],
            'nombres' => 'required|string|max:100',
            'apellidos' => 'nullable|string|max:100',
            'puesto' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'rol' => ['required', Rule::in(['ADMIN', 'AUXILIAR-TI', 'PERSONAL-TI'])],
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

        try {
            $data = [
                'usuario' => $validated['usuario'],
                'nombres' => $validated['nombres'],
                'apellidos' => $validated['apellidos'],
                'puesto' => $validated['puesto'],
                'telefono' => $validated['telefono'],
                'rol' => $validated['rol']
            ];

            if ($request->filled('contrasena')) {
                $data['contrasena'] = Hash::make($validated['contrasena']);
            }

            $usuario->update($data);

            return redirect()->route('usuarios-ti.index')
                ->with('success', 'Usuario TI actualizado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function destroy(UsuarioTI $usuario)
    {
        try {
            if ($usuario->id === auth()->id()) {
                return redirect()->route('usuarios-ti.index')
                    ->with('error', 'No puedes eliminar tu propio usuario.');
            }

            // ✅ Prevenir eliminación si el usuario tiene registros relacionados
            if ($usuario->reportesActividades()->exists()) {
                return redirect()->route('usuarios-ti.index')
                    ->with('error', 'No se puede eliminar el usuario porque tiene reportes de actividad asociados.');
            }

            $usuario->delete();

            return redirect()->route('usuarios-ti.index')
                ->with('success', 'Usuario TI eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('usuarios-ti.index')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    public function actualizarCredenciales(Request $request)
    {
        $usuario = auth()->user();

        $validated = $request->validate([
            'usuario' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('usuarios_ti', 'usuario')->ignore($usuario->id)
            ],
            'contrasena_actual' => 'required',
            'nueva_contrasena' => [
                'nullable', 
                'min:8', 
                'confirmed', 
                'different:contrasena_actual',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ]
        ], [
            'nueva_contrasena.regex' => 'La nueva contraseña debe contener al menos una letra minúscula, una letra mayúscula y un número.',
        ]);

        if (!Hash::check($validated['contrasena_actual'], $usuario->contrasena)) {
            return back()->withErrors(['contrasena_actual' => 'La contraseña actual es incorrecta.']);
        }

        try {
            $data = ['usuario' => $validated['usuario']];

            if ($request->filled('nueva_contrasena')) {
                $data['contrasena'] = Hash::make($validated['nueva_contrasena']);
            }

            $usuario->update($data);

            return redirect()->route('dashboard')
                ->with('success', 'Tus credenciales se actualizaron correctamente.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar las credenciales: ' . $e->getMessage());
        }
    }
}