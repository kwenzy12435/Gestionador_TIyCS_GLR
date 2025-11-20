<?php

namespace App\Http\Controllers;

use App\Models\MonitoreoRed;
use App\Models\UsuarioTI;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonitoreoRedController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $monitoreos = MonitoreoRed::with('usuarioResponsable')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('fecha', 'LIKE', "%{$search}%")
                      ->orWhere('hora', 'LIKE', "%{$search}%")
                      ->orWhere('velocidad_descarga', 'LIKE', "%{$search}%")
                      ->orWhere('velocidad_subida', 'LIKE', "%{$search}%")
                      ->orWhere('porcentaje_experiencia_wifi', 'LIKE', "%{$search}%")
                      ->orWhere('clientes_conectados', 'LIKE', "%{$search}%")
                      ->orWhere('observaciones', 'LIKE', "%{$search}%")
                      ->orWhereHas('usuarioResponsable', function($q) use ($search) {
                          $q->where('usuario', 'LIKE', "%{$search}%")
                            ->orWhere('nombres', 'LIKE', "%{$search}%")
                            ->orWhere('apellidos', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->paginate();
            
        return view('monitoreo_red.index', compact('monitoreos', 'search'));
    }

    public function create()
    {
        $usuariosTi = UsuarioTI::orderBy('nombres')->paginate();
        return view('monitoreo_red.create', compact('usuariosTi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
    [
        'fecha' => 'required|date|before_or_equal:today',
        'velocidad_descarga' => 'required|numeric|min:0|max:1000',
        'velocidad_subida' => 'required|numeric|min:0|max:1000',
        'porcentaje_experiencia_wifi' => 'required|numeric|min:0|max:100',
        'clientes_conectados' => 'required|integer|min:0|max:1000',
        'observaciones' => 'nullable|string|max:500',
        'responsable' => 'required|exists:usuarios_ti,id',
    ],
    [
        'required'          => 'El campo :attribute es obligatorio.',
        'date'              => 'El campo :attribute debe ser una fecha válida.',
        'before_or_equal'   => 'La :attribute no puede ser futura.',
        'numeric'           => 'El campo :attribute debe ser numérico.',
        'integer'           => 'El campo :attribute debe ser un número entero.',
        'min'               => 'El campo :attribute no puede ser menor a :min.',
        'max'               => 'El campo :attribute no puede ser mayor a :max.',
        'string'            => 'El campo :attribute debe ser texto.',
        'exists'            => 'El :attribute seleccionado no es válido.',
    ],
    [
        'fecha'                         => 'fecha',
        'velocidad_descarga'            => 'velocidad de descarga (Mbps)',
        'velocidad_subida'              => 'velocidad de subida (Mbps)',
        'porcentaje_experiencia_wifi'   => 'experiencia WiFi (%)',
        'clientes_conectados'           => 'clientes conectados',
        'observaciones'                 => 'observaciones',
        'responsable'                   => 'responsable',
    ]
);


        // Agregar hora automática
        $validated['hora'] = now()->setTimezone('America/Merida')->format('H:i');

        MonitoreoRed::create($validated);

        return redirect()->route('monitoreo_red.index')
            ->with('success', 'Registro de monitoreo creado exitosamente.');
    }

    public function show(MonitoreoRed $monitoreoRed)
    {
        $monitoreoRed->load('usuarioResponsable');
        return view('monitoreo_red.show', compact('monitoreoRed'));
    }

    public function edit(MonitoreoRed $monitoreoRed)
    {
        $usuariosTi = UsuarioTI::orderBy('nombres')->paginate();
        return view('monitoreo_red.edit', compact('monitoreoRed', 'usuariosTi'));
    }

    public function update(Request $request, MonitoreoRed $monitoreoRed)
    {
        $validated = $request->validate(
    [
        'fecha' => 'required|date|before_or_equal:today',
        'velocidad_descarga' => 'required|numeric|min:0|max:1000',
        'velocidad_subida' => 'required|numeric|min:0|max:1000',
        'porcentaje_experiencia_wifi' => 'required|numeric|min:0|max:100',
        'clientes_conectados' => 'required|integer|min:0|max:1000',
        'observaciones' => 'nullable|string|max:500',
        'responsable' => 'required|exists:usuarios_ti,id',
    ],
    [
        'required'          => 'El campo :attribute es obligatorio.',
        'date'              => 'El campo :attribute debe ser una fecha válida.',
        'before_or_equal'   => 'La :attribute no puede ser futura.',
        'numeric'           => 'El campo :attribute debe ser numérico.',
        'integer'           => 'El campo :attribute debe ser un número entero.',
        'min'               => 'El campo :attribute no puede ser menor a :min.',
        'max'               => 'El campo :attribute no puede ser mayor a :max.',
        'string'            => 'El campo :attribute debe ser texto.',
        'exists'            => 'El :attribute seleccionado no es válido.',
    ],
    [
        'fecha'                         => 'fecha',
        'velocidad_descarga'            => 'velocidad de descarga (Mbps)',
        'velocidad_subida'              => 'velocidad de subida (Mbps)',
        'porcentaje_experiencia_wifi'   => 'experiencia WiFi (%)',
        'clientes_conectados'           => 'clientes conectados',
        'observaciones'                 => 'observaciones',
        'responsable'                   => 'responsable',
    ]
);


        // Actualizar hora automática
        $validated['hora'] = now()->setTimezone('America/Merida')->format('H:i');

        $monitoreoRed->update($validated);

        return redirect()->route('monitoreo_red.index')
            ->with('success', 'Registro de monitoreo actualizado exitosamente.');
    }

    public function destroy(MonitoreoRed $monitoreoRed)
    {
        try {
            $monitoreoRed->delete();

            return redirect()->route('monitoreo_red.index')
                ->with('success', 'Registro de monitoreo eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('monitoreo_red.index')
                ->with('error', 'No se pudo eliminar el registro: ' . $e->getMessage());
        }
    }
}