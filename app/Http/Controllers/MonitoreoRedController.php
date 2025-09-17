<?php

namespace App\Http\Controllers;

use App\Models\MonitoreoRed;
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
        $usuariosTi = \DB::table('usuarios_ti')->orderBy('nombres')->get();
        return view('MonitoreoRed.create', compact('usuariosTi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'velocidad_descarga' => 'required|numeric|min:0',
            'velocidad_subida' => 'required|numeric|min:0',
            'porcentaje_experiencia_wifi' => 'required|numeric|min:0|max:100',
            'clientes_conectados' => 'required|integer|min:0',
            'observaciones' => 'nullable|string',
            'responsable' => 'required|exists:usuarios_ti,id'
        ]);

    $validated['hora'] = Carbon::now()->format('H:i:s'); // Hora automática

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
        $usuariosTi = \DB::table('usuarios_ti')->orderBy('nombres')->get();
        return view('MonitoreoRed.edit', compact('monitoreo', 'usuariosTi'));
    }

public function update(Request $request, $id)
{
    $monitoreo = MonitoreoRed::findOrFail($id);
    
    $validated = $request->validate([
        'fecha' => 'required|date',
        'velocidad_descarga' => 'required|numeric|min:0',
        'velocidad_subida' => 'required|numeric|min:0',
        'porcentaje_experiencia_wifi' => 'required|numeric|min:0|max:100',
        'clientes_conectados' => 'required|integer|min:0',
        'observaciones' => 'nullable|string',
        'responsable' => 'required|exists:usuarios_ti,id'
    ]);

    // Opcional: actualizar la hora automáticamente si quieres
    $validated['hora'] = now()->format('H:i');

    $monitoreo->update($validated); // <<--- Esto reemplaza los datos existentes, no crea uno nuevo

    return redirect()->route('monitoreo-red.index')
        ->with('success', 'Registro de monitoreo actualizado exitosamente.');
}

    public function destroy($id)
    {
        $monitoreo = MonitoreoRed::findOrFail($id);
        $monitoreo->delete();

        return redirect()->route('monitoreo-red.index')
            ->with('success', 'Registro de monitoreo eliminado exitosamente.');
    }
}