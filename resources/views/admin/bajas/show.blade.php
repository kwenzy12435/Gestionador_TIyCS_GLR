@extends('layouts.app')
@section('title', 'Detalle de Baja - Administración')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-archive me-2"></i>Detalle de Baja</h1>
<small class="text-muted">Registro #{{ $baja->id }}</small>
@endsection

@section('content')
<div class="card p-4 shadow-sm">
    <div class="row">
        <div class="col-md-6">
            <!-- Información del Equipo -->
            <h5 class="fw-bold text-brand mb-3">
                <i class="bi bi-pc-display me-2"></i>Información del Equipo
            </h5>
            
            <div class="border-start border-3 border-primary ps-3 mb-4">
                <p class="mb-2">
                    <strong>Tipo:</strong><br>
                    <span class="badge bg-primary">{{ $baja->tipo ?? '—' }}</span>
                </p>
                <p class="mb-2">
                    <strong>Marca:</strong><br>
                    {{ $baja->marca_nombre ?? '—' }}
                </p>
                <p class="mb-2">
                    <strong>Modelo:</strong><br>
                    <strong>{{ $baja->modelo ?? '—' }}</strong>
                </p>
                <p class="mb-2">
                    <strong>Número de Serie:</strong><br>
                    <code class="text-primary fs-5">{{ $baja->numero_serie ?? '—' }}</code>
                </p>
                <p class="mb-0">
                    <strong>Dirección MAC:</strong><br>
                    @if($baja->mac_address)
                        <code class="text-success">{{ $baja->mac_address }}</code>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </p>
            </div>

            <!-- Información de la Baja -->
            <h5 class="fw-bold text-brand mb-3">
                <i class="bi bi-calendar-x me-2"></i>Información de la Baja
            </h5>
            
            <div class="border-start border-3 border-warning ps-3">
                <p class="mb-2">
                    <strong>Fecha de Baja:</strong><br>
                    {{ $baja->fecha ? \Carbon\Carbon::parse($baja->fecha)->format('d/m/Y') : '—' }}
                </p>
                <p class="mb-2">
                    <strong>Razón de Baja:</strong><br>
                    {{ $baja->razon_baja ?? '—' }}
                </p>
                <p class="mb-0">
                    <strong>Observaciones:</strong><br>
                    {{ $baja->observaciones ?? '—' }}
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Información del Usuario Asociado -->
            <h5 class="fw-bold text-brand mb-3">
                <i class="bi bi-person me-2"></i>Usuario Asociado
            </h5>
            
            <div class="border-start border-3 border-info ps-3 mb-4">
                <p class="mb-2">
                    <strong>Nombre:</strong><br>
                    {{ $baja->usuario_nombre ?? '—' }}
                </p>
                <p class="mb-0">
                    <strong>ID de Registro:</strong><br>
                    <code>{{ $baja->registro_id ?? '—' }}</code>
                </p>
            </div>

            <!-- Información del Responsable TI -->
            <h5 class="fw-bold text-brand mb-3">
                <i class="bi bi-person-gear me-2"></i>Responsable TI
            </h5>
            
            <div class="border-start border-3 border-success ps-3 mb-4">
                <p class="mb-2">
                    <strong>Usuario:</strong><br>
                    {{ $baja->ti_usuario ?? '—' }}
                </p>
                <p class="mb-2">
                    <strong>Nombre Completo:</strong><br>
                    {{ $baja->ti_nombres ?? '' }} {{ $baja->ti_apellidos ?? '' }}
                </p>
                <p class="mb-2">
                    <strong>Puesto:</strong><br>
                    {{ $baja->ti_puesto ?? '—' }}
                </p>
                <p class="mb-2">
                    <strong>Teléfono:</strong><br>
                    {{ $baja->ti_telefono ?? '—' }}
                </p>
                <p class="mb-0">
                    <strong>Email:</strong><br>
                    @if($baja->ti_email)
                        <a href="mailto:{{ $baja->ti_email }}" class="text-decoration-none">
                            {{ $baja->ti_email }}
                        </a>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </p>
            </div>

            <!-- Información de Auditoría -->
            <h5 class="fw-bold text-brand mb-3">
                <i class="bi bi-clock-history me-2"></i>Información de Auditoría
            </h5>
            
            <div class="border-start border-3 border-secondary ps-3">
                <p class="mb-2">
                    <strong>Registro Creado:</strong><br>
                    {{ \Carbon\Carbon::parse($baja->created_at)->format('d/m/Y H:i') }}
                </p>
                @if($baja->updated_at && $baja->updated_at != $baja->created_at)
                <p class="mb-0">
                    <strong>Última Actualización:</strong><br>
                    {{ \Carbon\Carbon::parse($baja->updated_at)->format('d/m/Y H:i') }}
                </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="text-end mt-4 pt-3 border-top">
        <a href="{{ route('admin.bajas.index', request()->query()) }}" 
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver al Listado
        </a>
        <a href="{{ route('admin.bajas.export.pdf', array_merge(request()->query(), ['id' => $baja->id])) }}" 
           class="btn btn-outline-danger" 
           target="_blank">
            <i class="bi bi-filetype-pdf me-1"></i> Exportar PDF
        </a>
    </div>
</div>
@endsection