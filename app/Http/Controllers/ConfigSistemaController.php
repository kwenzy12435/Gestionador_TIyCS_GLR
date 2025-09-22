<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Canal;
use App\Models\Marca;
use App\Models\Naturaleza;
use App\Models\Plataforma;
use App\Models\TipoDispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Categoria;
use App\Models\Subcategoria;

class ConfigSistemaController extends Controller
{
    public function __construct()
    {
        // Verificación directa en el constructor
        if (!Auth::check() || Auth::user()->rol !== 'ADMIN') {
            abort(403, 'Acceso no autorizado. Solo administradores pueden acceder.');
        }
    }

    private $modelos = [
        'departamentos' => Departamento::class,
        'canales' => Canal::class,
        'marcas' => Marca::class,
        'naturalezas' => Naturaleza::class,
        'plataformas' => Plataforma::class,
        'tipos_dispositivo' => TipoDispositivo::class,
        'categorias' => Categoria::class,
        'subcategorias' => Subcategoria::class
    ];

    private $nombres = [
        'departamentos' => 'Departamentos',
        'canales' => 'Canales de Atención',
        'marcas' => 'Marcas de Equipos',
        'naturalezas' => 'Naturalezas de Solicitud',
        'plataformas' => 'Plataformas/Sistemas',
        'tipos_dispositivo' => 'Tipos de Dispositivo',
        'categorias' => 'Categorías',
        'subcategorias' => 'Subcategorías'
    ];

    public function index($tabla = 'departamentos')
    {
        if (!array_key_exists($tabla, $this->modelos)) {
            abort(404);
        }

        $modelo = $this->modelos[$tabla];
        
        // Cargar relaciones para subcategorías
        if ($tabla === 'subcategorias') {
            $datos = $modelo::with('categoria')->get();
        } else {
            $datos = $modelo::all();
        }

        // Cargar categorías para el formulario de subcategorías
        $categorias = $tabla === 'subcategorias' ? Categoria::all() : [];

        $nombreTabla = $this->nombres[$tabla];

        return view('admin.configsistem.index', [
            'tabla_actual' => $tabla,
            'nombre_tabla' => $nombreTabla,
            'datos' => $datos,
            'tablas' => $this->nombres,
            'categorias' => $categorias
        ]);
    }

    public function store(Request $request, $tabla)
    {
        if (!array_key_exists($tabla, $this->modelos)) {
            abort(404);
        }

        // Validaciones específicas para cada tabla
        if ($tabla === 'subcategorias') {
            $request->validate([
                'categoria_id' => 'required|exists:categorias,id',
                'nombre' => 'required|string|max:100|unique:subcategorias,nombre,NULL,id,categoria_id,' . $request->categoria_id
            ]);
        } else {
            $request->validate([
                'nombre' => 'required|string|max:100|unique:' . $tabla . ',nombre'
            ]);
        }

        $modelo = $this->modelos[$tabla];
        $modelo::create($request->all());

        return redirect()->route('admin.configsistem.index', $tabla)
            ->with('success', 'Registro creado correctamente.');
    }

    public function update(Request $request, $tabla, $id)
    {
        if (!array_key_exists($tabla, $this->modelos)) {
            abort(404);
        }

        // Validaciones específicas para cada tabla
        if ($tabla === 'subcategorias') {
            $request->validate([
                'categoria_id' => 'required|exists:categorias,id',
                'nombre' => 'required|string|max:100|unique:subcategorias,nombre,' . $id . ',id,categoria_id,' . $request->categoria_id
            ]);
        } else {
            $request->validate([
                'nombre' => 'required|string|max:100|unique:' . $tabla . ',nombre,' . $id
            ]);
        }

        $modelo = $this->modelos[$tabla];
        $registro = $modelo::findOrFail($id);
        $registro->update($request->all());

        return redirect()->route('admin.configsistem.index', $tabla)
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($tabla, $id)
    {
        if (!array_key_exists($tabla, $this->modelos)) {
            abort(404);
        }

        $modelo = $this->modelos[$tabla];
        $registro = $modelo::findOrFail($id);
        $registro->delete();

        return redirect()->route('admin.configsistem.index', $tabla)
            ->with('success', 'Registro eliminado correctamente.');
    }
}