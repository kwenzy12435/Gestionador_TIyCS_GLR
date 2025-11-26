@extends('layouts.app')
@section('title', 'Colaboradores')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Colaboradores</h1>
@endsection

@section('header-actions')
<a href="{{ route('colaboradores.create') }}" class="btn btn-brand">
    <i class="bi bi-person-plus me-1"></i>Nuevo Colaborador
</a>
@endsection

@section('content')


<div class="card p-3 shadow-sm">
    <form method="GET" action="{{ route('colaboradores.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control"
                   placeholder="Buscar: usuario, nombre, apellidos, puesto, AnyDesk, departamento…"
                   value="{{ $search ?? '' }}">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
            @if($search)
                <a href="{{ route('colaboradores.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Nombre Completo</th>
                    <th>Puesto</th>
                    <th>Departamento</th>
                    <th>AnyDesk ID</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($colaboradores as $colaborador)
                    <tr>
                        <td class="fw-semibold">{{ $colaborador->id }}</td>
                        <td>
                            <strong class="text-primary">{{ $colaborador->usuario }}</strong>
                        </td>
                        <td>
                            {{ $colaborador->nombre }} {{ $colaborador->apellidos }}
                        </td>
                        <td>
                            @if($colaborador->puesto)
                                <span class="badge bg-light text-dark">{{ $colaborador->puesto }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($colaborador->departamento)
                                <span class="badge bg-info text-dark">{{ $colaborador->departamento->nombre }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($colaborador->anydesk_id)
                                <code class="text-success">{{ $colaborador->anydesk_id }}</code>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('colaboradores.show', $colaborador) }}" 
                               class="btn btn-sm btn-outline-primary" 
                               title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('colaboradores.edit', $colaborador) }}" 
                               class="btn btn-sm btn-outline-warning" 
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                           <form action="{{ route('colaboradores.destroy', $colaborador) }}" 
      method="POST" 
      class="d-inline"
      data-confirm="¿Estás seguro de eliminar al colaborador «{{ $colaborador->nombre }} {{ $colaborador->apellidos }}»?"
      data-confirm-title="Eliminar colaborador"
      data-confirm-variant="danger">
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
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-people display-4 d-block mb-2"></i>
                            No hay colaboradores registrados
                            @if($search)
                                <br><small>Intenta con otros términos de búsqueda</small>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($colaboradores->hasPages())
  <div class="card-footer d-flex justify-content-between align-items-center">
    <div class="small text-muted">
      Mostrando {{ $colaboradores->firstItem() }}–{{ $colaboradores->lastItem() }} de {{ $colaboradores->total() }}
    </div>
    {{ $colaboradores->onEachSide(1)->links() }}
  </div>
@endif
</div>
@endsection