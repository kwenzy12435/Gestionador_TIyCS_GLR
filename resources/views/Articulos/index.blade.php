@extends('layouts.app')
@section('title', 'Artículos')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i>Artículos</h1>
@endsection

@section('header-actions')
<a href="{{ route('articulos.create') }}" class="btn btn-brand">
    <i class="bi bi-plus-lg me-1"></i>Nuevo artículo
</a>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-3 shadow-sm">
    <form method="GET" class="mb-3" action="{{ route('articulos.index') }}">
        <div class="input-group">
            <input type="text" name="search" class="form-control"
                   placeholder="Buscar: nombre, descripción, unidades, ubicación, estado, fecha…"
                   value="{{ $search ?? '' }}">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            @if($search)
                <a href="{{ route('articulos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Subcategoría</th>
                    <th>Cantidad</th>
                    <th>Unidades</th>
                    <th>Ubicación</th>
                    <th>Fecha ingreso</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articulos as $articulo)
                    @php
                        $badgeEstado = match($articulo->estado) {
                            'Disponible' => 'bg-success',
                            'pocas piezas' => 'bg-warning text-dark',
                            'no disponible' => 'bg-secondary',
                            default => 'bg-light text-dark'
                        };
                        
                        $badgeUbicacion = match($articulo->ubicacion) {
                            'almacen' => 'bg-primary',
                            'oficina' => 'bg-info text-dark',
                            default => 'bg-dark'
                        };
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $articulo->id }}</td>
                        <td class="fw-semibold">{{ $articulo->nombre }}</td>
                        <td>{{ $articulo->categoria->nombre ?? '—' }}</td>
                        <td>{{ $articulo->subcategoria->nombre ?? '—' }}</td>
                        <td class="fw-bold">{{ number_format($articulo->cantidad) }}</td>
                        <td><span class="badge bg-light text-dark text-uppercase">{{ $articulo->unidades }}</span></td>
                        <td>
                            <span class="badge {{ $badgeUbicacion }} text-uppercase">
                                {{ $articulo->ubicacion }}
                            </span>
                        </td>
                        <td class="text-nowrap">{{ $articulo->fecha_ingreso->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge {{ $badgeEstado }}">
                                {{ $articulo->estado }}
                            </span>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('articulos.show', $articulo) }}" 
                               class="btn btn-sm btn-outline-primary" 
                               title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('articulos.edit', $articulo) }}" 
                               class="btn btn-sm btn-outline-warning" 
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('articulos.destroy', $articulo) }}" 
                                  method="POST" 
                                  class="d-inline" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar este artículo?')">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="bi bi-inbox display-4 d-block mb-2"></i>
                            No hay artículos registrados
                            @if($search)
                                <br><small>Intenta con otros términos de búsqueda</small>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($articulos->hasPages())
        <div class="card-footer">
            {{ $articulos->links() }}
        </div>
    @endif
</div>
@endsection