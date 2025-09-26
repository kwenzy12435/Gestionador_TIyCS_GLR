@extends('layouts.app')

@section('title', 'Detalle Usuario TI - Sistema de Gestión TI')

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
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a class="nav-link active" href="{{ route('usuarios-ti.index') }}">
                    <i class="fas fa-users me-2"></i>Usuarios TI
                </a>
                <a class="nav-link" href="{{ route('articulos.index') }}">
                    <i class="fas fa-boxes me-2"></i>Artículos
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="fas fa-user-circle me-2"></i>Detalle del Usuario
                </h1>
                <div>
                    <a href="{{ route('usuarios-ti.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="{{ route('usuarios-ti.edit', $usuarioTI) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="avatar bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 80px; height: 80px; font-size: 2rem;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <h4 class="card-title">{{ $usuarioTI->nombres }} {{ $usuarioTI->apellidos }}</h4>
                            <span class="badge bg-{{ $usuarioTI->rol == 'ADMIN' ? 'danger' : ($usuarioTI->rol == 'AUXILIAR-TI' ? 'warning' : 'primary') }} fs-6">
                                {{ $usuarioTI->rol }}
                            </span>
                            <p class="text-muted mt-2">{{ $usuarioTI->puesto ?? 'Puesto no especificado' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>Información Detallada
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-user me-2"></i>Usuario:</strong>
                                    <p class="mt-1">{{ $usuarioTI->usuario }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-id-card me-2"></i>Nombre Completo:</strong>
                                    <p class="mt-1">{{ $usuarioTI->nombres }} {{ $usuarioTI->apellidos }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-briefcase me-2"></i>Puesto:</strong>
                                    <p class="mt-1">{{ $usuarioTI->puesto ?? 'No especificado' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-phone me-2"></i>Teléfono:</strong>
                                    <p class="mt-1">{{ $usuarioTI->telefono ?? 'No especificado' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-shield-alt me-2"></i>Rol:</strong>
                                    <p class="mt-1">
                                        <span class="badge bg-{{ $usuarioTI->rol == 'ADMIN' ? 'danger' : ($usuarioTI->rol == 'AUXILIAR-TI' ? 'warning' : 'primary') }}">
                                            {{ $usuarioTI->rol }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-calendar me-2"></i>Fecha de Registro:</strong>
                                    <p class="mt-1">{{ $usuarioTI->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection