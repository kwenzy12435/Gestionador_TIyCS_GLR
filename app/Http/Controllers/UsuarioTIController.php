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

        $usuarios = UsuarioTI::when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
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
            ->paginate(15);

        return view('usuarios-ti.index', compact('usuarios', 'search'));
    }

    public function create()
    {
        $roles = ['ADMIN', 'AUXILIAR-TI', 'PERSONAL-TI'];
        return view('usuarios-ti.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
    [
        'usuario'     => 'required|string|max:100|unique:usuarios_ti,usuario',
        'nombres'     => 'required|string|max:100',
        'apellidos'   => 'nullable|string|max:100',
        'puesto'      => 'nullable|string|max:100',
        'telefono'    => 'nullable|string|max:20',
        'rol'         => ['required', Rule::in(['ADMIN', 'AUXILIAR-TI', 'PERSONAL-TI'])],
        'contrasena'  => ['required','string','min:8','confirmed','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
    ],
    [
        'required'   => 'El campo :attribute es obligatorio.',
        'string'     => 'El campo :attribute debe ser texto.',
        'max'        => 'El campo :attribute no puede exceder :max caracteres.',
        'min'        => 'El campo :attribute debe tener al menos :min caracteres.',
        'confirmed'  => 'La confirmación de :attribute no coincide.',
        'unique'     => 'El :attribute ya está en uso.',
        'in'         => 'El :attribute seleccionado no es válido.',
        'regex'      => 'El campo :attribute debe incluir al menos una minúscula, una mayúscula y un número.',
    ],
    [
        'usuario'                     => 'usuario',
        'nombres'                     => 'nombres',
        'apellidos'                   => 'apellidos',
        'puesto'                      => 'puesto',
        'telefono'                    => 'teléfono',
        'rol'                         => 'rol',
        'contrasena'                  => 'contraseña',
        'contrasena_confirmation'     => 'confirmación de contraseña',
    ]
);


        UsuarioTI::create([
            'usuario' => $validated['usuario'],
            'nombres' => $validated['nombres'],
            'apellidos' => $validated['apellidos'] ?? null,
            'puesto' => $validated['puesto'] ?? null,
            'telefono' => $validated['telefono'] ?? null,
            'rol' => $validated['rol'],
            'contrasena' => Hash::make($validated['contrasena']),
        ]);

        return redirect()->route('usuarios-ti.index')->with('success', 'Usuario TI creado exitosamente.');
    }

    public function show(UsuarioTI $usuarioTi)
    {
        $usuario = $usuarioTi;
        return view('usuarios-ti.show', compact('usuario'));
    }

    public function edit(UsuarioTI $usuarioTi)
    {
        $roles = ['ADMIN', 'AUXILIAR-TI', 'PERSONAL-TI'];
        $usuario = $usuarioTi;
        return view('usuarios-ti.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, UsuarioTI $usuarioTi)
    {
        $validated = $request->validate(
    [
        'usuario'     => ['required','string','max:100', Rule::unique('usuarios_ti','usuario')->ignore($usuarioTi->id)],
        'nombres'     => 'required|string|max:100',
        'apellidos'   => 'nullable|string|max:100',
        'puesto'      => 'nullable|string|max:100',
        'telefono'    => 'nullable|string|max:20',
        'rol'         => ['required', Rule::in(['ADMIN', 'AUXILIAR-TI', 'PERSONAL-TI'])],
        'contrasena'  => ['nullable','string','min:8','confirmed','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
    ],
    [
        'required'   => 'El campo :attribute es obligatorio.',
        'string'     => 'El campo :attribute debe ser texto.',
        'max'        => 'El campo :attribute no puede exceder :max caracteres.',
        'min'        => 'El campo :attribute debe tener al menos :min caracteres.',
        'confirmed'  => 'La confirmación de :attribute no coincide.',
        'unique'     => 'El :attribute ya está en uso.',
        'in'         => 'El :attribute seleccionado no es válido.',
        'regex'      => 'El campo :attribute debe incluir al menos una minúscula, una mayúscula y un número.',
    ],
    [
        'usuario'                     => 'usuario',
        'nombres'                     => 'nombres',
        'apellidos'                   => 'apellidos',
        'puesto'                      => 'puesto',
        'telefono'                    => 'teléfono',
        'rol'                         => 'rol',
        'contrasena'                  => 'contraseña',
        'contrasena_confirmation'     => 'confirmación de contraseña',
    ]
);


        $data = [
            'usuario' => $validated['usuario'],
            'nombres' => $validated['nombres'],
            'apellidos' => $validated['apellidos'] ?? null,
            'puesto' => $validated['puesto'] ?? null,
            'telefono' => $validated['telefono'] ?? null,
            'rol' => $validated['rol'],
        ];

        if ($request->filled('contrasena')) {
            $data['contrasena'] = Hash::make($validated['contrasena']);
        }

        $usuarioTi->update($data);

        return redirect()->route('usuarios-ti.index')->with('success', 'Usuario TI actualizado exitosamente.');
    }

    public function destroy(UsuarioTI $usuarioTi)
    {
        if ($usuarioTi->id === auth()->id()) {
            return redirect()->route('usuarios-ti.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        if (method_exists($usuarioTi, 'reportesActividades') && $usuarioTi->reportesActividades()->exists()) {
            return redirect()->route('usuarios-ti.index')->with('error', 'No se puede eliminar el usuario porque tiene reportes de actividad asociados.');
        }

        $usuarioTi->delete();

        return redirect()->route('usuarios-ti.index')->with('success', 'Usuario TI eliminado exitosamente.');
    }

    public function actualizarCredenciales(Request $request)
    {
        $usuario = auth()->user();

        $validated = $request->validate([
            'usuario' => ['required', 'string', 'max:100', Rule::unique('usuarios_ti', 'usuario')->ignore($usuario->id)],
            'contrasena_actual' => 'required',
            'nueva_contrasena' => ['nullable', 'min:8', 'confirmed', 'different:contrasena_actual', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'nueva_contrasena.regex' => 'La nueva contraseña debe contener al menos una letra minúscula, una letra mayúscula y un número.',
        ]);

        if (!Hash::check($validated['contrasena_actual'], $usuario->contrasena)) {
            return back()->withErrors(['contrasena_actual' => 'La contraseña actual es incorrecta.']);
        }

        $data = ['usuario' => $validated['usuario']];

        if ($request->filled('nueva_contrasena')) {
            $data['contrasena'] = Hash::make($validated['nueva_contrasena']);
        }

        $usuario->update($data);

        return redirect()->route('dashboard')->with('success', 'Tus credenciales se actualizaron correctamente.');
    }
}
