@extends('layouts.app')
@section('title', 'Usuarios TI')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-people me-2"></i>Usuarios TI</h1>
@endsection

@section('header-actions')
<a href="{{ route('usuarios-ti.create') }}" class="btn btn-brand">
    <i class="bi bi-plus-lg me-1"></i>Nuevo usuario
</a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Lista de Usuarios del Sistema</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por usuario, nombre, puesto..." value="{{ $search ?? '' }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('usuarios-ti.index') }}" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if($usuarios->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Nombre Completo</th>
                            <th>Puesto</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th width="140" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        @php($key = $usuario->getRouteKey())
                        <tr>
                            <td><strong>#{{ $usuario->id }}</strong></td>
                            <td><div class="fw-semibold">{{ $usuario->usuario }}</div></td>
                            <td>
                                {{ $usuario->nombres }} {{ $usuario->apellidos }}
                                @if($usuario->id === auth()->id())
                                    <span class="badge bg-primary ms-1">Tú</span>
                                @endif
                            </td>
                            <td>{{ $usuario->puesto ?: '—' }}</td>
                            <td>{{ $usuario->telefono ?: '—' }}</td>
                            <td>
                            <span class="badge {{ ['ADMIN'=>'bg-danger','AUXILIAR-TI'=>'bg-info','PERSONAL-TI'=>'bg-secondary'][$usuario->rol] ?? 'bg-secondary' }}">
                              {{ $usuario->rol }}
                            </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('usuarios-ti.show', ['usuarioTi' => $key]) }}" class="btn btn-outline-primary" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('usuarios-ti.edit', ['usuarioTi' => $key]) }}" class="btn btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($usuario->id !== auth()->id())
                                        <form action="{{ route('usuarios-ti.destroy',$usuario) }}"
      method="POST"
      class="d-inline"
      data-confirm="¿Estás seguro de eliminar a {{ $usuario->usuario }}?"
      data-confirm-title="Eliminar usuario TI">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger">
        <i class="bi bi-trash"></i>
    </button>
</form>

                                    @else
                                        <button class="btn btn-outline-secondary" disabled title="No puedes eliminarte a ti mismo">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Mostrando {{ $usuarios->firstItem() }} - {{ $usuarios->lastItem() }} de {{ $usuarios->total() }} usuarios
                </div>
                {{ $usuarios->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="text-muted mt-3">
                    @if(request('search'))
                        No se encontraron usuarios
                    @else
                        No hay usuarios registrados
                    @endif
                </h4>
                <p class="text-muted">
                    @if(request('search'))
                        Intenta con otros términos de búsqueda
                    @else
                        Comienza agregando el primer usuario del sistema
                    @endif
                </p>
                <a href="{{ route('usuarios-ti.create') }}" class="btn btn-brand mt-2">
                    <i class="bi bi-plus-circle me-1"></i>Crear Usuario
                </a>
                @if(request('search'))
                    <a href="{{ route('usuarios-ti.index') }}" class="btn btn-outline-secondary mt-2">
                        Ver todos los usuarios
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
