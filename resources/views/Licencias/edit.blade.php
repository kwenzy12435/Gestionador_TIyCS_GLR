@extends('layouts.app')

@section('title', 'Editar Licencia - Sistema de Gestión TI')

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
                <h1 class="h2">Editar Licencia</h1>
                <div>
                    <a href="{{ route('licencias.show', $licencia->id) }}" class="btn btn-info me-2">
                        <i class="fas fa-eye me-2"></i>Ver
                    </a>
                    <a href="{{ route('licencias.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('licencias.update', $licencia->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cuenta" class="form-label">Cuenta *</label>
                                <input type="text" class="form-control @error('cuenta') is-invalid @enderror" 
                                       id="cuenta" name="cuenta" value="{{ old('cuenta', $licencia->cuenta) }}" required>
                                @error('cuenta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contrasena" class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
                                <input type="text" class="form-control @error('contrasena') is-invalid @enderror" 
                                       id="contrasena" name="contrasena" placeholder="Nueva contraseña">
                                @error('contrasena')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="plataforma_id" class="form-label">Plataforma</label>
                                <select class="form-select @error('plataforma_id') is-invalid @enderror" id="plataforma_id" name="plataforma_id">
                                    <option value="">Seleccionar plataforma</option>
                                    @foreach($plataformas as $plataforma)
                                        <option value="{{ $plataforma->id }}" {{ old('plataforma_id', $licencia->plataforma_id) == $plataforma->id ? 'selected' : '' }}>
                                            {{ $plataforma->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('plataforma_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="colaborador_id" class="form-label">Asignar a Colaborador</label>
                                <select class="form-select @error('colaborador_id') is-invalid @enderror" id="colaborador_id" name="colaborador_id">
                                    <option value="">Seleccionar colaborador</option>
                                    @foreach($colaboradores as $colaborador)
                                        <option value="{{ $colaborador->id }}" {{ old('colaborador_id', $licencia->colaborador_id) == $colaborador->id ? 'selected' : '' }}>
                                            {{ $colaborador->nombre }} {{ $colaborador->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('colaborador_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                          <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                       id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $licencia->fecha_inicio?->format('Y-m-d')) }}">
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                <input type="date" class="form-control @error('fecha_vencimiento') is-invalid @enderror"
                                       id="fecha_vencimiento" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', $licencia->fecha_vencimiento?->format('Y-m-d')) }}">
                                @error('fecha_vencimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                          id="observaciones" name="observaciones" rows="3">{{ old('observaciones', $licencia->observaciones) }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Actualizar Licencia
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection