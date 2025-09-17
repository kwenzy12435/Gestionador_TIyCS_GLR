@extends('layouts.app')

@section('title', 'Ver Monitoreo de Red - Sistema de Gestión TI')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-server me-2"></i>Sistema Gestión TI
        </a>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('monitoreo-red.index') }}">
                            <i class="fas fa-network-wired me-2"></i>
                            Monitoreo Red
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Detalles del Monitoreo #{{ $monitoreo->id }}</h1>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('monitoreo-red.edit', $monitoreo->id) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('monitoreo-red.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Información del Monitoreo</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Fecha:</strong>
                            <span>{{ $monitoreo->fecha->format('d/m/Y') }}</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Hora:</strong>
                            <span>{{ $monitoreo->hora }}</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Velocidad de Descarga:</strong>
                            <span class="badge bg-info">{{ $monitoreo->velocidad_descarga }} Mbps</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Velocidad de Subida:</strong>
                            <span class="badge bg-primary">{{ $monitoreo->velocidad_subida }} Mbps</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Experiencia Wi-Fi:</strong>
                            @if($monitoreo->porcentaje_experiencia_wifi >= 80)
                                <span class="badge bg-success">{{ $monitoreo->porcentaje_experiencia_wifi }}%</span>
                            @elseif($monitoreo->porcentaje_experiencia_wifi >= 50)
                                <span class="badge bg-warning">{{ $monitoreo->porcentaje_experiencia_wifi }}%</span>
                            @else
                                <span class="badge bg-danger">{{ $monitoreo->porcentaje_experiencia_wifi }}%</span>
                            @endif
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Clientes Conectados:</strong>
                            <span class="badge bg-secondary">{{ $monitoreo->clientes_conectados }}</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Responsable:</strong>
                            <span>
                                @if($monitoreo->usuarioResponsable)
                                    {{ $monitoreo->usuarioResponsable->nombres }} {{ $monitoreo->usuarioResponsable->apellidos }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <strong>Observaciones:</strong>
                            <p class="text-muted">{{ $monitoreo->observaciones ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Fecha de Creación:</strong>
                            <span>{{ $monitoreo->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Última Actualización:</strong>
                            <span>{{ $monitoreo->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection