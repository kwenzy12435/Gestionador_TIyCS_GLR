@extends('layouts.app')

@section('title', 'Configuración del Sistema - Admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar con las tablas -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tablas de Configuración</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($tablas as $key => $nombre)
                        <a href="{{ route('admin.configsistem.index.tabla', $key) }}" 
                           class="list-group-item list-group-item-action {{ $tabla_actual == $key ? 'active' : '' }}">
                            <i class="fas fa-table me-2"></i> {{ $nombre }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $nombre_tabla }}</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                        <i class="fas fa-plus me-1"></i> Agregar Nuevo
                    </button>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($datos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">ID</th>
                                        <th>Nombre</th>
                                        @if($tabla_actual === 'subcategorias')
                                            <th>Categoría</th>
                                        @endif
                                        <th width="150">Fecha Creación</th>
                                        <th width="120">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datos as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->nombre }}</td>
                                        @if($tabla_actual === 'subcategorias')
                                            <td>
                                                @if($item->categoria)
                                                    <span class="badge bg-info">{{ $item->categoria->nombre }}</span>
                                                @else
                                                    <span class="text-muted">Sin categoría</span>
                                                @endif
                                            </td>
                                        @endif
                                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <!-- Botón para abrir modal de edición específico para cada registro -->
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalEditar{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.configsistem.destroy', [$tabla_actual, $item->id]) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i> No hay registros en esta tabla.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar -->
<div class="modal fade" id="modalAgregar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.configsistem.store', $tabla_actual) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nuevo Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($tabla_actual === 'subcategorias')
                    <div class="mb-3">
                        <label for="categoria_id" class="form-label">Categoría *</label>
                        <select class="form-control @error('categoria_id') is-invalid @enderror" 
                                id="categoria_id" name="categoria_id" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" 
                               value="{{ old('nombre') }}"
                               required maxlength="100" placeholder="Ingrese el nombre">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modales de edición individuales para cada registro -->
@foreach($datos as $item)
<div class="modal fade" id="modalEditar{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.configsistem.update', [$tabla_actual, $item->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Registro #{{ $item->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($tabla_actual === 'subcategorias')
                    <div class="mb-3">
                        <label for="categoria_id_edit{{ $item->id }}" class="form-label">Categoría *</label>
                        <select class="form-control" id="categoria_id_edit{{ $item->id }}" name="categoria_id" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ $item->categoria_id == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="nombre_edit{{ $item->id }}" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre_edit{{ $item->id }}" 
                               name="nombre" value="{{ $item->nombre }}" required maxlength="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection