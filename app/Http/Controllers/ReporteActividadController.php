<?php

namespace App\Http\Controllers;

use App\Models\ReporteActividad;
use App\Models\Colaborador;
use App\Models\Canal;
use App\Models\Naturaleza;
use App\Models\UsuarioTI;
use Illuminate\Http\Request;

class ReporteActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            // Búsqueda usando SQL directo con LIKE para múltiples campos y relaciones
            $reportes = ReporteActividad::with(['colaborador', 'canal', 'naturaleza', 'usuarioTi'])
                ->whereRaw("
                    actividad LIKE ? OR 
                    descripcion LIKE ? OR
                    fecha LIKE ? OR
                    id IN (SELECT id FROM reporte_actividades WHERE colaborador_id IN 
                          (SELECT id FROM colaboradores WHERE nombres LIKE ? OR apellidos LIKE ?)) OR
                    id IN (SELECT id FROM reporte_actividades WHERE canal_id IN 
                          (SELECT id FROM canales WHERE nombre LIKE ?)) OR
                    id IN (SELECT id FROM reporte_actividades WHERE naturaleza_id IN 
                          (SELECT id FROM naturalezas WHERE nombre LIKE ?)) OR
                    id IN (SELECT id FROM reporte_actividades WHERE usuario_ti_id IN 
                          (SELECT id FROM usuarios_ti WHERE usuario LIKE ? OR nombres LIKE ? OR apellidos LIKE ?))
                ", array_fill(0, 12, "%$search%"))
                ->orderBy('fecha', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $reportes = ReporteActividad::with(['colaborador', 'canal', 'naturaleza', 'usuarioTi'])
                ->orderBy('fecha', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }
            
        return view('reporte_actividades.index', compact('reportes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $colaboradores = Colaborador::all();
        $canales = Canal::all();
        $naturalezas = Naturaleza::all();
        $usuariosTi = UsuarioTI::all();
        
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
            'descripcion' => 'required|string',
            'canal_id' => 'nullable|exists:canales,id',
            'naturaleza_id' => 'nullable|exists:naturalezas,id',
            'usuario_ti_id' => 'nullable|exists:usuarios_ti,id'
        ]);

        
        ReporteActividad::create($validated);

        return redirect()->route('reporte_actividades.index')
            ->with('success', 'Reporte de actividad creado exitosamente.');
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
        $colaboradores = Colaborador::all();
        $canales = Canal::all();
        $naturalezas = Naturaleza::all();
        $usuariosTi = UsuarioTI::all();
        
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
            'descripcion' => 'required|string',
            'canal_id' => 'nullable|exists:canales,id',
            'naturaleza_id' => 'nullable|exists:naturalezas,id',
            'usuario_ti_id' => 'nullable|exists:usuarios_ti,id'
        ]);

       
        $reporte->update($validated);

        return redirect()->route('reporte_actividades.index')
            ->with('success', 'Reporte de actividad actualizado exitosamente.');
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