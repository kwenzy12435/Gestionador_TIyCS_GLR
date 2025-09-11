<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\Colaborador;
use App\Models\Plataforma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LicenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $licencias = Licencia::with(['colaborador', 'plataforma'])->get();
        return view('licencias.index', compact('licencias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $colaboradores = Colaborador::all();
        $plataformas = Plataforma::all();
        return view('licencias.create', compact('colaboradores', 'plataformas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'cuenta' => 'required|string|max:150|unique:licencias',
            'contrasena' => 'required|string|min:6',
            'plataforma_id' => 'nullable|exists:plataformas,id',
            'expiracion' => 'nullable|date'
        ]);

        Licencia::create([
            'colaborador_id' => $request->colaborador_id,
            'cuenta' => $request->cuenta,
            'contrasena' => $request->contrasena, // Se almacena en texto plano según tu estructura
            'plataforma_id' => $request->plataforma_id,
            'expiracion' => $request->expiracion
        ]);

        return redirect()->route('licencias.index')
            ->with('success', 'Licencia creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $licencia = Licencia::with(['colaborador', 'plataforma'])->findOrFail($id);
        return view('licencias.show', compact('licencia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $licencia = Licencia::findOrFail($id);
        $colaboradores = Colaborador::all();
        $plataformas = Plataforma::all();
        return view('licencias.edit', compact('licencia', 'colaboradores', 'plataformas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $licencia = Licencia::findOrFail($id);
        
        $request->validate([
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'cuenta' => 'required|string|max:150|unique:licencias,cuenta,' . $id,
            'contrasena' => 'nullable|string|min:6',
            'plataforma_id' => 'nullable|exists:plataformas,id',
            'expiracion' => 'nullable|date'
        ]);

        $data = [
            'colaborador_id' => $request->colaborador_id,
            'cuenta' => $request->cuenta,
            'plataforma_id' => $request->plataforma_id,
            'expiracion' => $request->expiracion
        ];

        if ($request->filled('contrasena')) {
            $data['contrasena'] = $request->contrasena;
        }

        $licencia->update($data);

        return redirect()->route('licencias.index')
            ->with('success', 'Licencia actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $licencia = Licencia::findOrFail($id);
            $licencia->delete();

            return redirect()->route('licencias.index')
                ->with('success', 'Licencia eliminada exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('licencias.index')
                ->with('error', 'Error al eliminar la licencia: ' . $e->getMessage());
        }
    }
}