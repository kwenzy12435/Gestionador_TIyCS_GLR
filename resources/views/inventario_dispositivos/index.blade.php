@extends('layouts.app')

@section('title', 'Inventario Dispositivos - Sistema de Gestión TI')

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
                        <a class="nav-link active" href="{{ route('inventario-dispositivos.index') }}">
                            <i class="fas fa-laptop me-2"></i>Inventario Dispositivos
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Inventario de Dispositivos</h1>
                <a href="{{ route('inventario-dispositivos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Dispositivo
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
                                    <th>Modelo</th>
                                    <th>Marca</th>
                                    <th>Tipo</th>
                                    <th>N° Serie</th>
                                    <th>Estado</th>
                                    <th>Asignado a</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dispositivos as $dispositivo)
                                <tr>
                                    <td>{{ $dispositivo->modelo }}</td>
                                    <td>{{ $dispositivo->marca_nombre ?? 'N/A' }}</td>
                                    <td>{{ $dispositivo->tipo_nombre ?? 'N/A' }}</td>
                                    <td>{{ $dispositivo->numero_serie }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $dispositivo->estado == 'nuevo' ? 'success' : 
                                            ($dispositivo->estado == 'asignado' ? 'primary' : 
                                            ($dispositivo->estado == 'reparación' ? 'warning' : 'danger')) 
                                        }}">
                                            {{ ucfirst($dispositivo->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($dispositivo->colaborador_nombre)
                                            {{ $dispositivo->colaborador_nombre }} {{ $dispositivo->colaborador_apellidos }}
                                        @else
                                            No asignado
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('inventario-dispositivos.show', $dispositivo->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('inventario-dispositivos.edit', $dispositivo->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('inventario-dispositivos.destroy', $dispositivo->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este dispositivo?')">
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