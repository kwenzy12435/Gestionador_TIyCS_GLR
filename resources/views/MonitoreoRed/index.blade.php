@extends('layouts.app')

@section('title', 'Monitoreo de Red - Sistema de Gestión TI')

@section('content')


<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('monitoreo-red.index') }}">
                            <i class="fas fa-network-wired me-2"></i>
                            Monitoreo Red
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Monitoreo de Red</h1>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('monitoreo-red.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Nuevo Registro
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if($monitoreos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Descarga (Mbps)</th>
                                        <th>Subida (Mbps)</th>
                                        <th>Experiencia Wi-Fi</th>
                                        <th>Clientes</th>
                                        <th>Responsable</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monitoreos as $monitoreo)
                                    <tr>
                                        <td>{{ $monitoreo->id }}</td>
                                        <td>{{ $monitoreo->fecha->format('d/m/Y') }}</td>
                                        <td>{{ $monitoreo->hora }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $monitoreo->velocidad_descarga }} Mbps</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $monitoreo->velocidad_subida }} Mbps</span>
                                        </td>
                                        <td>
                                            @if($monitoreo->porcentaje_experiencia_wifi >= 80)
                                                <span class="badge bg-success">{{ $monitoreo->porcentaje_experiencia_wifi }}%</span>
                                            @elseif($monitoreo->porcentaje_experiencia_wifi >= 50)
                                                <span class="badge bg-warning">{{ $monitoreo->porcentaje_experiencia_wifi }}%</span>
                                            @else
                                                <span class="badge bg-danger">{{ $monitoreo->porcentaje_experiencia_wifi }}%</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $monitoreo->clientes_conectados }}</span>
                                        </td>
                                        <td>
                                            @if($monitoreo->usuarioResponsable)
                                                {{ $monitoreo->usuarioResponsable->nombres }} {{ $monitoreo->usuarioResponsable->apellidos }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('monitoreo-red.show', $monitoreo->id) }}" class="btn btn-info btn-sm" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('monitoreo-red.edit', $monitoreo->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('monitoreo-red.destroy', $monitoreo->id) }}" method="POST" class="d-inline">
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
                            No hay registros de monitoreo de red.
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection