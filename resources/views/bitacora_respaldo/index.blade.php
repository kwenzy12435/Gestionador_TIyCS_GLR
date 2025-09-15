@extends('layouts.app')

@section('title', 'Bitácora Respaldos Contpaq - Sistema de Gestión TI')

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
                        <a class="nav-link active" href="{{ route('bitacora_respaldo.index') }}">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Bitácora Respaldos
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Bitácora de Respaldos Contpaq</h1>
                <a href="{{ route('bitacora_respaldo.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Nuevo Registro
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if($bitacoras->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Empresa</th>
                                        <th>Usuario TI</th>
                                        <th>Fecha Respaldo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bitacoras as $bitacora)
                                    <tr>
                                        <td>{{ $bitacora->id }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucfirst($bitacora->empresa_id) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($bitacora->usuarioTi)
                                                {{ $bitacora->usuarioTi->nombres }} {{ $bitacora->usuarioTi->apellido }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $bitacora->fecha_respaldo->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge {{ $bitacora->estado == 'Hecho' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $bitacora->estado }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('bitacora_respaldo.show', $bitacora->id) }}" class="btn btn-info btn-sm" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('bitacora_respaldo.edit', $bitacora->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('bitacora_respaldo.destroy', $bitacora->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay registros de respaldos en la bitácora.
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection