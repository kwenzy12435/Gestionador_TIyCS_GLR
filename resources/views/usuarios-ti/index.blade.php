@extends('layouts.app')

@section('title', 'Licencias - Sistema de Gestión TI')
  @vite(['resources/js/licencias.js'])
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
                        <a class="nav-link active" href="{{ route('licencias.index') }}">
                            <i class="fas fa-key me-2"></i>Licencias
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestión de Licencias</h1>
                <a href="{{ route('licencias.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nueva Licencia
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

            <!-- Modal para confirmar contraseña -->
            <div class="modal fade" id="passwordModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmar Contraseña</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Por seguridad, ingrese su contraseña para continuar:</p>
                            <input type="password" id="passwordInput" class="form-control" placeholder="Contraseña">
                            <div id="passwordError" class="text-danger mt-2" style="display: none;"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" id="confirmPasswordBtn">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Cuenta</th>
                                    <th>Plataforma</th>
                                    <th>Colaborador</th>
                                    <th>Expiración</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($licencias as $licencia)
                                <tr>
                                    <td>{{ $licencia->cuenta }}</td>
                                    <td>{{ $licencia->plataforma->nombre ?? 'N/A' }}</td>
                                    <td>
                                        @if($licencia->colaborador)
                                            {{ $licencia->colaborador->nombre }} {{ $licencia->colaborador->apellidos }}
                                        @else
                                            Sin asignar
                                        @endif
                                    </td>
                                    <td>{{ $licencia->expiracion ? $licencia->expiracion->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        @if($licencia->expiracion && $licencia->expiracion->isPast())
                                            <span class="badge bg-danger">Expirada</span>
                                        @elseif($licencia->expiracion && $licencia->expiracion->diffInDays(now()) <= 30)
                                            <span class="badge bg-warning">Por expirar</span>
                                        @else
                                            <span class="badge bg-success">Activa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('licencias.show', $licencia->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-warning btn-editar" data-id="{{ $licencia->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-eliminar" data-id="{{ $licencia->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection