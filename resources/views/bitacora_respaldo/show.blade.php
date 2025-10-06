@extends('layouts.app')

@section('title', 'Ver Registro - Bitácora Respaldos')

@section('content')


<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('bitacora_respaldo.index') }}">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Bitácora Respaldos
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Detalles del Registro #{{ $bitacora->id }}</h1>
                <div>
                    <a href="{{ route('bitacora_respaldo.edit', $bitacora->id) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('bitacora_respaldo.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Información del Respaldo</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Empresa:</strong>
                            <span class="badge bg-info">{{ ucfirst($bitacora->empresa_id) }}</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Usuario TI:</strong>
                            <span>
                                @if($bitacora->usuarioTi)
                                    {{ $bitacora->usuarioTi->nombres }} {{ $bitacora->usuarioTi->apellido }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Respaldo Nóminas:</strong>
                            <span class="badge {{ $bitacora->respaldo_nominas ? 'bg-success' : 'bg-secondary' }}">
                                {{ $bitacora->respaldo_nominas ? 'Sí' : 'No' }}
                            </span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Respaldo Contabilidad:</strong>
                            <span class="badge {{ $bitacora->respaldo_contabilidad ? 'bg-success' : 'bg-secondary' }}">
                                {{ $bitacora->respaldo_contabilidad ? 'Sí' : 'No' }}
                            </span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Fecha de Respaldo:</strong>
                            <span>{{ $bitacora->fecha_respaldo->format('d/m/Y') }}</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Estado:</strong>
                            <span class="badge {{ $bitacora->estado == 'Hecho' ? 'bg-success' : 'bg-warning' }}">
                                {{ $bitacora->estado }}
                            </span>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <strong>Ubicación de Guardado:</strong>
                            <p class="text-muted">{{ $bitacora->ubicacion_guardado ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <strong>Acciones Alternativas:</strong>
                            <p class="text-muted">{{ $bitacora->acciones_alternativas ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Fecha de Creación:</strong>
                            <span>{{ $bitacora->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <strong>Última Actualización:</strong>
                            <span>{{ $bitacora->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection