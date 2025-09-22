<?php

namespace App\Http\Controllers;

use App\Models\InventarioDispositivo;
use App\Models\TipoDispositivo;
use App\Models\Marca;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioDispositivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dispositivos = DB::table('inventario_dispositivos')
            ->leftJoin('tipos_dispositivo', 'inventario_dispositivos.tipo_id', '=', 'tipos_dispositivo.id')
            ->leftJoin('marcas', 'inventario_dispositivos.marca_id', '=', 'marcas.id')
            ->leftJoin('colaboradores', 'inventario_dispositivos.colaborador_id', '=', 'colaboradores.id')
            ->select(
                'inventario_dispositivos.*',
                'tipos_dispositivo.nombre as tipo_nombre',
                'marcas.nombre as marca_nombre',
                'colaboradores.nombre as colaborador_nombre',
                'colaboradores.apellidos as colaborador_apellidos'
            )
            ->get();

        return view('inventario_dispositivos.index', compact('dispositivos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = TipoDispositivo::all();
        $marcas = Marca::all();
        $colaboradores = Colaborador::all();
        $estados = ['nuevo', 'asignado', 'baja', 'reparación'];
        
        return view('inventario_dispositivos.create', compact('tipos', 'marcas', 'colaboradores', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'estado' => 'required|in:nuevo,asignado,baja,reparación',
            'tipo_id' => 'required|exists:tipos_dispositivo,id',
            'marca_id' => 'required|exists:marcas,id',
            'mac' => 'nullable|string|max:50|unique:inventario_dispositivos',
            'modelo' => 'required|string|max:100',
            'serie' => 'nullable|string|max:100',
            'numero_serie' => 'required|string|max:100|unique:inventario_dispositivos',
            'procesador' => 'nullable|string|max:100',
            'memoria_ram' => 'nullable|string|max:100',
            'ssd' => 'nullable|string|max:100',
            'hdd' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'costo' => 'nullable|numeric|min:0',
            'fecha_compra' => 'nullable|date',
            'garantia_hasta' => 'nullable|date',
            'colaborador_id' => 'nullable|exists:colaboradores,id'
        ]);

        try {
            DB::table('inventario_dispositivos')->insert([
                'estado' => $validated['estado'],
                'tipo_id' => $validated['tipo_id'],
                'marca_id' => $validated['marca_id'],
                'mac' => $validated['mac'] ?? null,
                'modelo' => $validated['modelo'],
                'serie' => $validated['serie'] ?? null,
                'numero_serie' => $validated['numero_serie'],
                'procesador' => $validated['procesador'] ?? null,
                'memoria_ram' => $validated['memoria_ram'] ?? null,
                'ssd' => $validated['ssd'] ?? null,
                'hdd' => $validated['hdd'] ?? null,
                'color' => $validated['color'] ?? null,
                'costo' => $validated['costo'] ?? null,
                'fecha_compra' => $validated['fecha_compra'] ?? null,
                'garantia_hasta' => $validated['garantia_hasta'] ?? null,
                'colaborador_id' => $validated['colaborador_id'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('inventario-dispositivos.index')
                ->with('success', 'Dispositivo creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el dispositivo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dispositivo = DB::table('inventario_dispositivos')
            ->leftJoin('tipos_dispositivo', 'inventario_dispositivos.tipo_id', '=', 'tipos_dispositivo.id')
            ->leftJoin('marcas', 'inventario_dispositivos.marca_id', '=', 'marcas.id')
            ->leftJoin('colaboradores', 'inventario_dispositivos.colaborador_id', '=', 'colaboradores.id')
            ->select(
                'inventario_dispositivos.*',
                'tipos_dispositivo.nombre as tipo_nombre',
                'marcas.nombre as marca_nombre',
                'colaboradores.nombre as colaborador_nombre',
                'colaboradores.apellidos as colaborador_apellidos',
                'colaboradores.puesto as colaborador_puesto'
            )
            ->where('inventario_dispositivos.id', $id)
            ->first();

        if (!$dispositivo) {
            return redirect()->route('inventario-dispositivos.index')
                ->with('error', 'Dispositivo no encontrado.');
        }

        return view('inventario_dispositivos.show', compact('dispositivo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dispositivo = DB::table('inventario_dispositivos')
            ->where('id', $id)
            ->first();

        if (!$dispositivo) {
            return redirect()->route('inventario-dispositivos.index')
                ->with('error', 'Dispositivo no encontrado.');
        }

        $tipos = TipoDispositivo::all();
        $marcas = Marca::all();
        $colaboradores = Colaborador::all();
        $estados = ['nuevo', 'asignado', 'baja', 'reparación'];
        
        return view('inventario_dispositivos.edit', compact('dispositivo', 'tipos', 'marcas', 'colaboradores', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'estado' => 'required|in:nuevo,asignado,baja,reparación',
            'tipo_id' => 'required|exists:tipos_dispositivo,id',
            'marca_id' => 'required|exists:marcas,id',
            'mac' => 'nullable|string|max:50|unique:inventario_dispositivos,mac,' . $id,
            'modelo' => 'required|string|max:100',
            'serie' => 'nullable|string|max:100',
            'numero_serie' => 'required|string|max:100|unique:inventario_dispositivos,numero_serie,' . $id,
            'procesador' => 'nullable|string|max:100',
            'memoria_ram' => 'nullable|string|max:100',
            'ssd' => 'nullable|string|max:100',
            'hdd' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'costo' => 'nullable|numeric|min:0',
            'fecha_compra' => 'nullable|date',
            'garantia_hasta' => 'nullable|date',
            'colaborador_id' => 'nullable|exists:colaboradores,id'
        ]);

        try {
            DB::table('inventario_dispositivos')
                ->where('id', $id)
                ->update([
                    'estado' => $validated['estado'],
                    'tipo_id' => $validated['tipo_id'],
                    'marca_id' => $validated['marca_id'],
                    'mac' => $validated['mac'] ?? null,
                    'modelo' => $validated['modelo'],
                    'serie' => $validated['serie'] ?? null,
                    'numero_serie' => $validated['numero_serie'],
                    'procesador' => $validated['procesador'] ?? null,
                    'memoria_ram' => $validated['memoria_ram'] ?? null,
                    'ssd' => $validated['ssd'] ?? null,
                    'hdd' => $validated['hdd'] ?? null,
                    'color' => $validated['color'] ?? null,
                    'costo' => $validated['costo'] ?? null,
                    'fecha_compra' => $validated['fecha_compra'] ?? null,
                    'garantia_hasta' => $validated['garantia_hasta'] ?? null,
                    'colaborador_id' => $validated['colaborador_id'] ?? null,
                    'updated_at' => now(),
                ]);

            return redirect()->route('inventario-dispositivos.index')
                ->with('success', 'Dispositivo actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el dispositivo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    
    public function destroy($id)
    {
            try {
        $usuario = auth()->user();
        $nombreCompleto = $usuario->nombres . ' ' . ($usuario->apellidos ?? '');
        
        // Obtener el dispositivo antes de eliminarlo
        $dispositivo = DB::table('inventario_dispositivos')->where('id', $id)->first();
        
        if (!$dispositivo) {
            throw new \Exception('Dispositivo no encontrado');
        }
        
        // Obtener nombre del colaborador
        $colaboradorNombre = null;
        if ($dispositivo->colaborador_id) {
            $colaborador = DB::table('colaboradores')
                ->where('id', $dispositivo->colaborador_id)
                ->first();
            $colaboradorNombre = $colaborador->nombre ?? null;
        }
        
        DB::transaction(function () use ($id, $usuario, $nombreCompleto, $dispositivo, $colaboradorNombre) {
            // Insertar manualmente en log_bajas
            DB::table('log_bajas')->insert([
                'tabla_afectada' => 'inventario_dispositivos',
                'registro_id' => $id,
                'usuario_ti_id' => $usuario->id,
                'usuario_nombre_completo' => $nombreCompleto,
                'accion' => 'DELETE',
                'estado_texto' => $dispositivo->estado,
                'usuario_nombre' => $colaboradorNombre,
                'marca_id' => $dispositivo->marca_id,
                'numero_serie' => $dispositivo->numero_serie ?? $dispositivo->serie,
                'modelo' => $dispositivo->modelo,
                'mac_address' => $dispositivo->mac,
                'fecha_ultima_edicion' => $dispositivo->updated_at,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Eliminar el dispositivo
            DB::table('inventario_dispositivos')->where('id', $id)->delete();
        });
        
        return redirect()->back()->with('success', 'Dispositivo eliminado correctamente.');
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }

    }

}