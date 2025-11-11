@extends('layouts.app')
@section('title', 'Detalle de Artículo')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-eye me-2"></i>Detalle del artículo</h1>
@endsection

@section('content')
@php
    $badgeEstado = match($articulo->estado) {
        'Disponible' => 'bg-success',
        'pocas piezas' => 'bg-warning text-dark',
        'no disponible' => 'bg-secondary',
        default => 'bg-light text-dark'
    };
    
    $badgeUbicacion = match($articulo->ubicacion) {
        'almacen' => 'bg-primary',
        'oficina' => 'bg-info text-dark',
        default => 'bg-dark'
    };
@endphp

<div class="card p-4 shadow-sm">
    <div class="row">
        <div class="col-md-8">
            <h4 class="fw-bold text-brand">{{ $articulo->nombre }}</h4>
            <p class="text-muted mb-4">{{ $articulo->descripcion ?? 'Sin descripción' }}</p>
            
            <div class="row g-3">
                <div class="col-sm-6">
                    <strong><i class="bi bi-tags me-2"></i>Categoría:</strong>
                    <p class="ms-4">{{ $articulo->categoria->nombre ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-tag me-2"></i>Subcategoría:</strong>
                    <p class="ms-4">{{ $articulo->subcategoria->nombre ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-hash me-2"></i>Cantidad:</strong>
                    <p class="ms-4 fw-bold fs-5">{{ number_format($articulo->cantidad) }}</p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-box me-2"></i>Unidades:</strong>
                    <p class="ms-4">
                        <span class="badge bg-light text-dark text-uppercase">
                            {{ $articulo->unidades }}
                        </span>
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-geo-alt me-2"></i>Ubicación:</strong>
                    <p class="ms-4">
                        <span class="badge {{ $badgeUbicacion }} text-uppercase">
                            {{ $articulo->ubicacion }}
                        </span>
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-calendar me-2"></i>Fecha de ingreso:</strong>
                    <p class="ms-4">{{ $articulo->fecha_ingreso->format('d/m/Y') }}</p>
                </div>
                <div class="col-12">
                    <strong><i class="bi bi-info-circle me-2"></i>Estado:</strong>
                    <p class="ms-4">
                        <span class="badge {{ $badgeEstado }} fs-6">
                            {{ $articulo->estado }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 border-start">
            <h6 class="fw-semibold mb-3">Información adicional</h6>
            <div class="d-grid gap-2">
                <div class="text-center p-3 bg-light rounded">
                    <i class="bi bi-clock-history display-6 text-muted d-block mb-2"></i>
                    <strong>Registrado el:</strong><br>
                    {{ $articulo->created_at->format('d/m/Y H:i') }}
                </div>
                
                @if($articulo->updated_at->ne($articulo->created_at))
                <div class="text-center p-3 bg-light rounded">
                    <i class="bi bi-arrow-clockwise display-6 text-muted d-block mb-2"></i>
                    <strong>Última actualización:</strong><br>
                    {{ $articulo->updated_at->format('d/m/Y H:i') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="text-end mt-4 pt-3 border-top">
        <a href="{{ route('articulos.edit', $articulo) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Editar artículo
        </a>
        <a href="{{ route('articulos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
    </div>
</div>
@endsection