@extends('layouts.app')
@section('title', 'Detalle del Dispositivo')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-eye me-2"></i>Detalle del Dispositivo</h1>
@endsection

@section('content')
@php
    $badgeEstado = match($inventarioDispositivo->estado) {
        'nuevo' => 'bg-success',
        'asignado' => 'bg-primary',
        'reparación' => 'bg-warning text-dark',
        'baja' => 'bg-danger',
        default => 'bg-secondary'
    };
@endphp

<div class="card p-4 shadow-sm">
    <div class="row">
        <div class="col-md-8">
            <h4 class="fw-bold text-brand mb-4">{{ $inventarioDispositivo->modelo }}</h4>
            
            <div class="row g-3">
                <!-- Información Básica -->
                <div class="col-sm-6">
                    <strong><i class="bi bi-tag me-2"></i>Estado:</strong>
                    <p class="ms-4">
                        <span class="badge {{ $badgeEstado }} fs-6">
                            {{ ucfirst($inventarioDispositivo->estado) }}
                        </span>
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-pc-display me-2"></i>Tipo:</strong>
                    <p class="ms-4">{{ $inventarioDispositivo->tipo->nombre ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-building me-2"></i>Marca:</strong>
                    <p class="ms-4">{{ $inventarioDispositivo->marca->nombre ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-person me-2"></i>Asignado a:</strong>
                    <p class="ms-4">
                        @if($inventarioDispositivo->colaborador)
                            {{ $inventarioDispositivo->colaborador->nombre }} {{ $inventarioDispositivo->colaborador->apellidos }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>

                <!-- Identificación -->
                <div class="col-sm-6">
                    <strong><i class="bi bi-upc-scan me-2"></i>Número de Serie:</strong>
                    <p class="ms-4">
                        <code class="fs-5">{{ $inventarioDispositivo->numero_serie }}</code>
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-upc me-2"></i>Serie (Alterno):</strong>
                    <p class="ms-4">
                        @if($inventarioDispositivo->serie)
                            <code>{{ $inventarioDispositivo->serie }}</code>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-hdd-network me-2"></i>Dirección MAC:</strong>
                    <p class="ms-4">
                        @if($inventarioDispositivo->mac)
                            <code class="text-success">{{ $inventarioDispositivo->mac }}</code>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-palette me-2"></i>Color:</strong>
                    <p class="ms-4">{{ $inventarioDispositivo->color ?? '—' }}</p>
                </div>

                <!-- Especificaciones Técnicas -->
                <div class="col-sm-6">
                    <strong><i class="bi bi-cpu me-2"></i>Procesador:</strong>
                    <p class="ms-4">{{ $inventarioDispositivo->procesador ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-memory me-2"></i>Memoria RAM:</strong>
                    <p class="ms-4">{{ $inventarioDispositivo->memoria_ram ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-device-ssd me-2"></i>SSD:</strong>
                    <p class="ms-4">{{ $inventarioDispositivo->ssd ?? '—' }}</p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-hdd me-2"></i>HDD:</strong>
                    <p class="ms-4">{{ $inventarioDispositivo->hdd ?? '—' }}</p>
                </div>

                <!-- Información de Compra -->
                <div class="col-sm-6">
                    <strong><i class="bi bi-currency-dollar me-2"></i>Costo:</strong>
                    <p class="ms-4">
                        @if($inventarioDispositivo->costo)
                            ${{ number_format($inventarioDispositivo->costo, 2) }} MXN
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-calendar-check me-2"></i>Fecha de Compra:</strong>
                    <p class="ms-4">
                        @if($inventarioDispositivo->fecha_compra)
                            {{ $inventarioDispositivo->fecha_compra->format('d/m/Y') }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-shield-check me-2"></i>Garantía Hasta:</strong>
                    <p class="ms-4">
                        @if($inventarioDispositivo->garantia_hasta)
                            {{ $inventarioDispositivo->garantia_hasta->format('d/m/Y') }}
                            @if($inventarioDispositivo->garantia_hasta->isFuture())
                                <span class="badge bg-success ms-2">Vigente</span>
                            @else
                                <span class="badge bg-secondary ms-2">Vencida</span>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <!-- QR Code -->
        <div class="col-md-4 border-start">
            <div class="text-center">
                <h6 class="fw-semibold mb-3">Código QR del Dispositivo</h6>
                <img src="{{ $qrCodeBase64 }}" alt="QR del dispositivo" class="img-fluid border rounded p-2 bg-white shadow-sm">
                
                <div class="d-grid gap-2 mt-3">
                    <a class="btn btn-outline-dark"
                       href="{{ route('inventario-dispositivos.qr', ['inventarioDispositivo' => $inventarioDispositivo->id, 'size' => 300, 'format' => 'png']) }}">
                        <i class="bi bi-download me-1"></i>Descargar PNG
                    </a>
                    <a class="btn btn-outline-secondary"
                       href="{{ route('inventario-dispositivos.qr', ['inventarioDispositivo' => $inventarioDispositivo->id, 'size' => 300, 'format' => 'svg']) }}">
                        <i class="bi bi-download me-1"></i>Descargar SVG
                    </a>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="mt-4 pt-3 border-top">
                <h6 class="fw-semibold mb-3">Información del Registro</h6>
                <div class="d-grid gap-2">
                    <div class="text-center p-2 bg-light rounded">
                        <i class="bi bi-clock-history text-muted me-1"></i>
                        <strong>Registrado:</strong><br>
                        {{ $inventarioDispositivo->created_at->format('d/m/Y H:i') }}
                    </div>
                    
                    @if($inventarioDispositivo->updated_at->ne($inventarioDispositivo->created_at))
                    <div class="text-center p-2 bg-light rounded">
                        <i class="bi bi-arrow-clockwise text-muted me-1"></i>
                        <strong>Última actualización:</strong><br>
                        {{ $inventarioDispositivo->updated_at->format('d/m/Y H:i') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-4 pt-3 border-top">
        <a href="{{ route('inventario-dispositivos.edit', $inventarioDispositivo) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Editar Dispositivo
        </a>
        <a href="{{ route('inventario-dispositivos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver al Listado
        </a>
    </div>
</div>
@endsection