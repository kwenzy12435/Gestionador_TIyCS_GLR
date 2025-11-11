@extends('layouts.app')
@section('title', 'Inventario de Dispositivos')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-pc-display me-2"></i>Inventario de Dispositivos</h1>
@endsection

@section('header-actions')
<a href="{{ route('inventario-dispositivos.create') }}" class="btn btn-brand">
    <i class="bi bi-plus-lg me-1"></i>Nuevo Dispositivo
</a>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-3 shadow-sm">
    <!-- Filtros -->
    <form method="GET" action="{{ route('inventario-dispositivos.index') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" 
                       placeholder="Buscar: modelo, serie, MAC, procesador..." 
                       value="{{ $search ?? '' }}">
            </div>
            <div class="col-md-2">
                <select name="tipo_id" class="form-select">
                    <option value="">Todos los tipos</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" {{ $tipoId == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="marca_id" class="form-select">
                    <option value="">Todas las marcas</option>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->id }}" {{ $marcaId == $marca->id ? 'selected' : '' }}>
                            {{ $marca->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado }}" {{ $estado == $estado ? 'selected' : '' }}>
                            {{ ucfirst($estado) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
            </div>
        </div>
        @if($search || $tipoId || $marcaId || $estado)
            <div class="mt-2">
                <a href="{{ route('inventario-dispositivos.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-lg"></i> Limpiar filtros
                </a>
            </div>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Modelo</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>N° Serie</th>
                    <th>Estado</th>
                    <th>Asignado a</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dispositivos as $dispositivo)
                    @php
                        $badgeEstado = match($dispositivo->estado) {
                            'nuevo' => 'bg-success',
                            'asignado' => 'bg-primary',
                            'reparación' => 'bg-warning text-dark',
                            'baja' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $dispositivo->id }}</td>
                        <td>
                            <strong>{{ $dispositivo->modelo }}</strong>
                            @if($dispositivo->mac)
                                <br><small class="text-muted">MAC: {{ $dispositivo->mac }}</small>
                            @endif
                        </td>
                        <td>{{ $dispositivo->tipo->nombre ?? '—' }}</td>
                        <td>{{ $dispositivo->marca->nombre ?? '—' }}</td>
                        <td>
                            <code>{{ $dispositivo->numero_serie }}</code>
                            @if($dispositivo->serie)
                                <br><small class="text-muted">Serie: {{ $dispositivo->serie }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $badgeEstado }}">
                                {{ ucfirst($dispositivo->estado) }}
                            </span>
                        </td>
                        <td>
                            @if($dispositivo->colaborador)
                                {{ $dispositivo->colaborador->nombre }} {{ $dispositivo->colaborador->apellidos }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('inventario-dispositivos.show', $dispositivo) }}" 
                               class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('inventario-dispositivos.edit', $dispositivo) }}" 
                               class="btn btn-sm btn-outline-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('inventario-dispositivos.qr', $dispositivo) }}" 
                               class="btn btn-sm btn-outline-info" title="Descargar QR">
                                <i class="bi bi-qr-code"></i>
                            </a>
                            <form action="{{ route('inventario-dispositivos.destroy', $dispositivo) }}" 
                                  method="POST" 
                                  class="d-inline" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar este dispositivo?')">
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
                            <i class="bi bi-pc-display-horizontal display-4 d-block mb-2"></i>
                            No hay dispositivos registrados
                            @if($search || $tipoId || $marcaId || $estado)
                                <br><small>Intenta con otros términos de búsqueda</small>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($dispositivos->hasPages())
        <div class="card-footer">
            {{ $dispositivos->links() }}
        </div>
    @endif
</div>
@endsection