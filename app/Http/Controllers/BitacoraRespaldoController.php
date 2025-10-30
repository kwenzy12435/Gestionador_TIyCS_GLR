<?php

namespace App\Http\Controllers;

use App\Models\BitacoraRespaldo;
use App\Models\UsuarioTi;
use Illuminate\Http\Request;

class BitacoraRespaldoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            // Búsqueda usando SQL directo con LIKE para múltiples campos y relaciones
            $bitacoras = BitacoraRespaldo::with('usuarioTi')
                ->whereRaw("
                    empresa_id LIKE ? OR
                    estado LIKE ? OR
                    ubicacion_guardado LIKE ? OR
                    acciones_alternativas LIKE ? OR
                    fecha_respaldo LIKE ? OR
                    usuario_ti_id IN (SELECT id FROM usuarios_ti WHERE usuario LIKE ? OR nombres LIKE ? OR apellidos LIKE ? OR puesto LIKE ?)
                ", array_fill(0, 9, "%$search%"))
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $bitacoras = BitacoraRespaldo::with('usuarioTi')->orderBy('created_at', 'desc')->get();
        }
        
        return view('bitacora_respaldo.index', compact('bitacoras', 'search'));
    }

    public function create()
    {
        $usuariosTi = UsuarioTi::orderBy('nombres')->get();
        return view('bitacora_respaldo.create', compact('usuariosTi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'empresa_id' => 'required|in:contabilidad,nomina',
            'usuario_ti_id' => 'required|exists:usuarios_ti,id',
            'respaldo_nominas' => 'nullable|boolean',
            'respaldo_contabilidad' => 'nullable|boolean',
            'fecha_respaldo' => 'required|date',
            'estado' => 'required|in:no hecho,Hecho',
            'ubicacion_guardado' => 'nullable|string|max:255',
            'acciones_alternativas' => 'nullable|string'
        ]);

        BitacoraRespaldo::create($validated);

        return redirect()->route('bitacora_respaldo.index')
            ->with('success', 'Registro creado exitosamente.');
    }

    public function show($id)
    {
        $bitacora = BitacoraRespaldo::with('usuarioTi')->findOrFail($id);
        return view('bitacora_respaldo.show', compact('bitacora'));
    }

    public function edit($id)
    {
        $bitacora = BitacoraRespaldo::findOrFail($id);
        $usuariosTi = UsuarioTi::orderBy('nombres')->get();
        return view('bitacora_respaldo.edit', compact('bitacora', 'usuariosTi'));
    }

    public function update(Request $request, $id)
    {
        $bitacora = BitacoraRespaldo::findOrFail($id);
        
        $validated = $request->validate([
            'empresa_id' => 'required|in:contabilidad,nomina',
            'usuario_ti_id' => 'required|exists:usuarios_ti,id',
            'respaldo_nominas' => 'nullable|boolean',
            'respaldo_contabilidad' => 'nullable|boolean',
            'fecha_respaldo' => 'required|date',
            'estado' => 'required|in:no hecho,Hecho',
            'ubicacion_guardado' => 'nullable|string|max:255',
            'acciones_alternativas' => 'nullable|string'
        ]);

        $bitacora->update($validated);

        return redirect()->route('bitacora_respaldo.index')
            ->with('success', 'Registro actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $bitacora = BitacoraRespaldo::findOrFail($id);
        $bitacora->delete();

        return redirect()->route('bitacora_respaldo.index')
            ->with('success', 'Registro eliminado exitosamente.');
    }
}