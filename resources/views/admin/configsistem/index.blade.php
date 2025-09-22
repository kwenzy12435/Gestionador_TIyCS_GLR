<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración del Sistema - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-cog"></i> Configuración del Sistema
            </a>
        </div>
    </nav>

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
                                                <button type="button" class="btn btn-sm btn-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEditar"
                                                        data-id="{{ $item->id }}"
                                                        data-nombre="{{ $item->nombre }}"
                                                        @if($tabla_actual === 'subcategorias' && $item->categoria) 
                                                        data-categoria_id="{{ $item->categoria_id }}" 
                                                        @endif>
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
                            <select class="form-control" id="categoria_id" name="categoria_id" required>
                                <option value="">Seleccione una categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
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

    <!-- Modal para Editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditar" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @if($tabla_actual === 'subcategorias')
                        <div class="mb-3">
                            <label for="categoria_id_edit" class="form-label">Categoría *</label>
                            <select class="form-control" id="categoria_id_edit" name="categoria_id" required>
                                <option value="">Seleccione una categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="nombre_edit" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="nombre_edit" name="nombre" 
                                   required maxlength="100">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configurar modal de edición
        document.addEventListener('DOMContentLoaded', function() {
            const modalEditar = document.getElementById('modalEditar');
            modalEditar.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nombre = button.getAttribute('data-nombre');
                const categoriaId = button.getAttribute('data-categoria_id');
                
                const form = document.getElementById('formEditar');
                form.action = "{{ url('admin/configsistem') }}/{{ $tabla_actual }}/" + id;
                
                document.getElementById('nombre_edit').value = nombre;
                
                if (categoriaId && document.getElementById('categoria_id_edit')) {
                    document.getElementById('categoria_id_edit').value = categoriaId;
                }
            });

            // Limpiar modal de agregar al cerrar
            const modalAgregar = document.getElementById('modalAgregar');
            modalAgregar.addEventListener('hidden.bs.modal', function() {
                document.getElementById('nombre').value = '';
                if (document.getElementById('categoria_id')) {
                    document.getElementById('categoria_id').value = '';
                }
            });
        });
    </script>
</body>
</html>