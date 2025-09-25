<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\Colaborador;
use App\Models\Plataforma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LicenciaController extends Controller
{
    public function index()
    {
        $licencias = Licencia::with(['colaborador', 'plataforma'])->get();
        return view('licencias.index', compact('licencias'));
    }

    public function create()
    {
        $colaboradores = Colaborador::all();
        $plataformas = Plataforma::all();
        return view('licencias.create', compact('colaboradores', 'plataformas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'cuenta' => 'required|string|max:150|unique:licencias',
            'contrasena' => 'required|string|min:6',
            'plataforma_id' => 'nullable|exists:plataformas,id',
            'expiracion' => 'nullable|date'
        ]);

        Licencia::create($request->all());
        return redirect()->route('licencias.index')->with('success','Licencia creada exitosamente.');
    }

    public function show($id)
    {
        $licencia = Licencia::with(['colaborador','plataforma'])->findOrFail($id);
        return view('licencias.show', compact('licencia'));
    }

    public function edit($id)
    {
        $licencia = Licencia::findOrFail($id);
        $colaboradores = Colaborador::all();
        $plataformas = Plataforma::all();
        return view('licencias.edit', compact('licencia','colaboradores','plataformas'));
    }

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

        $licencia->update($request->all());
        return redirect()->route('licencias.index')->with('success','Licencia actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $licencia = Licencia::findOrFail($id);
        $licencia->delete();
        return redirect()->route('licencias.index')->with('success','Licencia eliminada exitosamente.');
    }

    public function confirmarPassword(Request $request)
{
    $user = Auth::user();
    $password = $request->input('password');

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Usuario no autenticado']);
    }

    // Compara la contraseña ingresada con la columna 'contrasena'
    if (Hash::check($password, $user->contrasena)) {
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Contraseña incorrecta']);
}
}