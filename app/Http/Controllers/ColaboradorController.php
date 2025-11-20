<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ColaboradorController extends Controller
{
    /* ---------- Reglas/Mensajes/Atributos ---------- */
    protected function rules(?int $ignoreId = null): array
    {
        return [
            'usuario' => [
                'required','string','max:100',
                Rule::unique('colaboradores','usuario')->ignore($ignoreId),
            ],
            'nombre'  => ['required','string','max:100'],
            'apellidos' => ['nullable','string','max:100'],
            'puesto'    => ['nullable','string','max:100'],
            'departamento_id' => ['nullable','exists:departamentos,id'],
            'anydesk_id' => [
                'nullable','string','max:50',
                Rule::unique('colaboradores','anydesk_id')->ignore($ignoreId),
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'required'   => 'El campo :attribute es obligatorio.',
            'string'     => 'El campo :attribute debe ser texto.',
            'max.string' => 'El campo :attribute no debe exceder :max caracteres.',
            'unique'     => 'El :attribute ya está en uso.',
            'exists'     => 'El :attribute seleccionado no existe.',
        ];
    }

    protected function attributes(): array
    {
        return [
            'usuario'         => 'usuario',
            'nombre'          => 'nombre',
            'apellidos'       => 'apellidos',
            'puesto'          => 'puesto',
            'departamento_id' => 'departamento',
            'anydesk_id'      => 'AnyDesk ID',
        ];
    }

    /* ----------------- Acciones ----------------- */

    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));

        $colaboradores = Colaborador::with('departamento')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('usuario', 'LIKE', "%{$search}%")
                      ->orWhere('nombre', 'LIKE', "%{$search}%")
                      ->orWhere('apellidos', 'LIKE', "%{$search}%")
                      ->orWhere('puesto', 'LIKE', "%{$search}%")
                      ->orWhere('anydesk_id', 'LIKE', "%{$search}%")
                      ->orWhereHas('departamento', function ($qq) use ($search) {
                          $qq->where('nombre', 'LIKE', "%{$search}%")
                             ->orWhere('descripcion', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->orderBy('nombre')->orderBy('apellidos')
            ->paginate(15)
            ->withQueryString();

        return view('colaboradores.index', compact('colaboradores', 'search'));
    }

    public function create()
    {
        $departamentos = Departamento::orderBy('nombre')->get(); // <- sin paginar
        return view('colaboradores.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

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
        $departamentos = Departamento::orderBy('nombre')->get(); // <- sin paginar
        return view('colaboradores.edit', compact('colaborador', 'departamentos'));
    }

    public function update(Request $request, Colaborador $colaborador)
    {
        $validated = $request->validate(
            $this->rules($colaborador->id),
            $this->messages(),
            $this->attributes()
        );

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
