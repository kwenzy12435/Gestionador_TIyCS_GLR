<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ColaboradorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $colaboradores = Colaborador::with('departamento')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('usuario', 'LIKE', "%{$search}%")
                      ->orWhere('nombre', 'LIKE', "%{$search}%")
                      ->orWhere('apellidos', 'LIKE', "%{$search}%")
                      ->orWhere('puesto', 'LIKE', "%{$search}%")
                      ->orWhere('anydesk_id', 'LIKE', "%{$search}%")
                      ->orWhereHas('departamento', function($q) use ($search) {
                          $q->where('nombre', 'LIKE', "%{$search}%")
                            ->orWhere('descripcion', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->orderBy('nombre')
            ->orderBy('apellidos')
            ->paginate();
        
        return view('colaboradores.index', compact('colaboradores', 'search'));
    }

    public function create()
    {
        $departamentos = Departamento::orderBy('nombre')->paginate();
        return view('colaboradores.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario' => 'required|string|max:100|unique:colaboradores',
            'nombre' => 'required|string|max:100',
            'apellidos' => 'nullable|string|max:100',
            'puesto' => 'nullable|string|max:100',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'anydesk_id' => 'nullable|string|max:50|unique:colaboradores',
        ]);

        Colaborador::create($validated);

        return redirect()->route('colaboradores.index')
            ->with('success', 'Colaborador creado exitosamente.');
    }

    public function show(Colaborador $colaborador)
    {
        $colaborador->load('departamento');
        return view('colaboradores.show', compact('colaborador'));
    }

    public function edit(Colaborador $colaborador)
    {
        $departamentos = Departamento::orderBy('nombre')->paginate();
        return view('colaboradores.edit', compact('colaborador', 'departamentos'));
    }

    public function update(Request $request, Colaborador $colaborador)
    {
        $validated = $request->validate([
            'usuario' => [
                'required',
                'string',
                'max:100',
                Rule::unique('colaboradores')->ignore($colaborador->id)
            ],
            'nombre' => 'required|string|max:100',
            'apellidos' => 'nullable|string|max:100',
            'puesto' => 'nullable|string|max:100',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'anydesk_id' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('colaboradores')->ignore($colaborador->id)
            ],
        ]);

        $colaborador->update($validated);

        return redirect()->route('colaboradores.index')
            ->with('success', 'Colaborador actualizado exitosamente.');
    }

    public function destroy(Colaborador $colaborador)
    {
        try {
            $colaborador->delete();

            return redirect()->route('colaboradores.index')
                ->with('success', 'Colaborador eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('colaboradores.index')
                ->with('error', 'No se pudo eliminar el colaborador. Puede que tenga registros relacionados.');
        }
    }
}