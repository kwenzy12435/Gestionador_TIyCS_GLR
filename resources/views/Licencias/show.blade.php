@extends('layouts.app')

@section('title', 'Detalle Licencia - Sistema de Gestión TI')

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
                <h1 class="h2">Detalle de la Licencia</h1>
                <div>
                    <a href="{{ route('licencias.edit', $licencia->id) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('licencias.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información de la Cuenta</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Cuenta:</th>
                                    <td>{{ $licencia->cuenta }}</td>
                                </tr>
                                <tr>
                                    <th>Contraseña:</th>
                                    <td>{{ $licencia->contrasena }}</td>
                                </tr>
                                <tr>
                                    <th>Plataforma:</th>
                                    <td>{{ $licencia->plataforma->nombre ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Información de Asignación</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Asignado a:</th>
                                    <td>
                                        @if($licencia->colaborador)
                                            {{ $licencia->colaborador->nombre }} {{ $licencia->colaborador->apellidos }}
                                            ({{ $licencia->colaborador->puesto }})
                                        @else
                                            Sin asignar
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha de Expiración:</th>
                                    <td>{{ $licencia->expiracion ? $licencia->expiracion->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        @if($licencia->expiracion && $licencia->expiracion->isPast())
                                            <span class="badge bg-danger">Expirada</span>
                                        @elseif($licencia->expiracion && $licencia->expiracion->diffInDays(now()) <= 30)
                                            <span class="badge bg-warning">Por expirar</span>
                                        @else
                                            <span class="badge bg-success">Activa</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Información Adicional</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Fecha Creación:</th>
                                    <td>{{ $licencia->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Última Actualización:</th>
                                    <td>{{ $licencia->updated_at->format('d/m/Y H:i') }}</td>
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