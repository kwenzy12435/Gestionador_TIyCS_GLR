@extends('layouts.app')

@section('title', 'Ver Artículo - Sistema de Gestión TI')

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
                        <a class="nav-link" href="{{ route('articulos.index') }}">
                            <i class="fas fa-box me-2"></i>
                            Artículos
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Detalles del Artículo #{{ $articulo->id }}</h1>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('articulos.edit', $articulo->id) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('articulos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Información del Artículo</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Nombre:</strong>
                            <span>{{ $articulo->nombre }}</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Categoría ID:</strong>
                            <span class="badge bg-secondary">{{ $articulo->categoria_id }}</span>
                            @if($categoria)
                                <br><small class="text-muted">Nombre: {{ $categoria->nombre }}</small>
                            @endif
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Subcategoría ID:</strong>
                            <span>
                                @if($articulo->subcategoria_id)
                                    <span class="badge bg-secondary">{{ $articulo->subcategoria_id }}</span>
                                    @if($subcategoria)
                                        <br><small class="text-muted">Nombre: {{ $subcategoria->nombre }}</small>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Cantidad:</strong>
                            <span>{{ $articulo->cantidad }} {{ $articulo->unidades }}</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Ubicación:</strong>
                            <span class="badge bg-info">{{ ucfirst($articulo->ubicacion) }}</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Estado:</strong>
                            @if($articulo->estado == 'Disponible')
                                <span class="badge bg-success">Disponible</span>
                            @elseif($articulo->estado == 'no disponible')
                                <span class="badge bg-danger">No Disponible</span>
                            @else
                                <span class="badge bg-warning">Pocas Piezas</span>
                            @endif
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Fecha de Ingreso:</strong>
                            <span>{{ $articulo->fecha_ingreso->format('d/m/Y') }}</span>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <strong>Descripción:</strong>
                            <p class="text-muted">{{ $articulo->descripcion ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Fecha de Creación:</strong>
                            <span>{{ $articulo->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Última Actualización:</strong>
                            <span>{{ $articulo->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection