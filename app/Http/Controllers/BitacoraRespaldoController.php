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
        
        $bitacoras = BitacoraRespaldo::with('usuarioTi')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('empresa_id', 'LIKE', "%{$search}%")
                      ->orWhere('estado', 'LIKE', "%{$search}%")
                      ->orWhere('ubicacion_guardado', 'LIKE', "%{$search}%")
                      ->orWhere('acciones_alternativas', 'LIKE', "%{$search}%")
                      ->orWhere('fecha_respaldo', 'LIKE', "%{$search}%")
                      ->orWhereHas('usuarioTi', function($q) use ($search) {
                          $q->where('usuario', 'LIKE', "%{$search}%")
                            ->orWhere('nombres', 'LIKE', "%{$search}%")
                            ->orWhere('apellidos', 'LIKE', "%{$search}%")
                            ->orWhere('puesto', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->orderBy('fecha_respaldo', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate();
        
        return view('bitacora-respaldo.index', compact('bitacoras', 'search'));
    }

    public function create()
    {
        $usuariosTi = UsuarioTi::orderBy('nombres')->paginate();
        return view('bitacora-respaldo.create', compact('usuariosTi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'empresa_id' => 'required|in:contabilidad,nomina',
            'usuario_ti_id' => 'required|exists:usuarios_ti,id',
            'respaldo_nominas' => 'sometimes|boolean',
            'respaldo_contabilidad' => 'sometimes|boolean',
            'fecha_respaldo' => 'required|date',
            'estado' => 'required|in:no hecho,Hecho',
            'ubicacion_guardado' => 'nullable|string|max:255',
            'acciones_alternativas' => 'nullable|string'
        ]);

        // Asegurar valores booleanos
        $validated['respaldo_nominas'] = $request->boolean('respaldo_nominas');
        $validated['respaldo_contabilidad'] = $request->boolean('respaldo_contabilidad');

        BitacoraRespaldo::create($validated);

        return redirect()->route('bitacora-respaldo.index')
            ->with('success', 'Registro de respaldo creado exitosamente.');
    }

    public function show(BitacoraRespaldo $bitacoraRespaldo)
    {
        $bitacoraRespaldo->load('usuarioTi');
        return view('bitacora-respaldo.show', compact('bitacoraRespaldo'));
    }

    public function edit(BitacoraRespaldo $bitacoraRespaldo)
    {
        $usuariosTi = UsuarioTi::orderBy('nombres')->paginate();
        return view('bitacora-respaldo.edit', compact('bitacoraRespaldo', 'usuariosTi'));
    }

    public function update(Request $request, BitacoraRespaldo $bitacoraRespaldo)
    {
        $validated = $request->validate([
            'empresa_id' => 'required|in:contabilidad,nomina',
            'usuario_ti_id' => 'required|exists:usuarios_ti,id',
            'respaldo_nominas' => 'sometimes|boolean',
            'respaldo_contabilidad' => 'sometimes|boolean',
            'fecha_respaldo' => 'required|date',
            'estado' => 'required|in:no hecho,Hecho',
            'ubicacion_guardado' => 'nullable|string|max:255',
            'acciones_alternativas' => 'nullable|string'
        ]);

        // Asegurar valores booleanos
        $validated['respaldo_nominas'] = $request->boolean('respaldo_nominas');
        $validated['respaldo_contabilidad'] = $request->boolean('respaldo_contabilidad');

        $bitacoraRespaldo->update($validated);

        return redirect()->route('bitacora-respaldo.index')
            ->with('success', 'Registro de respaldo actualizado exitosamente.');
    }

    public function destroy(BitacoraRespaldo $bitacoraRespaldo)
    {
        $bitacoraRespaldo->delete();

        return redirect()->route('bitacora-respaldo.index')
            ->with('success', 'Registro de respaldo eliminado exitosamente.');
    }
}