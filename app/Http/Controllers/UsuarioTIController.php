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
        return view('usuarios-ti.index', compact('usuarios')); // ✅ CORREGIDO
    }

    public function create()
    {
        return view('usuarios-ti.create'); // ✅ CORREGIDO
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

    public function show($id) // ✅ CAMBIAR a parámetro simple
    {
        $usuarioTI = UsuarioTI::findOrFail($id);
        return view('usuarios-ti.show', compact('usuarioTI')); // ✅ CORREGIDO
    }

    public function edit($id) // ✅ CAMBIAR a parámetro simple
    {
        $usuarioTI = UsuarioTI::findOrFail($id);
        return view('usuarios-ti.edit', compact('usuarioTI')); // ✅ CORREGIDO
    }

    public function update(Request $request, $id) // ✅ CAMBIAR a parámetro simple
    {
        $usuarioTI = UsuarioTI::findOrFail($id);
        
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
            
            // Prevenir auto-eliminación
            if ($usuarioTI->id === auth()->id()) {
                return redirect()->route('usuarios-ti.index')
                    ->with('error', 'No puedes eliminar tu propio usuario.');
            }

            $usuarioTI->delete();

            return redirect()->route('usuarios-ti.index')
                ->with('success', 'Usuario TI eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('usuarios-ti.index')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
    public function actualizarCredenciales(Request $request)
{
    $usuario = UsuarioTI::findOrFail(auth()->id());
    
    $request->validate([
        'usuario' => 'required|string|max:100|unique:usuarios_ti,usuario,' . $usuario->id,
        'contrasena_actual' => 'required',
        'nueva_contrasena' => 'nullable|min:6|confirmed'
    ]);

    // Verificar contraseña actual
    if (!Hash::check($request->contrasena_actual, $usuario->contrasena)) {
        return back()->withErrors(['contrasena_actual' => 'La contraseña actual es incorrecta.']);
    }

    // Preparar datos para actualizar
    $data = ['usuario' => $request->usuario];
    
    // Actualizar contraseña si se proporcionó una nueva
    if ($request->filled('nueva_contrasena')) {
        $data['contrasena'] = Hash::make($request->nueva_contrasena);
    }

    $usuario->update($data);

    return redirect()->route('dashboard')
        ->with('success', 'Tus credenciales se actualizaron correctamente.');
    }
}