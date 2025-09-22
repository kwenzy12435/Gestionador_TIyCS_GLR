@extends('layouts.app')

@section('title', 'Detalles Completos de Baja - Sistema TI')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Detalles Completos de Baja #{{ $baja->id }}
                        </h4>
                        <div>
                            <a href="{{ route('admin.bajas.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Volver al Listado
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Información Principal -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Información de la Baja</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%" class="text-end pe-3">ID del Log:</th>
                                            <td><strong class="text-primary">#{{ $baja->id }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Tabla Afectada:</th>
                                            <td><span class="badge bg-dark">{{ $baja->tabla_afectada }}</span></td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Registro ID Eliminado:</th>
                                            <td><code class="text-danger">#{{ $baja->registro_id }}</code></td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Acción Realizada:</th>
                                            <td><span class="badge bg-danger">{{ $baja->accion }}</span></td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Fecha de Baja:</th>
                                            <td>
                                                <i class="fas fa-calendar text-muted me-1"></i>
                                                {{ \Carbon\Carbon::parse($baja->fecha)->format('d/m/Y H:i:s') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Creado en Sistema:</th>
                                            <td>
                                                <i class="fas fa-clock text-muted me-1"></i>
                                                {{ \Carbon\Carbon::parse($baja->created_at)->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                        @if($baja->updated_at)
                                        <tr>
                                            <th class="text-end pe-3">Última Actualización:</th>
                                            <td>
                                                <i class="fas fa-sync text-muted me-1"></i>
                                                {{ \Carbon\Carbon::parse($baja->updated_at)->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Usuario TI -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Usuario TI que Realizó la Baja</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%" class="text-end pe-3">Usuario TI ID:</th>
                                            <td>
                                                @if($baja->usuario_ti_id)
                                                    <code>#{{ $baja->usuario_ti_id }}</code>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Nombre Completo:</th>
                                            <td>
                                                @if($baja->usuario_nombre_completo)
                                                    <strong>{{ $baja->usuario_nombre_completo }}</strong>
                                                @else
                                                    <span class="text-muted">No especificado</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Usuario del Sistema:</th>
                                            <td>
                                                @if($baja->ti_usuario)
                                                    <code>{{ $baja->ti_usuario }}</code>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Nombres:</th>
                                            <td>{{ $baja->ti_nombres ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Apellidos:</th>
                                            <td>{{ $baja->ti_apellidos ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Puesto:</th>
                                            <td>
                                                @if($baja->ti_puesto)
                                                    <span class="badge bg-info">{{ $baja->ti_puesto }}</span>
                                                @else
                                                    <span class="text-muted">No especificado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Dispositivo -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Información del Dispositivo Eliminado</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%" class="text-end pe-3">Estado al Eliminar:</th>
                                            <td><span class="badge bg-warning">{{ $baja->estado_texto }}</span></td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Marca:</th>
                                            <td>
                                                @if($baja->marca_nombre)
                                                    <strong>{{ $baja->marca_nombre }}</strong>
                                                    @if($baja->marca_id)
                                                        <br><small class="text-muted">ID: {{ $baja->marca_id }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Modelo:</th>
                                            <td>{{ $baja->modelo ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Número de Serie:</th>
                                            <td>
                                                @if($baja->numero_serie)
                                                    <code class="fs-6">{{ $baja->numero_serie }}</code>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">MAC Address:</th>
                                            <td>
                                                @if($baja->mac_address)
                                                    <code>{{ $baja->mac_address }}</code>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Usuario Asignado:</th>
                                            <td>
                                                @if($baja->usuario_nombre)
                                                    <span class="badge bg-success">{{ $baja->usuario_nombre }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Sin asignar</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Información Adicional -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0">Información Adicional</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%" class="text-end pe-3">Fecha Última Edición:</th>
                                            <td>
                                                @if($baja->fecha_ultima_edicion)
                                                    {{ \Carbon\Carbon::parse($baja->fecha_ultima_edicion)->format('d/m/Y H:i') }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Tipo de Registro:</th>
                                            <td><span class="badge bg-dark">Log de Baja</span></td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Base de Datos:</th>
                                            <td><code>gestion_tiycs_glr</code></td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Tabla de Origen:</th>
                                            <td><code>inventario_dispositivos</code></td>
                                        </tr>
                                        <tr>
                                            <th class="text-end pe-3">Registro Eliminado:</th>
                                            <td><code>ID #{{ $baja->registro_id }}</code></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos RAW (para debugging) -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-database me-2"></i>Datos RAW del Registro
                                        <small class="float-end">(Para fines de auditoría)</small>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <pre class="bg-light p-3 rounded small" style="max-height: 300px; overflow-y: auto;"><code>@php
echo json_encode($baja, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
@endphp</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Información de Auditoría:</strong> Este registro no puede ser modificado ni eliminado.
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.bajas.index') }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-list me-1"></i>Volver al Listado
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table-borderless th {
    font-weight: 600;
    color: #495057;
}
.table-borderless td {
    color: #6c757d;
}
pre code {
    font-family: 'Courier New', monospace;
    font-size: 12px;
}
</style>
@endsection