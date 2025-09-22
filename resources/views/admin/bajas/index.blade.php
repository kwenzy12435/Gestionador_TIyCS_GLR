@extends('layouts.app')

@section('title', 'Historial de Bajas - Sistema TI')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-history me-2"></i>Historial de Bajas de Dispositivos
                        </h4>
                        <div>
                            <a href="{{ route('admin.bajas.export.pdf') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros de búsqueda -->
                    <form action="{{ route('admin.bajas.search') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Buscar por modelo, serie, usuario..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="fecha_desde" class="form-control" 
                                       value="{{ request('fecha_desde') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="fecha_hasta" class="form-control" 
                                       value="{{ request('fecha_hasta') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i>Buscar
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($bajas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="80">ID</th>
                                        <th>Fecha</th>
                                        <th>Usuario TI</th>
                                        <th>Dispositivo</th>
                                        <th>Modelo/Serie</th>
                                        <th>Usuario Asignado</th>
                                        <th>Estado</th>
                                        <th width="100">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bajas as $baja)
                                    <tr>
                                        <td><strong>#{{ $baja->id }}</strong></td>
                                        <td>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($baja->fecha)->format('d/m/Y') }}<br>
                                                {{ \Carbon\Carbon::parse($baja->fecha)->format('H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $baja->ti_nombres }} {{ $baja->ti_apellidos }}</div>
                                            <small class="text-muted">{{ $baja->ti_usuario }}</small>
                                            @if($baja->ti_puesto)
                                                <br><span class="badge bg-info">{{ $baja->ti_puesto }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $baja->marca_nombre }}</div>
                                            <small class="text-muted">{{ $baja->modelo }}</small>
                                        </td>
                                        <td>
                                            @if($baja->numero_serie)
                                                <code>{{ $baja->numero_serie }}</code>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($baja->usuario_nombre)
                                                <span class="badge bg-success">{{ $baja->usuario_nombre }}</span>
                                            @else
                                                <span class="badge bg-secondary">Sin asignar</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ $baja->estado_texto }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.bajas.show', $baja->id) }}" 
                                               class="btn btn-sm btn-primary" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Mostrando {{ $bajas->firstItem() }} - {{ $bajas->lastItem() }} de {{ $bajas->total() }} registros
                            </div>
                            {{ $bajas->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <h5>No se encontraron registros de bajas</h5>
                            <p class="mb-0">No hay dispositivos dados de baja en el sistema.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection