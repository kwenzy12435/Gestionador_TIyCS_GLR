@extends('layouts.app')
@section('title', 'Monitoreo de Red')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-speedometer2 me-2"></i>Monitoreo de Red</h1>
@endsection

@section('header-actions')
<a href="{{ route('monitoreo_red.create') }}" class="btn btn-brand">
    <i class="bi bi-plus-lg me-1"></i>Nuevo Registro
</a>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-3 shadow-sm">
    <!-- Barra de búsqueda -->
    <form method="GET" action="{{ route('monitoreo_red.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control"
                   placeholder="Buscar por fecha, responsable, observaciones..."
                   value="{{ $search ?? '' }}">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
            @if($search)
                <a href="{{ route('monitoreo_red.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Descarga</th>
                    <th>Subida</th>
                    <th>WiFi</th>
                    <th>Clientes</th>
                    <th>Responsable</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monitoreos as $monitoreo)
                    @php
                        $wifiBadge = match(true) {
                            $monitoreo->porcentaje_experiencia_wifi >= 80 => 'bg-success',
                            $monitoreo->porcentaje_experiencia_wifi >= 60 => 'bg-warning text-dark',
                            default => 'bg-danger'
                        };
                        
                        $descargaClass = $monitoreo->velocidad_descarga >= 100 ? 'text-success fw-bold' : 
                                       ($monitoreo->velocidad_descarga >= 50 ? 'text-warning' : 'text-danger');
                        
                        $subidaClass = $monitoreo->velocidad_subida >= 50 ? 'text-success fw-bold' : 
                                     ($monitoreo->velocidad_subida >= 20 ? 'text-warning' : 'text-danger');
                    @endphp
                    <tr>
                        <td class="text-nowrap">
                            {{ $monitoreo->fecha->format('d/m/Y') }}
                        </td>
                        <td class="text-nowrap">
                            <small class="text-muted">{{ $monitoreo->hora }}</small>
                        </td>
                        <td class="{{ $descargaClass }}">
                            <strong>{{ number_format($monitoreo->velocidad_descarga, 2) }}</strong>
                            <small class="text-muted d-block">Mbps</small>
                        </td>
                        <td class="{{ $subidaClass }}">
                            <strong>{{ number_format($monitoreo->velocidad_subida, 2) }}</strong>
                            <small class="text-muted d-block">Mbps</small>
                        </td>
                        <td>
                            <span class="badge {{ $wifiBadge }}">
                                {{ $monitoreo->porcentaje_experiencia_wifi }}%
                            </span>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $monitoreo->clientes_conectados }}</span>
                        </td>
                        <td>
                            @if($monitoreo->usuarioResponsable)
                                <small>
                                    <strong>{{ $monitoreo->usuarioResponsable->usuario }}</strong><br>
                                    {{ $monitoreo->usuarioResponsable->nombres }}
                                </small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('monitoreo_red.show', $monitoreo) }}" 
                               class="btn btn-sm btn-outline-primary" 
                               title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('monitoreo_red.edit', $monitoreo) }}" 
                               class="btn btn-sm btn-outline-warning" 
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('monitoreo_red.destroy', $monitoreo) }}" 
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
                            <i class="bi bi-reception-4 display-4 d-block mb-2"></i>
                            No hay registros de monitoreo
                            @if($search)
                                <br><small>Intenta con otros términos de búsqueda</small>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($monitoreos->hasPages())
        <div class="card-footer">
            {{ $monitoreos->links() }}
        </div>
    @endif
</div>
@endsection