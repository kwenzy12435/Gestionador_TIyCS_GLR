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
    public function index(Request $request)
    {
        $search = $request->get('search');

        $reportes = ReporteActividad::with(['colaborador', 'canal', 'naturaleza', 'usuarioTi'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('actividad', 'LIKE', "%{$search}%")
                      ->orWhere('fecha', 'LIKE', "%{$search}%")
                      // Nota: 'descripcion' ya no se lista; sí se puede buscar si quieres:
                      ->orWhere('descripcion', 'LIKE', "%{$search}%")
                      ->orWhereHas('colaborador', function ($q) use ($search) {
                          $q->where('nombre', 'LIKE', "%{$search}%")
                            ->orWhere('apellidos', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('canal', function ($q) use ($search) {
                          $q->where('nombre', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('naturaleza', function ($q) use ($search) {
                          $q->where('nombre', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('usuarioTi', function ($q) use ($search) {
                          $q->where('usuario', 'LIKE', "%{$search}%")
                            ->orWhere('nombres', 'LIKE', "%{$search}%")
                            ->orWhere('apellidos', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('reporte_actividades.index', compact('reportes', 'search'));
    }

    public function create()
    {
        $colaboradores = Colaborador::orderBy('nombre')->orderBy('apellidos')->get();
        $canales       = Canal::orderBy('nombre')->get();
        $naturalezas   = Naturaleza::orderBy('nombre')->get();
        $usuariosTi    = UsuarioTI::orderBy('usuario')->get();

        return view('reporte_actividades.create', compact('colaboradores', 'canales', 'naturalezas', 'usuariosTi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
    [
        'fecha'           => 'required|date',
        'colaborador_id'  => 'required|exists:colaboradores,id',
        'actividad'       => 'required|string|max:255',
        'descripcion'     => 'required|string|min:10',
        'canal_id'        => 'required|exists:canales,id',
        'naturaleza_id'   => 'required|exists:naturalezas,id',
        'usuario_ti_id'   => 'required|exists:usuarios_ti,id',
    ],
    [
        'required' => 'El campo :attribute es obligatorio.',
        'date'     => 'El campo :attribute debe ser una fecha válida.',
        'string'   => 'El campo :attribute debe ser texto.',
        'max'      => 'El campo :attribute no puede exceder :max caracteres.',
        'min'      => 'El campo :attribute debe tener al menos :min caracteres.',
        'exists'   => 'El :attribute seleccionado no es válido.',
    ],
    [
        'fecha'           => 'fecha',
        'colaborador_id'  => 'colaborador',
        'actividad'       => 'actividad',
        'descripcion'     => 'descripción',
        'canal_id'        => 'canal',
        'naturaleza_id'   => 'naturaleza',
        'usuario_ti_id'   => 'usuario TI',
    ]
);


        ReporteActividad::create($validated);

        return redirect()->route('reporte_actividades.index')
            ->with('success', 'Reporte de actividad creado exitosamente.');
    }

    public function show($id)
    {
        $reporte = ReporteActividad::with(['colaborador', 'canal', 'naturaleza', 'usuarioTi'])
            ->findOrFail($id);

        return view('reporte_actividades.show', compact('reporte'));
    }

    public function edit($id)
    {
        $reporte       = ReporteActividad::findOrFail($id);
        $colaboradores = Colaborador::orderBy('nombre')->orderBy('apellidos')->get();
        $canales       = Canal::orderBy('nombre')->get();
        $naturalezas   = Naturaleza::orderBy('nombre')->get();
        $usuariosTi    = UsuarioTI::orderBy('usuario')->get();

        return view('reporte_actividades.edit', compact('reporte', 'colaboradores', 'canales', 'naturalezas', 'usuariosTi'));
    }

    public function update(Request $request, $id)
    {
        $reporte = ReporteActividad::findOrFail($id);

      $validated = $request->validate(
    [
        'fecha'           => 'required|date',
        'colaborador_id'  => 'required|exists:colaboradores,id',
        'actividad'       => 'required|string|max:255',
        'descripcion'     => 'required|string|min:10',
        'canal_id'        => 'required|exists:canales,id',
        'naturaleza_id'   => 'required|exists:naturalezas,id',
        'usuario_ti_id'   => 'required|exists:usuarios_ti,id',
    ],
    [
        'required' => 'El campo :attribute es obligatorio.',
        'date'     => 'El campo :attribute debe ser una fecha válida.',
        'string'   => 'El campo :attribute debe ser texto.',
        'max'      => 'El campo :attribute no puede exceder :max caracteres.',
        'min'      => 'El campo :attribute debe tener al menos :min caracteres.',
        'exists'   => 'El :attribute seleccionado no es válido.',
    ],
    [
        'fecha'           => 'fecha',
        'colaborador_id'  => 'colaborador',
        'actividad'       => 'actividad',
        'descripcion'     => 'descripción',
        'canal_id'        => 'canal',
        'naturaleza_id'   => 'naturaleza',
        'usuario_ti_id'   => 'usuario TI',
    ]
);


        $reporte->update($validated);

        return redirect()->route('reporte_actividades.index')
            ->with('success', 'Reporte de actividad actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $reporte = ReporteActividad::findOrFail($id);
        $reporte->delete();

        return redirect()->route('reporte_actividades.index')
            ->with('success', 'Reporte de actividad eliminado exitosamente.');
    }
}
