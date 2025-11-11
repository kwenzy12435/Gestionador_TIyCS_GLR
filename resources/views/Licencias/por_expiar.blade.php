@extends('layouts.app')
@section('title', 'Licencias por Expirar')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Licencias por Expirar</h1>
@endsection

@section('header-actions')
<a href="{{ route('licencias.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Volver al Listado
</a>
@endsection

@section('content')
@include('Partials.flash')

<div class="card p-3 shadow-sm">
    <!-- Barra de búsqueda -->
    <form method="GET" action="{{ route('licencias.por-expiar') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control"
                   placeholder="Buscar en licencias por expirar..."
                   value="{{ $search ?? '' }}">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
            @if($search)
                <a href="{{ route('licencias.por-expiar') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>

    @if($licencias->count() > 0)
    <div class="alert alert-warning">
        <i class="bi bi-info-circle me-2"></i>
        Se muestran <strong>{{ $licencias->count() }}</strong> licencias que expiran en los próximos 30 días.
    </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Cuenta</th>
                    <th>Plataforma</th>
                    <th>Colaborador</th>
                    <th>Expiración</th>
                    <th>Días Restantes</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($licencias as $licencia)
                    @php
                        $exp = \Carbon\Carbon::parse($licencia->expiracion);
                        $days = now()->diffInDays($exp, false);
                        $badge = match(true) {
                            $days < 0 => 'bg-danger',
                            $days <= 7 => 'bg-warning text-dark',
                            default => 'bg-success'
                        };
                        $urgency = match(true) {
                            $days < 0 => 'text-danger fw-bold',
                            $days <= 7 => 'text-warning fw-bold',
                            $days <= 15 => 'text-warning',
                            default => 'text-success'
                        };
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
                                    <strong>{{ $licencia->colaborador->nombres }} {{ $licencia->colaborador->apellidos }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $licencia->colaborador->email }}</small>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $badge }}">
                                {{ $exp->format('d/m/Y') }}
                            </span>
                        </td>
                        <td class="{{ $urgency }}">
                            @if($days < 0)
                                <i class="bi bi-exclamation-circle me-1"></i>Expirada hace {{ abs($days) }} días
                            @else
                                <i class="bi bi-clock me-1"></i>{{ $days }} días
                            @endif
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
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-check-circle display-4 d-block mb-2 text-success"></i>
                            No hay licencias próximas a expirar
                            @if($search)
                                <br><small>Intenta con otros términos de búsqueda</small>
                            @else
                                <br><small>¡Excelente! Todas las licencias están al día.</small>
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