<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Canal;
use App\Models\Marca;
use App\Models\Naturaleza;
use App\Models\Plataforma;
use App\Models\TipoDispositivo;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;

class ConfigSistemaController extends Controller
{
    private array $modelos = [
        'departamentos'     => Departamento::class,
        'canales'           => Canal::class,
        'marcas'            => Marca::class,
        'naturalezas'       => Naturaleza::class,
        'plataformas'       => Plataforma::class,
        'tipos_dispositivo' => TipoDispositivo::class,
        'categorias'        => Categoria::class,
        'subcategorias'     => Subcategoria::class,
    ];

    private array $nombres = [
        'departamentos'     => 'Departamentos',
        'canales'           => 'Canales de Atención',
        'marcas'            => 'Marcas de Equipos',
        'naturalezas'       => 'Naturalezas de Solicitud',
        'plataformas'       => 'Plataformas/Sistemas',
        'tipos_dispositivo' => 'Tipos de Dispositivo',
        'categorias'        => 'Categorías',
        'subcategorias'     => 'Subcategorías',
    ];

    public function index(?string $tabla = 'departamentos')
    {
        if (!array_key_exists($tabla, $this->modelos)) abort(404);

        $modelo = $this->modelos[$tabla];

        $datos = $tabla === 'subcategorias'
            ? $modelo::with('categoria')->get()
            : $modelo::all();

        $categorias = $tabla === 'subcategorias' ? Categoria::all() : collect();

        return view('admin.configsistem.index', [
            'tabla_actual' => $tabla,
            'nombre_tabla' => $this->nombres[$tabla],
            'datos'        => $datos,
            'tablas'       => $this->nombres,
            'categorias'   => $categorias,
        ]);
    }

    public function store(Request $request, string $tabla)
    {
        if (!array_key_exists($tabla, $this->modelos)) abort(404);

        if ($tabla === 'subcategorias') {
            $request->validate([
                'categoria_id' => 'required|exists:categorias,id',
                'nombre'       => 'required|string|max:100|unique:subcategorias,nombre,NULL,id,categoria_id,' . $request->categoria_id,
            ]);
        } else {
            $request->validate([
                'nombre' => 'required|string|max:100|unique:' . $tabla . ',nombre',
            ]);
        }

        $modelo  = $this->modelos[$tabla];
        $payload = ['nombre' => $request->nombre];

        if ($tabla === 'subcategorias') {
            $payload['categoria_id'] = $request->categoria_id;
        }

        $modelo::create($payload);

        return redirect()->route('admin.configsistem.index', ['tabla' => $tabla])
            ->with('success', 'Registro creado correctamente.');
    }

    public function update(Request $request, string $tabla, int $id)
    {
        if (!array_key_exists($tabla, $this->modelos)) abort(404);

        $modelo   = $this->modelos[$tabla];
        $registro = $modelo::findOrFail($id);

        if ($tabla === 'subcategorias') {
            $request->validate([
                'categoria_id' => 'required|exists:categorias,id',
                'nombre'       => 'required|string|max:100|unique:subcategorias,nombre,' . $id . ',id,categoria_id,' . $request->categoria_id,
            ]);
        } else {
            $request->validate([
                'nombre' => 'required|string|max:100|unique:' . $tabla . ',nombre,' . $id,
            ]);
        }

        $payload = ['nombre' => $request->nombre];

        if ($tabla === 'subcategorias') {
            $payload['categoria_id'] = $request->categoria_id;
        }

        $registro->update($payload);

        return redirect()->route('admin.configsistem.index', ['tabla' => $tabla])
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy(string $tabla, int $id)
    {
        if (!array_key_exists($tabla, $this->modelos)) abort(404);

        $modelo   = $this->modelos[$tabla];
        $registro = $modelo::findOrFail($id);
        $registro->delete();

        return redirect()->route('admin.configsistem.index', ['tabla' => $tabla])
            ->with('success', 'Registro eliminado correctamente.');
    }
}
