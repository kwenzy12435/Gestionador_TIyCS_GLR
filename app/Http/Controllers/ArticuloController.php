<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;

class ArticuloController extends Controller
{
    /* ---------- Reglas / Mensajes / Atributos ---------- */

    protected function rules(): array
    {
        return [
            'categoria_id'    => 'required|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'nombre'          => 'required|string|max:150',
            'descripcion'     => 'nullable|string',
            'cantidad'        => 'required|integer|min:0',
            'unidades'        => 'required|in:piezas,cajas,paquetes',
            'ubicacion'       => 'required|in:cajon1,rafa,cajon4,almacen,oficina',
            'fecha_ingreso'   => 'required|date',
            'estado'          => 'required|in:Disponible,pocas piezas,no disponible',
        ];
    }

    protected function messages(): array
    {
        return [
            'required'           => 'El campo :attribute es obligatorio.',
            'string'             => 'El campo :attribute debe ser texto.',
            'integer'            => 'El campo :attribute debe ser un número entero.',
            'min.integer'        => 'El campo :attribute debe ser al menos :min.',
            'date'               => 'El campo :attribute debe ser una fecha válida.',
            'in'                 => 'El valor de :attribute no es válido.',
            'exists'             => 'La :attribute seleccionada no existe.',
            'max.string'         => 'El campo :attribute no debe exceder :max caracteres.',
        ];
    }

    protected function attributes(): array
    {
        return [
            'categoria_id'    => 'categoría',
            'subcategoria_id' => 'subcategoría',
            'nombre'          => 'nombre del artículo',
            'descripcion'     => 'descripción',
            'cantidad'        => 'cantidad',
            'unidades'        => 'unidades',
            'ubicacion'       => 'ubicación',
            'fecha_ingreso'   => 'fecha de ingreso',
            'estado'          => 'estado',
        ];
    }

    /* ------------------ Acciones ------------------ */

    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));

        $articulos = Articulo::with(['categoria', 'subcategoria'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'LIKE', "%{$search}%")
                      ->orWhere('descripcion', 'LIKE', "%{$search}%")
                      ->orWhere('unidades', 'LIKE', "%{$search}%")
                      ->orWhere('ubicacion', 'LIKE', "%{$search}%")
                      ->orWhere('estado', 'LIKE', "%{$search}%")
                      ->orWhere('fecha_ingreso', 'LIKE', "%{$search}%")
                      ->orWhereHas('categoria', fn($qq) => $qq->where('nombre','LIKE',"%{$search}%")
                                                           ->orWhere('descripcion','LIKE',"%{$search}%"))
                      ->orWhereHas('subcategoria', fn($qq) => $qq->where('nombre','LIKE',"%{$search}%")
                                                              ->orWhere('descripcion','LIKE',"%{$search}%"));
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('articulos.index', compact('articulos', 'search'));
    }

    public function create()
    {
        $categorias    = Categoria::orderBy('nombre')->get();
        $subcategorias = Subcategoria::orderBy('nombre')->get();

        return view('articulos.create', compact('categorias', 'subcategorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        Articulo::create($validated);

        return redirect()->route('articulos.index')
            ->with('success', 'Artículo creado exitosamente.');
    }

    public function show(Articulo $articulo)
    {
        $articulo->load(['categoria', 'subcategoria']);
        return view('articulos.show', compact('articulo'));
    }

    public function edit(Articulo $articulo)
    {
        $categorias    = Categoria::orderBy('nombre')->get();
        $subcategorias = Subcategoria::orderBy('nombre')->get();

        return view('articulos.edit', compact('articulo', 'categorias', 'subcategorias'));
    }

    public function update(Request $request, Articulo $articulo)
    {
        $validated = $request->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        $articulo->update($validated);

        return redirect()->route('articulos.index')
            ->with('success', 'Artículo actualizado exitosamente.');
    }

    public function destroy(Articulo $articulo)
    {
        $articulo->delete();

        return redirect()->route('articulos.index')
            ->with('success', 'Artículo eliminado exitosamente.');
    }
}
