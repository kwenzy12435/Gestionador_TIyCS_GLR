<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\Colaborador;
use App\Models\Plataforma;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Validation\Rule;

class LicenciaController extends Controller
{
    /* ================= helpers de validación ================= */
    private function messages(): array
    {
        return [
            'required'                => 'El campo :attribute es obligatorio.',
            'string'                  => 'El campo :attribute debe ser texto.',
            'max'                     => 'El campo :attribute no debe exceder :max caracteres.',
            'min'                     => 'El campo :attribute debe tener al menos :min caracteres.',
            'unique'                  => 'Este :attribute ya está registrado.',
            'exists'                  => 'El :attribute seleccionado no existe.',
            'date'                    => 'El campo :attribute debe ser una fecha válida.',
            'after_or_equal'          => 'La :attribute no puede ser anterior a hoy.',
        ];
    }

    private function attributes(): array
    {
        return [
            'colaborador_id' => 'colaborador',
            'cuenta'         => 'cuenta',
            'contrasena'     => 'contraseña',
            'plataforma_id'  => 'plataforma',
            'expiracion'     => 'fecha de expiración',
            'password'       => 'contraseña',
        ];
    }

    /* ============================ CRUD ============================ */

    public function index(Request $request)
    {
        $search = $request->get('search');

        $licencias = Licencia::with(['colaborador','plataforma'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('cuenta','LIKE',"%{$search}%")
                      ->orWhere('expiracion','LIKE',"%{$search}%")
                      ->orWhereHas('colaborador', function ($q) use ($search) {
                          $q->where('nombre','LIKE',"%{$search}%")
                            ->orWhere('apellidos','LIKE',"%{$search}%")
                            ->orWhere('email','LIKE',"%{$search}%");
                      })
                      ->orWhereHas('plataforma', function ($q) use ($search) {
                          $q->where('nombre','LIKE',"%{$search}%")
                            ->orWhere('descripcion','LIKE',"%{$search}%");
                      });
                });
            })
            ->orderBy('expiracion','asc')
            ->orderBy('cuenta','asc')
            ->paginate(15);

        return view('licencias.index', compact('licencias','search'));
    }

    public function create()
    {
        $colaboradores = Colaborador::orderBy('nombre')->get();
        $plataformas   = Plataforma::orderBy('nombre')->get();
        return view('licencias.create', compact('colaboradores','plataformas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'cuenta'         => 'required|string|max:150|unique:licencias,cuenta',
            'contrasena'     => 'required|string|min:8|max:255',
            'plataforma_id'  => 'required|exists:plataformas,id',
            'expiracion'     => 'nullable|date|after_or_equal:today',
        ], $this->messages(), $this->attributes());

        // Encriptar contraseña
        $validated['contrasena'] = Crypt::encryptString($validated['contrasena']);

        try {
            Licencia::create($validated);
            return redirect()->route('licencias.index')->with('success','Licencia creada exitosamente.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error','Error al crear la licencia: '.$e->getMessage());
        }
    }

    public function show(Licencia $licencia)
    {
        $licencia->load(['colaborador','plataforma']);
        return view('licencias.show', compact('licencia'));
    }

    public function edit(Licencia $licencia)
    {
        $colaboradores = Colaborador::orderBy('nombre')->get();
        $plataformas   = Plataforma::orderBy('nombre')->get();
        return view('licencias.edit', compact('licencia','colaboradores','plataformas'));
    }

    public function update(Request $request, Licencia $licencia)
    {
        $validated = $request->validate([
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'cuenta'         => ['required','string','max:150', Rule::unique('licencias','cuenta')->ignore($licencia->id)],
            'contrasena'     => 'nullable|string|min:8|max:255',
            'plataforma_id'  => 'required|exists:plataformas,id',
            'expiracion'     => 'nullable|date|after_or_equal:today',
        ], $this->messages(), $this->attributes());

        if ($request->filled('contrasena')) {
            $validated['contrasena'] = Crypt::encryptString($validated['contrasena']);
        } else {
            unset($validated['contrasena']);
        }

        try {
            $licencia->update($validated);
            return redirect()->route('licencias.index')->with('success','Licencia actualizada exitosamente.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error','Error al actualizar la licencia: '.$e->getMessage());
        }
    }

    public function destroy(Licencia $licencia)
    {
        try {
            $licencia->delete();
            return redirect()->route('licencias.index')->with('success','Licencia eliminada exitosamente.');
        } catch (\Throwable $e) {
            return redirect()->route('licencias.index')->with('error','No se pudo eliminar la licencia: '.$e->getMessage());
        }
    }

    /* ============== Ver/Confirmar contraseña de la licencia ============== */

    /** Muestra el formulario para introducir la contraseña del usuario y revelar la de la licencia */
    public function verContrasena(Licencia $licencia)
    {
        $licencia->load('plataforma');
        return view('licencias.ver-contrasena', compact('licencia'));
    }

    /** Procesa la verificación y muestra la contraseña desencriptada */
    public function procesarVerContrasena(Request $request, Licencia $licencia)
    {
        $data = $request->validate([
            'password' => ['required','string','min:8'],
        ], $this->messages(), $this->attributes());

        $hashed = Auth::user()->password; // usa el hash del usuario autenticado
        if (! Hash::check($data['password'], $hashed)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.'])->withInput();
        }

        try {
            $contrasenaRevelada = Crypt::decryptString($licencia->contrasena);
        } catch (DecryptException $e) {
            return back()->withErrors(['password' => 'No fue posible revelar la contraseña.'])->withInput();
        }

        $mostrarContrasena = true;
        return view('licencias.ver-contrasena', compact('licencia','contrasenaRevelada','mostrarContrasena'));
    }

    /** Endpoint JSON para confirmar contraseña del usuario (si usas AJAX) */
    public function confirmarPassword(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Usuario no autenticado'], 401);
        }

        $request->validate(['password' => 'required|string'], $this->messages(), $this->attributes());

        if (Hash::check($request->input('password'), $user->password)) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Contraseña incorrecta'], 403);
    }
}
