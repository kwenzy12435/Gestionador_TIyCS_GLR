@extends('layouts.app')
@section('title', 'Bitácora de Respaldo')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-hdd-stack me-2"></i>Bitácora de Respaldo</h1>
@endsection

@section('header-actions')
<a href="{{ route('bitacora-respaldo.create') }}" class="btn btn-brand">
    <i class="bi bi-plus-lg me-1"></i>Nuevo Registro
</a>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-3 shadow-sm">
    <form method="GET" action="{{ route('bitacora-respaldo.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control"
                   placeholder="Buscar: empresa, estado, ubicación, acciones, fecha, usuario TI…"
                   value="{{ $search ?? '' }}">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
            @if($search)
                <a href="{{ route('bitacora-respaldo.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
        
        <div class="mt-2 d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-sm btn-outline-secondary quick-search" data-search="contabilidad">
                Contabilidad
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary quick-search" data-search="nomina">
                Nómina
            </button>
            <button type="button" class="btn btn-sm btn-outline-success quick-search" data-search="Hecho">
                Hecho
            </button>
            <button type="button" class="btn btn-sm btn-outline-warning quick-search" data-search="no hecho">
                No Hecho
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Empresa</th>
                    <th>Fecha Respaldo</th>
                    <th>Estado</th>
                 
                    <th>Ubicación</th>
                    <th>Responsable TI</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bitacoras as $bitacora)
                    @php
                        $badgeEmpresa = match($bitacora->empresa_id) {
                            'contabilidad' => 'bg-primary',
                            'nomina' => 'bg-info text-dark',
                            default => 'bg-secondary'
                        };
                        
                        $badgeEstado = match(strtolower($bitacora->estado)) {
                            'hecho' => 'bg-success',
                            'no hecho' => 'bg-warning text-dark',
                            default => 'bg-secondary'
                        };
                        
                        $respaldos = [];
                        if ($bitacora->respaldo_contabilidad) $respaldos[] = 'Contabilidad';
                        if ($bitacora->respaldo_nominas) $respaldos[] = 'Nóminas';
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $bitacora->id }}</td>
                        <td>
                            <span class="badge {{ $badgeEmpresa }} text-uppercase">
                                {{ $bitacora->empresa_id }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            {{ $bitacora->fecha_respaldo->format('d/m/Y') }}
                        </td>
                        <td>
                            <span class="badge {{ $badgeEstado }}">
                                {{ $bitacora->estado }}
                            </span>
                        </td>
                
                        <td class="text-truncate" style="max-width: 200px" title="{{ $bitacora->ubicacion_guardado }}">
                            {{ $bitacora->ubicacion_guardado ?? '—' }}
                        </td>
                        <td>
                            @if($bitacora->usuarioTi)
                                <small>
                                    <strong>{{ $bitacora->usuarioTi->usuario }}</strong><br>
                                    {{ $bitacora->usuarioTi->nombres }} {{ $bitacora->usuarioTi->apellidos }}
                                </small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('bitacora-respaldo.show', $bitacora) }}" 
                               class="btn btn-sm btn-outline-primary" 
                               title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('bitacora-respaldo.edit', $bitacora) }}" 
                               class="btn btn-sm btn-outline-warning" 
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('bitacora-respaldo.destroy', $bitacora) }}" 
                                  method="POST" 
                                  class="d-inline" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar este registro?')">
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
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-hdd-stack display-4 d-block mb-2"></i>
                            No hay registros de respaldo
                            @if($search)
                                <br><small>Intenta con otros términos de búsqueda</small>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bitacoras->hasPages())
        <div class="card-footer">
            {{ $bitacoras->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick search buttons
    document.querySelectorAll('.quick-search').forEach(button => {
        button.addEventListener('click', function() {
            const searchInput = document.querySelector('input[name="search"]');
            searchInput.value = this.dataset.search;
            searchInput.closest('form').submit();
        });
    });
});
</script>
@endpush