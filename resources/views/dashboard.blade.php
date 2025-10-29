@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Gestión TI')

@section('content')

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <!-- Encabezado -->
            <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
                <h1 class="h2 fw-bold text-dark">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard del Sistema
                </h1>
                <div class="btn-group flex-wrap gap-2">
                    <a href="{{ route('usuarios-ti.index') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i>Nuevo Usuario
                    </a>
                    <a href="{{ route('inventario-dispositivos.index') }}" class="btn btn-primary">
                        <i class="fas fa-laptop me-1"></i>Inventario
                    </a>
                    <a href="{{ route('licencias.index') }}" class="btn btn-primary">
                        <i class="fas fa-key me-1"></i>Licencias
                    </a>
                    <a href="{{ route('reporte_actividades.index') }}" class="btn btn-primary">
                        <i class="fas fa-clipboard-list me-1"></i>Reportes
                    </a>
                    <a href="{{ route('admin.configsistem.index') }}" class="btn btn-secondary">
                        <i class="fas fa-cog me-1"></i>Configuración
                    </a>
                    <a href="{{ route('bitacora_respaldo.index') }}" class="btn btn-primary">
                        <i class="fas fa-database me-1"></i>Bitácora Respaldos
                    </a>
                    <a href="{{ route('articulos.index') }}" class="btn btn-primary">
                        <i class="fas fa-boxes-stacked me-1"></i>Ver Artículos
                    </a>
                    <a href="{{ route('monitoreo-red.index') }}" class="btn btn-primary">
                        <i class="fas fa-network-wired me-1"></i>Monitoreo de Red
                    </a>
                </div>
            </div>

            <!-- Tarjetas resumen -->
            <div class="row g-4">
                <!-- Tarjeta Usuarios TI -->
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title text-dark mb-0">
                                    <i class="fas fa-users me-2"></i>Usuarios TI
                                </h5>
                                <div class="bg-accent rounded-circle p-2">
                                    <i class="fas fa-user-friends text-dark"></i>
                                </div>
                            </div>
                            <p class="card-text display-6 fw-bold text-dark mb-1">
                                {{ \App\Models\UsuarioTI::count() }}
                            </p>
                            <small class="text-muted">Usuarios registrados en el sistema</small>
                            <div class="mt-3">
                                <a href="{{ route('usuarios-ti.index') }}" class="btn btn-sm btn-primary">
                                    Ver todos <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Inventario -->
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title text-dark mb-0">
                                    <i class="fas fa-laptop me-2"></i>Inventario
                                </h5>
                                <div class="bg-accent rounded-circle p-2">
                                    <i class="fas fa-desktop text-dark"></i>
                                </div>
                            </div>
                            <p class="card-text display-6 fw-bold text-dark mb-1">
                                {{ \App\Models\InventarioDispositivo::count() }}
                            </p>
                            <small class="text-muted">Dispositivos registrados</small>
                            <div class="mt-3">
                                <a href="{{ route('inventario-dispositivos.index') }}" class="btn btn-sm btn-primary">
                                    Gestionar <i class="fas fa-tools ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Licencias -->
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title text-dark mb-0">
                                    <i class="fas fa-key me-2"></i>Licencias
                                </h5>
                                <div class="bg-accent rounded-circle p-2">
                                    <i class="fas fa-certificate text-dark"></i>
                                </div>
                            </div>
                            <p class="card-text display-6 fw-bold text-dark mb-1">
                                {{ \App\Models\Licencia::count() }}
                            </p>
                            <small class="text-muted">Licencias activas en el sistema</small>
                            <div class="mt-3">
                                <a href="{{ route('licencias.index') }}" class="btn btn-sm btn-primary">
                                    Ver licencias <i class="fas fa-list ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Accesos Rápidos -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-dark mb-4">
                                <i class="fas fa-rocket me-2"></i>Accesos Rápidos
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('usuarios-ti.create') }}" class="btn btn-outline-dark w-100 h-100 py-3">
                                        <i class="fas fa-user-plus fa-2x mb-2"></i>
                                        <br>
                                        <span>Nuevo Usuario</span>
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('inventario-dispositivos.create') }}" class="btn btn-outline-dark w-100 h-100 py-3">
                                        <i class="fas fa-laptop fa-2x mb-2"></i>
                                        <br>
                                        <span>Agregar Dispositivo</span>
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('licencias.create') }}" class="btn btn-outline-dark w-100 h-100 py-3">
                                        <i class="fas fa-key fa-2x mb-2"></i>
                                        <br>
                                        <span>Registrar Licencia</span>
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('reporte_actividades.create') }}" class="btn btn-outline-dark w-100 h-100 py-3">
                                        <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                        <br>
                                        <span>Nuevo Reporte</span>
                                    </a>
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