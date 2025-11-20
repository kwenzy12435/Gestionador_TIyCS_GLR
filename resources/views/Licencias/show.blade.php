@extends('layouts.app')
@section('title', 'Detalle de Licencia')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-eye me-2"></i>Detalle de Licencia</h1>
@endsection

@section('content')
@php
    $exp = $licencia->expiracion ? \Carbon\Carbon::parse($licencia->expiracion) : null;
    $days = $exp ? now()->diffInDays($exp, false) : null;
    $badge = match(true) {
        !$exp => 'bg-secondary',
        $days < 0 => 'bg-danger',
        $days <= 7 => 'bg-warning text-dark',
        default => 'bg-success'
    };
@endphp

<div class="card p-4 shadow-sm">
    <div class="row">
        <div class="col-md-8">
            <h4 class="fw-bold text-brand mb-4">{{ $licencia->cuenta }}</h4>
            
            <div class="row g-3">
                <!-- Información Principal -->
                <div class="col-sm-6">
                    <strong><i class="bi bi-person-badge me-2"></i>Cuenta:</strong>
                    <p class="ms-4">
                        <code class="fs-5 text-primary">{{ $licencia->cuenta }}</code>
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-window-stack me-2"></i>Plataforma:</strong>
                    <p class="ms-4">
                        @if($licencia->plataforma)
                            <span class="badge bg-info text-dark fs-6">{{ $licencia->plataforma->nombre }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-person me-2"></i>Asignado a:</strong>
                    <p class="ms-4">
                        @if($licencia->colaborador)
                            <div>
                                <strong>{{ $licencia->colaborador->nombre }} {{ $licencia->colaborador->apellidos }}</strong> <!-- ✅ Corregido: nombres → nombre -->
                                <br>
                                <small class="text-muted">{{ $licencia->colaborador->email }}</small>
                            </div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-calendar-check me-2"></i>Expiración:</strong>
                    <p class="ms-4">
                        <span class="badge {{ $badge }} fs-6">
                            @if($exp)
                                {{ $exp->format('d/m/Y') }} ({{ $days < 0 ? $days : "+$days" }} días)
                            @else
                                Sin expiración
                            @endif
                        </span>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Panel de Acciones -->
        <div class="col-md-4 border-start">
            <h6 class="fw-semibold mb-3">Acciones Rápidas</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('licencias.ver-contrasena', $licencia) }}" 
                   class="btn btn-outline-dark text-start">
                    <i class="bi bi-shield-lock me-2"></i>Ver Contraseña
                </a>
              
                
                @if($exp && $days <= 30)
                <div class="alert alert-warning mt-2 mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <small>Esta licencia expira pronto</small>
                </div>
                @endif
            </div>

            <!-- Información del Registro -->
            <div class="mt-4 pt-3 border-top">
                <h6 class="fw-semibold mb-3">Información del Registro</h6>
                <div class="d-grid gap-2">
                    <div class="text-center p-2 bg-light rounded">
                        <i class="bi bi-clock-history text-muted me-1"></i>
                        <strong>Registrado:</strong><br>
                        {{ $licencia->created_at->format('d/m/Y H:i') }}
                    </div>
                    
                    @if($licencia->updated_at->ne($licencia->created_at))
                    <div class="text-center p-2 bg-light rounded">
                        <i class="bi bi-arrow-clockwise text-muted me-1"></i>
                        <strong>Última actualización:</strong><br>
                        {{ $licencia->updated_at->format('d/m/Y H:i') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-4 pt-3 border-top">
        <a href="{{ route('licencias.edit', $licencia) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Editar Licencia
        </a>
        <a href="{{ route('licencias.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver al Listado
        </a>
    </div>
</div>
@endsection