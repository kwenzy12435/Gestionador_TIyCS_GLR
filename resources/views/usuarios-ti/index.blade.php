@extends('layouts.app')

@section('title', 'Usuarios TI - Sistema de Gestión TI')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-server me-2"></i>Sistema Gestión TI
        </a>
        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user me-1"></i> {{ auth()->user()->nombres }}
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-cog me-2"></i>Configuración
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a class="nav-link active" href="{{ route('usuarios-ti.index') }}">
                    <i class="fas fa-users me-2"></i>Usuarios TI
                </a>
                <a class="nav-link" href="{{ route('articulos.index') }}">
                    <i class="fas fa-boxes me-2"></i>Artículos
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="fas fa-users me-2"></i>Gestión de Usuarios TI
                </h1>
                @if(auth()->user()->rol === 'ADMIN')
                <a href="{{ route('usuarios-ti.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Usuario
                </a>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Lista de Usuarios
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Usuario</th>
                                    <th>Nombre Completo</th>
                                    <th>Puesto</th>
                                    <th>Teléfono</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $usuario)
                                <tr>
                                    <td>
                                        <strong>{{ $usuario->usuario }}</strong>
                                        @if($usuario->id === auth()->id())
                                            <span class="badge bg-info ms-1">Tú</span>
                                        @endif
                                    </td>
                                    <td>{{ $usuario->nombres }} {{ $usuario->apellidos }}</td>
                                    <td>{{ $usuario->puesto ?? 'No especificado' }}</td>
                                    <td>{{ $usuario->telefono ?? 'No especificado' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $usuario->rol == 'ADMIN' ? 'danger' : ($usuario->rol == 'AUXILIAR-TI' ? 'warning' : 'primary') }}">
                                            {{ $usuario->rol }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('usuarios-ti.show', $usuario) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('usuarios-ti.edit', $usuario) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(auth()->user()->rol === 'ADMIN' && $usuario->id !== auth()->id())
                                            <form action="{{ route('usuarios-ti.destroy', $usuario) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('¿Estás seguro de eliminar a {{ $usuario->nombres }}?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-2x mb-3"></i>
                                        <p>No hay usuarios registrados</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
@endsection