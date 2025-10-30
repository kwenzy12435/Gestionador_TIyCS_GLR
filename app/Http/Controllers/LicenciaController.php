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

class LicenciaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            // Búsqueda usando SQL directo con LIKE para múltiples campos y relaciones
            $licencias = Licencia::with(['colaborador', 'plataforma'])
                ->whereRaw("
                    cuenta LIKE ? OR
                    expiracion LIKE ? OR
                    colaborador_id IN (SELECT id FROM colaboradores WHERE nombres LIKE ? OR apellidos LIKE ? OR email LIKE ?) OR
                    plataforma_id IN (SELECT id FROM plataformas WHERE nombre LIKE ? OR descripcion LIKE ?)
                ", array_fill(0, 7, "%$search%"))
                ->get();
        } else {
            $licencias = Licencia::with(['colaborador', 'plataforma'])->get();
        }
        
        return view('licencias.index', compact('licencias', 'search'));
    }

    public function create()
    {
        $colaboradores = Colaborador::all();
        $plataformas = Plataforma::all();
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

      
        $validated['contrasena'] = Crypt::encryptString($validated['contrasena']);

        Licencia::create($validated);

        return redirect()->route('licencias.index')->with('success','Licencia creada exitosamente.');
    }

    public function show($id)
    {
        $licencia = Licencia::with(['colaborador','plataforma'])->findOrFail($id);
        
        // Mostrar la vista normal sin contraseña visible inicialmente
        return view('licencias.show', compact('licencia'));
    }

    public function edit($id)
    {
        $licencia = Licencia::findOrFail($id);
        $colaboradores = Colaborador::all();
        $plataformas = Plataforma::all();
        return view('licencias.edit', compact('licencia','colaboradores','plataformas'));
    }

    public function update(Request $request, $id)
    {
        $licencia = Licencia::findOrFail($id);

        $validated = $request->validate([
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'cuenta' => 'required|string|max:150|unique:licencias,cuenta,' . $id,
            'contrasena' => 'nullable|string|min:8|max:255',
            'plataforma_id' => 'required|exists:plataformas,id',
            'expiracion' => 'nullable|date|after_or_equal:today'
        ], [
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'plataforma_id.required' => 'La plataforma es obligatoria.',
            'expiracion.after_or_equal' => 'La fecha de expiración no puede ser anterior a hoy.'
        ]);

      
        if ($request->filled('contrasena')) {
            $validated['contrasena'] = Crypt::encryptString($validated['contrasena']);
        } else {
            // Mantener la contraseña actual
            unset($validated['contrasena']);
        }

        $licencia->update($validated);

        return redirect()->route('licencias.index')->with('success','Licencia actualizada exitosamente.');
    }

    public function destroy($id)
    {
        try {
            $licencia = Licencia::findOrFail($id);
            $licencia->delete();

            return redirect()->route('licencias.index')->with('success','Licencia eliminada exitosamente.');
            
        } catch (\Exception $e) {
            return redirect()->route('licencias.index')
                ->with('error', 'Error al eliminar la licencia: ' . $e->getMessage());
        }
    }

   
    public function revelarContrasena(Request $request, $id)
    {
        $licencia = Licencia::findOrFail($id);
        $passwordUsuario = $request->input('password');

        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json([
                'success' => false, 
                'message' => 'Usuario no autenticado.'
            ], 401);
        }

        $usuario = Auth::user();

        // Verificar contraseña del usuario
        if (!Hash::check($passwordUsuario, $usuario->contrasena)) {
            return response()->json([
                'success' => false, 
                'message' => 'Contraseña incorrecta.'
            ], 422);
        }

        try {
            // Desencriptar la contraseña de la licencia
            $contrasenaDesencriptada = Crypt::decryptString($licencia->contrasena);
            
            return response()->json([
                'success' => true,
                'contrasena' => $contrasenaDesencriptada,
                'message' => 'Contraseña revelada correctamente.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desencriptar la contraseña de la licencia.'
            ], 500);
        }
    }

   
    public function verContrasena($id)
    {
        $licencia = Licencia::findOrFail($id);
        return view('licencias.ver_contrasena', compact('licencia'));
    }

    public function procesarVerContrasena(Request $request, $id)
    {
        $licencia = Licencia::findOrFail($id);
        $passwordUsuario = $request->input('password');

        // Verificar contraseña del usuario
        if (!Hash::check($passwordUsuario, Auth::user()->contrasena)) {
            return redirect()->back()
                ->with('error', 'Contraseña incorrecta.')
                ->withInput();
        }

        try {
            $contrasenaDesencriptada = Crypt::decryptString($licencia->contrasena);
            
            // Mostrar la contraseña en la misma vista
            return view('licencias.ver_contrasena', [
                'licencia' => $licencia,
                'contrasenaRevelada' => $contrasenaDesencriptada,
                'mostrarContrasena' => true
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al desencriptar la contraseña.');
        }
    }

    public function confirmarPassword(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuario no autenticado'], 401);
        }

        $request->validate([
            'password' => 'required|string'
        ]);

        if (Hash::check($request->input('password'), $user->contrasena)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Contraseña incorrecta'], 403);
    }
    
    public function licenciasPorExpiar(Request $request)
    {
        $search = $request->get('search');
        $fechaLimite = Carbon::now()->addDays(30);
        
        $query = Licencia::with(['colaborador', 'plataforma'])
            ->whereNotNull('expiracion')
            ->where('expiracion', '<=', $fechaLimite)
            ->where('expiracion', '>=', Carbon::today());

        if ($search) {
            // Aplicar búsqueda también para licencias por expirar
            $query->whereRaw("
                cuenta LIKE ? OR
                expiracion LIKE ? OR
                colaborador_id IN (SELECT id FROM colaboradores WHERE nombres LIKE ? OR apellidos LIKE ? OR email LIKE ?) OR
                plataforma_id IN (SELECT id FROM plataformas WHERE nombre LIKE ? OR descripcion LIKE ?)
            ", array_fill(0, 7, "%$search%"));
        }

        $licencias = $query->orderBy('expiracion')->get();

        return view('licencias.por_expiar', compact('licencias', 'search'));
    }
}