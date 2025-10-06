@extends('layouts.app')

@section('title', 'Gestión de Artículos - Sistema de Gestión TI')

@section('content')


<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('articulos.index') }}">
                            <i class="fas fa-box me-2"></i>
                            Artículos
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestión de Artículos</h1>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('articulos.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Nuevo Artículo
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if($articulos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Subcategoría</th>
                                        <th>Cantidad</th>
                                        <th>Ubicación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($articulos as $articulo)
                                    @php
                                        $categoria = \DB::table('categorias')->where('id', $articulo->categoria_id)->first();
                                        $subcategoria = $articulo->subcategoria_id ? \DB::table('subcategorias')->where('id', $articulo->subcategoria_id)->first() : null;
                                    @endphp
                                    <tr>
                                        <td>{{ $articulo->id }}</td>
                                        <td>{{ $articulo->nombre }}</td>
                                        <td>
                                            @if($categoria)
                                                <span class="badge bg-info">{{ $categoria->nombre }}</span>
                                            @else
                                                <span class="badge bg-secondary">ID: {{ $articulo->categoria_id }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subcategoria)
                                                <span class="badge bg-secondary">{{ $subcategoria->nombre }}</span>
                                            @elseif($articulo->subcategoria_id)
                                                <span class="badge bg-secondary">ID: {{ $articulo->subcategoria_id }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $articulo->cantidad }} {{ $articulo->unidades }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($articulo->ubicacion) }}</span>
                                        </td>
                                        <td>
                                            @if($articulo->estado == 'Disponible')
                                                <span class="badge bg-success">Disponible</span>
                                            @elseif($articulo->estado == 'no disponible')
                                                <span class="badge bg-danger">No Disponible</span>
                                            @else
                                                <span class="badge bg-warning">Pocas Piezas</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('articulos.show', $articulo->id) }}" class="btn btn-info btn-sm" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('articulos.edit', $articulo->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('articulos.destroy', $articulo->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este artículo?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay artículos registrados.
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection