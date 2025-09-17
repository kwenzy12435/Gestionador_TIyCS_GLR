<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;

class ArticuloController extends Controller
{
    public function index()
    {
        $articulos = Articulo::orderBy('created_at', 'desc')->get();
        return view('Articulos.index', compact('articulos'));
    }

    public function create()
    {
        $categorias = \DB::table('categorias')->orderBy('nombre')->get();
        $subcategorias = \DB::table('subcategorias')->orderBy('nombre')->get();
        
        return view('Articulos.create', compact('categorias', 'subcategorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'cantidad' => 'required|integer|min:0',
            'unidades' => 'required|in:piezas,cajas,paquetes',
            'ubicacion' => 'required|in:cajon1,rafa,cajon4,almacen,oficina',
            'fecha_ingreso' => 'required|date',
            'estado' => 'required|in:Disponible,no disponible,pocas piezas'
        ]);

        Articulo::create($validated);

        return redirect()->route('articulos.index')
            ->with('success', 'Artículo creado exitosamente.');
    }

    public function show($id)
    {
        $articulo = Articulo::findOrFail($id);
        $categoria = \DB::table('categorias')->where('id', $articulo->categoria_id)->first();
        $subcategoria = $articulo->subcategoria_id ? \DB::table('subcategorias')->where('id', $articulo->subcategoria_id)->first() : null;
        
        return view('Articulos.show', compact('articulo', 'categoria', 'subcategoria'));
    }

    public function edit($id)
    {
        $articulo = Articulo::findOrFail($id);
        $categorias = \DB::table('categorias')->orderBy('nombre')->get();
        $subcategorias = \DB::table('subcategorias')->orderBy('nombre')->get();
        
        return view('Articulos.edit', compact('articulo', 'categorias', 'subcategorias'));
    }

    public function update(Request $request, $id)
    {
        $articulo = Articulo::findOrFail($id);
        
        $validated = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'cantidad' => 'required|integer|min:0',
            'unidades' => 'required|in:piezas,cajas,paquetes',
            'ubicacion' => 'required|in:cajon1,rafa,cajon4,almacen,oficina',
            'fecha_ingreso' => 'required|date',
            'estado' => 'required|in:Disponible,no disponible,pocas piezas'
        ]);

        $articulo->update($validated);

        return redirect()->route('articulos.index')
            ->with('success', 'Artículo actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $articulo = Articulo::findOrFail($id);
        $articulo->delete();

        return redirect()->route('articulos.index')
            ->with('success', 'Artículo eliminado exitosamente.');
    }
}