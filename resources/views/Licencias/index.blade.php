@extends('layouts.app')
@section('title', 'Gestión de Licencias')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-key-fill me-2"></i>Gestión de Licencias</h1>
@endsection

@section('header-actions')
<a href="{{ route('licencias.create') }}" class="btn btn-brand">
    <i class="bi bi-plus-lg me-1"></i>Nueva Licencia
</a>

@endsection

@section('content')


<div class="card p-3 shadow-sm">
    <!-- Barra de búsqueda -->
    <form method="GET" action="{{ route('licencias.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control"
                   placeholder="Buscar: cuenta, colaborador, plataforma, email..."
                   value="{{ $search ?? '' }}">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
            @if($search)
                <a href="{{ route('licencias.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Cuenta</th>
                    <th>Plataforma</th>
                    <th>Colaborador</th>
                    <th>Expiración</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($licencias as $licencia)
                    @php
                        $exp = $licencia->expiracion ? \Carbon\Carbon::parse($licencia->expiracion) : null;
                        $days = $exp ? now()->diffInDays($exp, false) : null;
                        $badge = match(true) {
                            !$exp => 'bg-secondary',
                            $days < 0 => 'bg-danger',
                            $days <= 7 => 'bg-warning text-dark',
                            default => 'bg-success'
                        };
                        $label = !$exp ? '—' : $exp->format('d/m/Y') . " (" . ($days < 0 ? $days : "+$days") . " días)";
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $licencia->cuenta }}</strong>
                        </td>
                        <td>
                            @if($licencia->plataforma)
                                <span class="badge bg-info text-dark">{{ $licencia->plataforma->nombre }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($licencia->colaborador)
                                <div>
                                    <strong>{{ $licencia->colaborador->nombre }} {{ $licencia->colaborador->apellidos }}</strong> <!-- ✅ Corregido: nombres → nombre -->
                                    <br>
                                    <small class="text-muted">{{ $licencia->colaborador->email }}</small>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $badge }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="text-end text-nowrap">                          
                            <a href="{{ route('licencias.show', $licencia) }}" 
                               class="btn btn-sm btn-outline-primary" 
                               title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('licencias.edit', $licencia) }}" 
                               class="btn btn-sm btn-outline-warning" 
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
<form action="{{ route('licencias.destroy', $licencia) }}" 
      method="POST" 
      class="d-inline"
      data-confirm="¿Estás seguro de eliminar esta licencia?"
      data-confirm-title="Eliminar licencia"
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
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-key display-4 d-block mb-2"></i>
                            No hay licencias registradas
                            @if($search)
                                <br><small>Intenta con otros términos de búsqueda</small>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($licencias->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Mostrando {{ $licencias->firstItem() }} - {{ $licencias->lastItem() }} de {{ $licencias->total() }} registros
            </div>
            {{ $licencias->links() }}
        </div>
    @endif
</div>
@endsection