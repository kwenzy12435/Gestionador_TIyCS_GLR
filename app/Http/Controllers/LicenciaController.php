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
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class LicenciaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $licencias = Licencia::with(['colaborador', 'plataforma'])
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('cuenta', 'LIKE', "%{$search}%")
                      ->orWhere('expiracion', 'LIKE', "%{$search}%")
                      ->orWhereHas('colaborador', function($q) use ($search) {
                          $q->where('nombre', 'LIKE', "%{$search}%") 
                            ->orWhere('apellidos', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('plataforma', function($q) use ($search) {
                          $q->where('nombre', 'LIKE', "%{$search}%")
                            ->orWhere('descripcion', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->orderBy('expiracion', 'asc')
            ->orderBy('cuenta', 'asc')
            ->paginate(15); // ✅ Agregado: paginate en lugar de get
        
        return view('licencias.index', compact('licencias', 'search'));
    }

    public function create()
    {
        $colaboradores = Colaborador::orderBy('nombre')->get(); // ✅ Corregido: 'nombre' por 'nombres'
        $plataformas = Plataforma::orderBy('nombre')->get();
        return view('licencias.create', compact('colaboradores', 'plataformas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'cuenta' => 'required|string|max:150|unique:licencias',
            'contrasena' => 'required|string|min:8|max:255',
            'plataforma_id' => 'required|exists:plataformas,id',
            'expiracion' => 'nullable|date|after_or_equal:today'
        ], [
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'plataforma_id.required' => 'La plataforma es obligatoria.',
            'expiracion.after_or_equal' => 'La fecha de expiración no puede ser anterior a hoy.'
        ]);

        // Encriptar contraseña
        $validated['contrasena'] = Crypt::encryptString($validated['contrasena']);

        try {
            Licencia::create($validated);

            return redirect()->route('licencias.index')
                ->with('success', 'Licencia creada exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la licencia: ' . $e->getMessage());
        }
    }

    public function show(Licencia $licencia)
    {
        $licencia->load(['colaborador', 'plataforma']);
        return view('licencias.show', compact('licencia'));
    }

    public function edit(Licencia $licencia)
    {
        $colaboradores = Colaborador::orderBy('nombre')->get();
        $plataformas = Plataforma::orderBy('nombre')->get();
        return view('licencias.edit', compact('licencia', 'colaboradores', 'plataformas'));
    }

    public function update(Request $request, Licencia $licencia)
    {
        $validated = $request->validate([
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'cuenta' => [
                'required',
                'string',
                'max:150',
                Rule::unique('licencias')->ignore($licencia->id)
            ],
            'contrasena' => 'nullable|string|min:8|max:255',
            'plataforma_id' => 'required|exists:plataformas,id',
            'expiracion' => 'nullable|date|after_or_equal:today'
        ], [
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'plataforma_id.required' => 'La plataforma es obligatoria.',
            'expiracion.after_or_equal' => 'La fecha de expiración no puede ser anterior a hoy.'
        ]);

        // Encriptar nueva contraseña si se proporciona
        if ($request->filled('contrasena')) {
            $validated['contrasena'] = Crypt::encryptString($validated['contrasena']);
        } else {
            // Mantener la contraseña actual
            unset($validated['contrasena']);
        }

        try {
            $licencia->update($validated);

            return redirect()->route('licencias.index')
                ->with('success', 'Licencia actualizada exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la licencia: ' . $e->getMessage());
        }
    }

    public function destroy(Licencia $licencia)
    {
        try {
            $licencia->delete();

            return redirect()->route('licencias.index')
                ->with('success', 'Licencia eliminada exitosamente.');
            
        } catch (\Exception $e) {
            return redirect()->route('licencias.index')
                ->with('error', 'No se pudo eliminar la licencia: ' . $e->getMessage());
        }
    }
    

    public function revelarContrasena(Request $request, Licencia $licencia)
    {
         $data = $request->validate([
        'password' => ['required','string','min:8'],
    ], [
        'password.required' => 'Ingresa tu contraseña.',
        'password.min'      => 'La contraseña debe tener al menos :min caracteres.',
    ]);

    if (! Hash::check($data['password'], auth()->user()->password)) {
        // NO uses ->with('error', ...). Úsalo como error de campo:
        return back()->withErrors(['password' => 'Contraseña incorrecta.'])->withInput();
    }

    $plain = null;
    if (!empty($licencia->password_encrypted)) {
        try { $plain = Crypt::decryptString($licencia->password_encrypted); } catch (\Throwable $e) {}
    }

    return view('licencias.ver-contrasena', compact('licencia','plain'));
    }

    public function verContrasena(Licencia $licencia)
    {
        $licencia->load(['plataforma']);
        return view('licencias.ver-contrasena', compact('licencia'));
    }

    public function procesarVerContrasena(Request $request, Licencia $licencia)
    {
         // 1) Validación con mensajes personalizados (adiós "validation.min.string")
    $data = $request->validate([
        'password' => ['required','string','min:8'],
    ], [
        'password.required' => 'Ingresa tu contraseña.',
        'password.string'   => 'La contraseña debe ser texto.',
        'password.min'      => 'La contraseña debe tener al menos :min caracteres.',
    ]);

    // 2) Verificar contraseña del usuario (ajusta el campo según tu tabla)
    $hashed = Auth::user()->password ?? Auth::user()->contrasena; // usa el que tengas
    if (! Hash::check($data['password'], $hashed)) {
        // Enviar como error de validación del campo (sin session('error'))
        return back()->withErrors(['password' => 'Contraseña incorrecta.'])->withInput();
    }

    // 3) Desencriptar y mostrar
    try {
        $contrasenaRevelada = Crypt::decryptString($licencia->contrasena);
    } catch (DecryptException $e) {
        return back()->withErrors(['password' => 'No fue posible revelar la contraseña.'])->withInput();
    }

    $mostrarContrasena = true;
    return view('licencias.ver-contrasena', compact('licencia','contrasenaRevelada','mostrarContrasena'));
    }

    public function confirmarPassword(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false, 
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $request->validate([
            'password' => 'required|string'
        ]);

        if (Hash::check($request->input('password'), $user->contrasena)) {
            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false, 
            'message' => 'Contraseña incorrecta'
        ], 403);
    }
    
    
}