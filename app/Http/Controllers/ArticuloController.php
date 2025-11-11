<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticuloController extends Controller
{
    public function index(Request $request)
    {
        // ✅ CORREGIDO: Usar get() en lugar de paginate() para el search
        $search = $request->get('search');
        
        $articulos = Articulo::with(['categoria', 'subcategoria'])
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('nombres', 'LIKE', "%{$search}%")
                      ->orWhere('descripcion', 'LIKE', "%{$search}%")
                      ->orWhere('unidades', 'LIKE', "%{$search}%")
                      ->orWhere('ubicacion', 'LIKE', "%{$search}%")
                      ->orWhere('estado', 'LIKE', "%{$search}%")
                      ->orWhere('fecha_ingreso', 'LIKE', "%{$search}%")
                      ->orWhereHas('categoria', function($q) use ($search) {
                          $q->where('nombres', 'LIKE', "%{$search}%")
                            ->orWhere('descripcion', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('subcategoria', function($q) use ($search) {
                          $q->where('nombres', 'LIKE', "%{$search}%")
                            ->orWhere('descripcion', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15); // ✅ CORREGIDO: Agregar número de elementos por página
        
        return view('articulos.index', compact('articulos', 'search'));
    }

    public function create()
    {
        // ✅ CORREGIDO: Usar get() en lugar de paginate() para selects
        $categorias = Categoria::orderBy('nombres')->get();
        $subcategorias = Subcategoria::orderBy('nombres')->get();

        return view('articulos.create', compact('categorias', 'subcategorias'));
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

        try {
            Articulo::create($validated);

            return redirect()->route('articulos.index')
                ->with('success', 'Artículo creado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el artículo: ' . $e->getMessage());
        }
    }

    public function show(Articulo $articulo)
    {
        $articulo->load(['categoria', 'subcategoria']);
        return view('articulos.show', compact('articulo'));
    }

    public function edit(Articulo $articulo)
    {
        // ✅ CORREGIDO: Usar get() en lugar de paginate() para selects
        $categorias = Categoria::orderBy('nombres')->get();
        $subcategorias = Subcategoria::orderBy('nombres')->get();

        return view('articulos.edit', compact('articulo', 'categorias', 'subcategorias'));
    }

    public function update(Request $request, Articulo $articulo)
    {
        $validated = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'nombres' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'cantidad' => 'required|integer|min:0',
            'unidades' => 'required|in:piezas,cajas,paquetes',
            'ubicacion' => 'required|in:cajon1,rafa,cajon4,almacen,oficina',
            'fecha_ingreso' => 'required|date',
            'estado' => 'required|in:Disponible,no disponible,pocas piezas'
        ]);

        try {
            $articulo->update($validated);

            return redirect()->route('articulos.index')
                ->with('success', 'Artículo actualizado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el artículo: ' . $e->getMessage());
        }
    }

    public function destroy(Articulo $articulo)
    {
        try {
            $articulo->delete();

            return redirect()->route('articulos.index')
                ->with('success', 'Artículo eliminado exitosamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('articulos.index')
                ->with('error', 'Error al eliminar el artículo: ' . $e->getMessage());
        }
    }
}