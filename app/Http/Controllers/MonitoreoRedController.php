<?php

namespace App\Http\Controllers;

use App\Models\MonitoreoRed;
use App\Models\UsuarioTI;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonitoreoRedController extends Controller
{
    public function index()
    {
        $monitoreos = MonitoreoRed::with('usuarioResponsable')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();
            
        return view('MonitoreoRed.index', compact('monitoreos'));
    }

    public function create()
    {
        
        $usuariosTi = UsuarioTI::orderBy('nombres')->get();
        return view('MonitoreoRed.create', compact('usuariosTi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date|before_or_equal:today', 
            'velocidad_descarga' => 'required|numeric|min:0|max:1000', 
            'velocidad_subida' => 'required|numeric|min:0|max:1000',  
            'porcentaje_experiencia_wifi' => 'required|numeric|min:0|max:100',
            'clientes_conectados' => 'required|integer|min:0|max:1000',
            'observaciones' => 'nullable|string|max:500', 
            'responsable' => 'required|exists:usuarios_ti,id'
        ], [
            'fecha.before_or_equal' => 'La fecha no puede ser futura.', 
            'hora.date_format' => 'El formato de hora debe ser HH:MM (24 horas).',
            'velocidad_descarga.max' => 'La velocidad de descarga no puede ser mayor a 1000 Mbps.',
            'velocidad_subida.max' => 'La velocidad de subida no puede ser mayor a 1000 Mbps.',
            'clientes_conectados.max' => 'El número de clientes conectados no puede ser mayor a 1000.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 500 caracteres.'
        ]);

       
          $validated['hora'] = now()->setTimezone('America/Merida')->format('H:i');

        MonitoreoRed::create($validated);

        return redirect()->route('monitoreo-red.index')
            ->with('success', 'Registro de monitoreo creado exitosamente.');
    }

    public function show($id)
    {
        $monitoreo = MonitoreoRed::with('usuarioResponsable')->findOrFail($id);
        return view('MonitoreoRed.show', compact('monitoreo'));
    }

    public function edit($id)
    {
        $monitoreo = MonitoreoRed::findOrFail($id);
       
        $usuariosTi = UsuarioTI::orderBy('nombres')->get();
        return view('MonitoreoRed.edit', compact('monitoreo', 'usuariosTi'));
    }

    public function update(Request $request, $id)
    {
        $monitoreo = MonitoreoRed::findOrFail($id);
        
        $validated = $request->validate([
            'fecha' => 'required|date|before_or_equal:today', 
            
            'velocidad_descarga' => 'required|numeric|min:0|max:1000',
            'velocidad_subida' => 'required|numeric|min:0|max:1000',
            'porcentaje_experiencia_wifi' => 'required|numeric|min:0|max:100',
            'clientes_conectados' => 'required|integer|min:0|max:1000',
            'observaciones' => 'nullable|string|max:500',
            'responsable' => 'required|exists:usuarios_ti,id'
        ], [
            'fecha.before_or_equal' => 'La fecha no puede ser futura.',
            'hora.date_format' => 'El formato de hora debe ser HH:MM (24 horas).',
            'velocidad_descarga.max' => 'La velocidad de descarga no puede ser mayor a 1000 Mbps.',
            'velocidad_subida.max' => 'La velocidad de subida no puede ser mayor a 1000 Mbps.',
            'clientes_conectados.max' => 'El número de clientes conectados no puede ser mayor a 1000.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 500 caracteres.'
        ]);

      
          $validated['hora'] = now()->setTimezone('America/Merida')->format('H:i');
        $monitoreo->update($validated);

        return redirect()->route('monitoreo-red.index')
            ->with('success', 'Registro de monitoreo actualizado exitosamente.');
    }

    public function destroy($id)
    {
        try {
            $monitoreo = MonitoreoRed::findOrFail($id);
            $monitoreo->delete();

            return redirect()->route('monitoreo-red.index')
                ->with('success', 'Registro de monitoreo eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('monitoreo-red.index')
                ->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }
}