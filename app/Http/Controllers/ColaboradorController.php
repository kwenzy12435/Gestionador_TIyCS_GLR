<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ColaboradorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colaboradores = Colaborador::with('departamento')->get();
        return view('colaboradores.index', compact('colaboradores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departamentos = Departamento::all();
        return view('colaboradores.create', compact('departamentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:100|unique:colaboradores',
            'nombre' => 'required|string|max:100',
            'apellidos' => 'nullable|string|max:100',
            'puesto' => 'nullable|string|max:100',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'anydesk_id' => 'nullable|string|max:50|unique:colaboradores',
        
        ]);

        Colaborador::create([
            'usuario' => $request->usuario,
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'puesto' => $request->puesto,
            'departamento_id' => $request->departamento_id,
            'anydesk_id' => $request->anydesk_id,
        
        ]);

        return redirect()->route('colaboradores.index')
            ->with('success', 'Colaborador creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $colaborador = Colaborador::with('departamento')->findOrFail($id);
        return view('colaboradores.show', compact('colaborador'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $colaborador = Colaborador::findOrFail($id);
        $departamentos = Departamento::all();
        return view('colaboradores.edit', compact('colaborador', 'departamentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $colaborador = Colaborador::findOrFail($id);
        
        $request->validate([
            'usuario' => 'required|string|max:100|unique:colaboradores,usuario,' . $id,
            'nombre' => 'required|string|max:100',
            'apellidos' => 'nullable|string|max:100',
            'puesto' => 'nullable|string|max:100',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'anydesk_id' => 'nullable|string|max:50|unique:colaboradores,anydesk_id,' . $id,
           
        ]);

        $data = [
            'usuario' => $request->usuario,
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'puesto' => $request->puesto,
            'departamento_id' => $request->departamento_id,
            'anydesk_id' => $request->anydesk_id
        ];

        if ($request->filled('contrasena')) {
            $data['contrasena'] = Hash::make($request->contrasena);
        }

        $colaborador->update($data);

        return redirect()->route('colaboradores.index')
            ->with('success', 'Colaborador actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $colaborador = Colaborador::findOrFail($id);
            $colaborador->delete();

            return redirect()->route('colaboradores.index')
                ->with('success', 'Colaborador eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('colaboradores.index')
                ->with('error', 'Error al eliminar el colaborador: ' . $e->getMessage());
        }
    }
}