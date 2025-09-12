@extends('layouts.app')

@section('title', 'Detalle Reporte - Sistema de Gestión TI')

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
                <h1 class="h2">Detalle del Reporte</h1>
                <div>
                    <a href="{{ route('reporte_actividades.edit', $reporte->id) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('reporte_actividades.index') }}" class="btn btn-secondary">
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
                                    <th>Fecha:</th>
                                    <td>{{ $reporte->fecha->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Actividad:</th>
                                    <td>{{ $reporte->actividad }}</td>
                                </tr>
                                <tr>
                                    <th>Colaborador:</th>
                                    <td>
                                        @if($reporte->colaborador)
                                            {{ $reporte->colaborador->nombre }} {{ $reporte->colaborador->apellidos }}
                                            ({{ $reporte->colaborador->puesto }})
                                        @else
                                            No especificado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Técnico TI:</th>
                                    <td>{{ $reporte->usuarioTi->nombres ?? 'No especificado' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Detalles Adicionales</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Canal:</th>
                                    <td>{{ $reporte->canal->nombre ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <th>Naturaleza:</th>
                                    <td>{{ $reporte->naturaleza->nombre ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha Creación:</th>
                                    <td>{{ $reporte->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Última Actualización:</th>
                                    <td>{{ $reporte->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Descripción Completa</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-text">{{ $reporte->descripcion }}</p>
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