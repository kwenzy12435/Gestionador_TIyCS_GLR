@extends('layouts.app')

@section('title', 'Usuarios TI - Sistema de Gestión TI')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <!-- Mismo sidebar que el dashboard -->
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Usuarios TI</h1>
                <a href="{{ route('usuarios-ti.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Usuario
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Puesto</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->usuario }}</td>
                                    <td>{{ $usuario->nombres }}</td>
                                    <td>{{ $usuario->apellidos }}</td>
                                    <td>{{ $usuario->puesto }}</td>
                                    <td>
                                        <span class="badge bg-{{ $usuario->rol == 'ADMIN' ? 'danger' : ($usuario->rol == 'AUXILIAR-TI' ? 'warning' : 'info') }}">
                                            {{ $usuario->rol }}
                                        </span>
                                    </td>
                                    <td>
                                        <!-- CAMBIO: Pasar el objeto $usuario en lugar de $usuario->id -->
                                        <a href="{{ route('usuarios-ti.edit', $usuario) }}" class="btn btn-sm btn-warning">  <!-- CAMBIADO -->
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('usuarios-ti.destroy', $usuario) }}" method="POST" class="d-inline">  <!-- CAMBIADO -->
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">  <!-- MEJOR MENSAJE -->
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