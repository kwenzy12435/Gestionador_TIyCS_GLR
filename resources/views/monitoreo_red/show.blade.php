@extends('layouts.app')
@section('title', 'Detalle de Monitoreo de Red')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-clipboard-check me-2"></i>Detalle del Monitoreo de Red</h1>
@endsection

@section('content')
@php
    $wifiBadge = match(true) {
        $monitoreoRed->porcentaje_experiencia_wifi >= 80 => 'bg-success',
        $monitoreoRed->porcentaje_experiencia_wifi >= 60 => 'bg-warning text-dark',
        default => 'bg-danger'
    };
    
    $descargaClass = $monitoreoRed->velocidad_descarga >= 100 ? 'text-success' : 
                   ($monitoreoRed->velocidad_descarga >= 50 ? 'text-warning' : 'text-danger');
    
    $subidaClass = $monitoreoRed->velocidad_subida >= 50 ? 'text-success' : 
                 ($monitoreoRed->velocidad_subida >= 20 ? 'text-warning' : 'text-danger');
@endphp

<div class="card p-4 shadow-sm">
    <div class="row">
        <div class="col-md-8">
            <h4 class="fw-bold text-brand mb-4">Monitoreo del {{ $monitoreoRed->fecha->format('d/m/Y') }}</h4>
            
            <div class="row g-3">
                <!-- Información General -->
                <div class="col-sm-6">
                    <strong><i class="bi bi-calendar me-2"></i>Fecha:</strong>
                    <p class="ms-4">{{ $monitoreoRed->fecha->format('d/m/Y') }}</p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-clock me-2"></i>Hora:</strong>
                    <p class="ms-4">{{ $monitoreoRed->hora }}</p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-person-gear me-2"></i>Responsable:</strong>
                    <p class="ms-4">
                        @if($monitoreoRed->usuarioResponsable)
                            {{ $monitoreoRed->usuarioResponsable->usuario }} — {{ $monitoreoRed->usuarioResponsable->nombres }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>

                <!-- Velocidades -->
                <div class="col-sm-6">
                    <strong><i class="bi bi-download me-2"></i>Velocidad de Descarga:</strong>
                    <p class="ms-4 {{ $descargaClass }} fw-bold fs-5">
                        {{ number_format($monitoreoRed->velocidad_descarga, 2) }} Mbps
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-upload me-2"></i>Velocidad de Subida:</strong>
                    <p class="ms-4 {{ $subidaClass }} fw-bold fs-5">
                        {{ number_format($monitoreoRed->velocidad_subida, 2) }} Mbps
                    </p>
                </div>

                <!-- Calidad -->
                <div class="col-sm-6">
                    <strong><i class="bi bi-wifi me-2"></i>Experiencia WiFi:</strong>
                    <p class="ms-4">
                        <span class="badge {{ $wifiBadge }} fs-6">
                            {{ $monitoreoRed->porcentaje_experiencia_wifi }}%
                        </span>
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-people me-2"></i>Clientes Conectados:</strong>
                    <p class="ms-4 fw-bold fs-5">{{ $monitoreoRed->clientes_conectados }}</p>
                </div>

                <!-- Observaciones -->
                <div class="col-12">
                    <strong><i class="bi bi-chat-text me-2"></i>Observaciones:</strong>
                    <p class="ms-4">
                        @if($monitoreoRed->observaciones)
                            {{ $monitoreoRed->observaciones }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Panel de Métricas -->
        <div class="col-md-4 border-start">
            <h6 class="fw-semibold mb-3">Resumen de Métricas</h6>
            <div class="d-grid gap-3">
                <div class="text-center p-3 bg-light rounded">
                    <i class="bi bi-graph-up-arrow display-6 text-primary d-block mb-2"></i>
                    <strong>Descarga</strong><br>
                    <span class="{{ $descargaClass }} fw-bold">{{ number_format($monitoreoRed->velocidad_descarga, 2) }} Mbps</span>
                </div>
                
                <div class="text-center p-3 bg-light rounded">
                    <i class="bi bi-graph-down-arrow display-6 text-info d-block mb-2"></i>
                    <strong>Subida</strong><br>
                    <span class="{{ $subidaClass }} fw-bold">{{ number_format($monitoreoRed->velocidad_subida, 2) }} Mbps</span>
                </div>
                
                <div class="text-center p-3 bg-light rounded">
                    <i class="bi bi-wifi display-6 text-success d-block mb-2"></i>
                    <strong>WiFi</strong><br>
                    <span class="fw-bold">{{ $monitoreoRed->porcentaje_experiencia_wifi }}%</span>
                </div>
            </div>

            <!-- Información de Auditoría -->
            <div class="mt-4 pt-3 border-top">
                <h6 class="fw-semibold mb-3">Información del Registro</h6>
                <div class="d-grid gap-2">
                    <div class="text-center p-2 bg-light rounded">
                        <i class="bi bi-clock-history text-muted me-1"></i>
                        <strong>Registrado:</strong><br>
                        {{ $monitoreoRed->created_at->format('d/m/Y H:i') }}
                    </div>
                    
                    @if($monitoreoRed->updated_at->ne($monitoreoRed->created_at))
                    <div class="text-center p-2 bg-light rounded">
                        <i class="bi bi-arrow-clockwise text-muted me-1"></i>
                        <strong>Última actualización:</strong><br>
                        {{ $monitoreoRed->updated_at->format('d/m/Y H:i') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-4 pt-3 border-top">
        <a href="{{ route('monitoreo_red.edit', $monitoreoRed) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Editar Monitoreo
        </a>
        <a href="{{ route('monitoreo_red.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver al Listado
        </a>
    </div>
</div>
@endsection