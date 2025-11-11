<?php

namespace App\Http\Controllers;

use App\Models\ReporteActividad;
use App\Models\Colaborador;
use App\Models\Canal;
use App\Models\Naturaleza;
use App\Models\UsuarioTI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $reportes = ReporteActividad::with(['colaborador', 'canal', 'naturaleza', 'usuarioTi'])
            ->when($search, function($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('actividad', 'LIKE', "%{$search}%")
                      ->orWhere('descripcion', 'LIKE', "%{$search}%")
                      ->orWhere('fecha', 'LIKE', "%{$search}%")
                      ->orWhereHas('colaborador', function($q) use ($search) {
                          $q->where('nombres', 'LIKE', "%{$search}%")
                            ->orWhere('apellidos', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('canal', function($q) use ($search) {
                          $q->where('nombre', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('naturaleza', function($q) use ($search) {
                          $q->where('nombre', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('usuarioTi', function($q) use ($search) {
                          $q->where('usuario', 'LIKE', "%{$search}%")
                            ->orWhere('nombres', 'LIKE', "%{$search}%")
                            ->orWhere('apellidos', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15); // ✅ Mejor: paginación en lugar de get()
            
        return view('reporte_actividades.index', compact('reportes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $colaboradores = Colaborador::orderBy('nombres')->get();
        $canales = Canal::orderBy('nombre')->get();
        $naturalezas = Naturaleza::orderBy('nombre')->get();
        $usuariosTi = UsuarioTI::orderBy('usuario')->get();
        
        return view('reporte_actividades.create', compact('colaboradores', 'canales', 'naturalezas', 'usuariosTi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'actividad' => 'required|string|max:255',
            'descripcion' => 'required|string|min:10',
            'canal_id' => 'nullable|exists:canales,id',
            'naturaleza_id' => 'nullable|exists:naturalezas,id',
            'usuario_ti_id' => 'nullable|exists:usuarios_ti,id'
        ]);

        try {
            ReporteActividad::create($validated);

            return redirect()->route('reporte_actividades.index')
                ->with('success', 'Reporte de actividad creado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el reporte: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reporte = ReporteActividad::with(['colaborador', 'canal', 'naturaleza', 'usuarioTi'])
            ->findOrFail($id);
            
        return view('reporte_actividades.show', compact('reporte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reporte = ReporteActividad::findOrFail($id);
        $colaboradores = Colaborador::orderBy('nombres')->get();
        $canales = Canal::orderBy('nombre')->get();
        $naturalezas = Naturaleza::orderBy('nombre')->get();
        $usuariosTi = UsuarioTI::orderBy('usuario')->get();
        
        return view('reporte_actividades.edit', compact('reporte', 'colaboradores', 'canales', 'naturalezas', 'usuariosTi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $reporte = ReporteActividad::findOrFail($id);
        
        $validated = $request->validate([
            'fecha' => 'required|date',
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'actividad' => 'required|string|max:255',
            'descripcion' => 'required|string|min:10',
            'canal_id' => 'nullable|exists:canales,id',
            'naturaleza_id' => 'nullable|exists:naturalezas,id',
            'usuario_ti_id' => 'nullable|exists:usuarios_ti,id'
        ]);

        try {
            $reporte->update($validated);

            return redirect()->route('reporte_actividades.index')
                ->with('success', 'Reporte de actividad actualizado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el reporte: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $reporte = ReporteActividad::findOrFail($id);
            $reporte->delete();

            return redirect()->route('reporte_actividades.index')
                ->with('success', 'Reporte de actividad eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('reporte_actividades.index')
                ->with('error', 'Error al eliminar el reporte: ' . $e->getMessage());
        }
    }
}