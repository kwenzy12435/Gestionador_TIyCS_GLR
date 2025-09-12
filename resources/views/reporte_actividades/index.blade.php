@extends('layouts.app')

@section('title', 'Reporte de Actividades - Sistema de Gestión TI')

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
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('usuarios-ti.index') }}">
                            <i class="fas fa-users me-2"></i>Usuarios TI
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('colaboradores.index') }}">
                            <i class="fas fa-user-friends me-2"></i>Colaboradores
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
                        <a class="nav-link active" href="{{ route('reporte_actividades.index') }}">
                            <i class="fas fa-clipboard-list me-2"></i>Reporte Actividades
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Reporte de Actividades</h1>
                <a href="{{ route('reporte_actividades.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Reporte
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Actividad</th>
                                    <th>Colaborador</th>
                                    <th>Canal</th>
                                    <th>Naturaleza</th>
                                    <th>Técnico</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportes as $reporte)
                                <tr>
                                    <td>{{ $reporte->fecha->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($reporte->actividad, 50) }}</td>
                                    <td>{{ $reporte->colaborador->nombre ?? 'N/A' }} {{ $reporte->colaborador->apellidos ?? '' }}</td>
                                    <td>{{ $reporte->canal->nombre ?? 'N/A' }}</td>
                                    <td>{{ $reporte->naturaleza->nombre ?? 'N/A' }}</td>
                                    <td>{{ $reporte->usuarioTi->nombres ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('reporte_actividades.show', $reporte->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('reporte_actividades.edit', $reporte->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('reporte_actividades.destroy', $reporte->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este reporte?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection