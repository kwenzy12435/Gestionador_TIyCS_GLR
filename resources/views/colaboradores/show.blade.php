@extends('layouts.app')
@section('title', 'Detalle de Colaborador')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-person-badge me-2"></i>Detalle del Colaborador</h1>
@endsection

@section('content')
<div class="card p-4 shadow-sm">
    <div class="row">
        <div class="col-md-8">
            <h4 class="fw-bold text-brand mb-4">{{ $colaborador->nombre }} {{ $colaborador->apellidos }}</h4>
            
            <div class="row g-3">
                <div class="col-sm-6">
                    <strong><i class="bi bi-person me-2"></i>Usuario:</strong>
                    <p class="ms-4">
                        <code class="fs-5 text-primary">{{ $colaborador->usuario }}</code>
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-card-text me-2"></i>Nombre Completo:</strong>
                    <p class="ms-4 fs-5">{{ $colaborador->nombre }} {{ $colaborador->apellidos }}</p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-briefcase me-2"></i>Puesto:</strong>
                    <p class="ms-4">
                        @if($colaborador->puesto)
                            <span class="badge bg-primary fs-6">{{ $colaborador->puesto }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-building me-2"></i>Departamento:</strong>
                    <p class="ms-4">
                        @if($colaborador->departamento)
                            <span class="badge bg-info text-dark fs-6">{{ $colaborador->departamento->nombre }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
                <div class="col-12">
                    <strong><i class="bi bi-display me-2"></i>AnyDesk ID:</strong>
                    <p class="ms-4">
                        @if($colaborador->anydesk_id)
                            <code class="fs-5 text-success">{{ $colaborador->anydesk_id }}</code>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 border-start">
            <h6 class="fw-semibold mb-3">Información Adicional</h6>
            <div class="d-grid gap-2">
                <div class="text-center p-3 bg-light rounded">
                    <i class="bi bi-clock-history display-6 text-muted d-block mb-2"></i>
                    <strong>Registrado el:</strong><br>
                    {{ $colaborador->created_at->format('d/m/Y H:i') }}
                </div>
                
                @if($colaborador->updated_at->ne($colaborador->created_at))
                <div class="text-center p-3 bg-light rounded">
                    <i class="bi bi-arrow-clockwise display-6 text-muted d-block mb-2"></i>
                    <strong>Última actualización:</strong><br>
                    {{ $colaborador->updated_at->format('d/m/Y H:i') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="text-end mt-4 pt-3 border-top">
        <a href="{{ route('colaboradores.edit', $colaborador) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Editar Colaborador
        </a>
        <a href="{{ route('colaboradores.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver al Listado
        </a>
    </div>
</div>
@endsection