@extends('layouts.app')

@section('title', 'Detalle Colaborador - Sistema de Gestión TI')

@section('content')


<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <!-- Mismo sidebar que index -->
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Detalle del Colaborador</h1>
                <a href="{{ route('colaboradores.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información Personal</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Usuario:</th>
                                    <td>{{ $colaborador->usuario }}</td>
                                </tr>
                                <tr>
                                    <th>Nombre:</th>
                                    <td>{{ $colaborador->nombre }}</td>
                                </tr>
                                <tr>
                                    <th>Apellidos:</th>
                                    <td>{{ $colaborador->apellidos ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Puesto:</th>
                                    <td>{{ $colaborador->puesto ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Información Adicional</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Departamento:</th>
                                    <td>{{ $colaborador->departamento->nombre ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Anydesk ID:</th>
                                    <td>{{ $colaborador->anydesk_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha Creación:</th>
                                    <td>{{ $colaborador->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Última Actualización:</th>
                                    <td>{{ $colaborador->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="text-end mt-3">
                        <a href="{{ route('colaboradores.edit', $colaborador->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection