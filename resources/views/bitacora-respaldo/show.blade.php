@extends('layouts.app')
@section('title', 'Detalle de Registro de Respaldo')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-eye me-2"></i>Detalle del Registro de Respaldo</h1>
@endsection

@section('content')
@php
    $badgeEmpresa = match($bitacoraRespaldo->empresa_id) {
        'contabilidad' => 'bg-primary',
        'nomina' => 'bg-info text-dark',
        default => 'bg-secondary'
    };
    
    $badgeEstado = match(strtolower($bitacoraRespaldo->estado)) {
        'hecho' => 'bg-success',
        'no hecho' => 'bg-warning text-dark',
        default => 'bg-secondary'
    };
    
    $respaldos = [];
    if ($bitacoraRespaldo->respaldo_contabilidad) $respaldos[] = 'Contabilidad';
    if ($bitacoraRespaldo->respaldo_nominas) $respaldos[] = 'Nóminas';
@endphp

<div class="card p-4 shadow-sm">
    <div class="row">
        <div class="col-md-8">
            <h4 class="fw-bold text-brand mb-4">Información del Respaldo</h4>
            
            <div class="row g-3">
                <div class="col-sm-6">
                    <strong><i class="bi bi-building me-2"></i>Empresa:</strong>
                    <p class="ms-4">
                        <span class="badge {{ $badgeEmpresa }} text-uppercase fs-6">
                            {{ $bitacoraRespaldo->empresa_id }}
                        </span>
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-calendar-check me-2"></i>Fecha de Respaldo:</strong>
                    <p class="ms-4">{{ $bitacoraRespaldo->fecha_respaldo->format('d/m/Y') }}</p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-check-circle me-2"></i>Estado:</strong>
                    <p class="ms-4">
                        <span class="badge {{ $badgeEstado }} fs-6">
                            {{ $bitacoraRespaldo->estado }}
                        </span>
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-geo-alt me-2"></i>Ubicación de Guardado:</strong>
                    <p class="ms-4">{{ $bitacoraRespaldo->ubicacion_guardado ?? '—' }}</p>
                </div>
                <div class="col-12">
                    <strong><i class="bi bi-person-gear me-2"></i>Responsable TI:</strong>
                    <p class="ms-4">
                        @if($bitacoraRespaldo->usuarioTi)
                            <strong>{{ $bitacoraRespaldo->usuarioTi->usuario }}</strong> — 
                            {{ $bitacoraRespaldo->usuarioTi->nombres }} {{ $bitacoraRespaldo->usuarioTi->apellidos }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
                <div class="col-12">
                    <strong><i class="bi bi-hdd-stack me-2"></i>Respaldos Realizados:</strong>
                    <p class="ms-4">
                        @if(count($respaldos) > 0)
                            @foreach($respaldos as $respaldo)
                                <span class="badge bg-light text-dark me-1">{{ $respaldo }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
                <div class="col-12">
                    <strong><i class="bi bi-chat-dots me-2"></i>Acciones Alternativas:</strong>
                    <p class="ms-4">{{ $bitacoraRespaldo->acciones_alternativas ?? '—' }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 border-start">
            <h6 class="fw-semibold mb-3">Información Adicional</h6>
            <div class="d-grid gap-2">
                <div class="text-center p-3 bg-light rounded">
                    <i class="bi bi-clock-history display-6 text-muted d-block mb-2"></i>
                    <strong>Registrado el:</strong><br>
                    {{ $bitacoraRespaldo->created_at->format('d/m/Y H:i') }}
                </div>
                
                @if($bitacoraRespaldo->updated_at->ne($bitacoraRespaldo->created_at))
                <div class="text-center p-3 bg-light rounded">
                    <i class="bi bi-arrow-clockwise display-6 text-muted d-block mb-2"></i>
                    <strong>Última actualización:</strong><br>
                    {{ $bitacoraRespaldo->updated_at->format('d/m/Y H:i') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="text-end mt-4 pt-3 border-top">
        <a href="{{ route('bitacora-respaldo.edit', $bitacoraRespaldo) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Editar Registro
        </a>
        <a href="{{ route('bitacora-respaldo.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver al Listado
        </a>
    </div>
</div>
@endsection