@extends('layouts.app')

@section('title', 'Detalle Dispositivo - Sistema de Gestión TI')

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
            <!-- Mismo sidebar que index -->
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Detalle del Dispositivo</h1>
                <div>
                    <a href="{{ route('inventario-dispositivos.edit', $dispositivo->id) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('inventario-dispositivos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información General</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Modelo:</th>
                                    <td>{{ $dispositivo->modelo }}</td>
                                </tr>
                                <tr>
                                    <th>Marca:</th>
                                    <td>{{ $dispositivo->marca_nombre ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tipo:</th>
                                    <td>{{ $dispositivo->tipo_nombre ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Número de Serie:</th>
                                    <td>{{ $dispositivo->numero_serie }}</td>
                                </tr>
                                <tr>
                                    <th>Serie:</th>
                                    <td>{{ $dispositivo->serie ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $dispositivo->estado == 'nuevo' ? 'success' : 
                                            ($dispositivo->estado == 'asignado' ? 'primary' : 
                                            ($dispositivo->estado == 'reparación' ? 'warning' : 'danger')) 
                                        }}">
                                            {{ ucfirst($dispositivo->estado) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Especificaciones Técnicas</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>MAC:</th>
                                    <td>{{ $dispositivo->mac ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Procesador:</th>
                                    <td>{{ $dispositivo->procesador ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Memoria RAM:</th>
                                    <td>{{ $dispositivo->memoria_ram ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>SSD:</th>
                                    <td>{{ $dispositivo->ssd ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>HDD:</th>
                                    <td>{{ $dispositivo->hdd ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Color:</th>
                                    <td>{{ $dispositivo->color ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Información Económica</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Costo:</th>
                                    <td>{{ $dispositivo->costo ? '$ ' . number_format($dispositivo->costo, 2) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha de Compra:</th>
                                    <td>{{ $dispositivo->fecha_compra ? \Carbon\Carbon::parse($dispositivo->fecha_compra)->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Garantía hasta:</th>
                                    <td>{{ $dispositivo->garantia_hasta ? \Carbon\Carbon::parse($dispositivo->garantia_hasta)->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Asignación</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Asignado a:</th>
                                    <td>
                                        @if($dispositivo->colaborador_nombre)
                                            {{ $dispositivo->colaborador_nombre }} {{ $dispositivo->colaborador_apellidos }}
                                            @if($dispositivo->colaborador_puesto)
                                                ({{ $dispositivo->colaborador_puesto }})
                                            @endif
                                        @else
                                            No asignado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha Creación:</th>
                                    <td>{{ \Carbon\Carbon::parse($dispositivo->created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Última Actualización:</th>
                                    <td>{{ \Carbon\Carbon::parse($dispositivo->updated_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection