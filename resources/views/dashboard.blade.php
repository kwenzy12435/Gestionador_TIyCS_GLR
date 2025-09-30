@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Gestión TI')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <h5 class="px-3 mb-3 text-muted">
                    <i class="fas fa-bars me-2"></i> Menú
                </h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('usuarios-ti.index') }}">
                            <i class="fas fa-users me-2"></i>Usuarios TI
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inventario-dispositivos.index') }}">
                            <i class="fas fa-laptop me-2"></i>Inventario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('licencias.index') }}">
                            <i class="fas fa-key me-2"></i>Licencias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-headset me-2"></i>Soporte Técnico
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.bajas.index') }}">
                            <i class="fas fa-history me-2"></i>Historial de Bajas
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <!-- Encabezado -->
            <div class="d-flex justify-content-between align-items-center pb-2 mb-4 border-bottom">
                <h1 class="h3 fw-bold text-primary">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </h1>
                <div class="btn-group">
                    <a href="{{ route('usuarios-ti.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-user-plus me-1"></i>Nuevo Usuario
                    </a>
                    <a href="{{ route('inventario-dispositivos.index') }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-laptop me-1"></i>Inventario
                    </a>
                    <a href="{{ route('licencias.index') }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-key me-1"></i>Licencias
                    </a>
                    <a href="{{ route('reporte_actividades.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-clipboard-list me-1"></i>Reportes
                    </a>
                    <a href="{{ route('admin.configsistem.index') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-cog me-1"></i>Configuración
                    </a>
                    @can('create', App\Models\BitacoraRespaldo::class)
    <a href="{{ route('bitacora_respaldo.create') }}" class="btn btn-primary">
        Crear Nueva Bitácora
    </a>
@endcan
<a href="{{ route('articulos.index') }}" class="btn btn-primary">
    Ver Artículos
</a>
                   <a href="{{ route('monitoreo-red.index') }}" class="btn btn-primary">
                     Monitoreo de Red
                    </a>
                </div>
            </div>

            <!-- Tarjetas resumen -->
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 text-white bg-primary h-100">
                        <div class="card-body">
                            <h5 class="card-title">Usuarios TI</h5>
                            <p class="card-text fs-4 fw-bold">{{ \App\Models\UsuarioTI::count() }}</p>
                            <small>Registrados en el sistema</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 text-white bg-success h-100">
                        <div class="card-body">
                            <h5 class="card-title">Inventario</h5>
                            <p class="card-text fs-4 fw-bold">{{ \App\Models\InventarioDispositivo::count() }}</p>
                            <small>Dispositivos registrados</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 text-white bg-warning h-100">
                        <div class="card-body">
                            <h5 class="card-title">Licencias</h5>
                            <p class="card-text fs-4 fw-bold">{{ \App\Models\Licencia::count() }}</p>
                            <small>Licencias activas</small>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
