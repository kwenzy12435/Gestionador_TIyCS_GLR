@extends('layouts.app')

@section('title', 'Editar Usuario TI - Sistema de Gestión TI')

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
                    <i class="fas fa-user-edit me-2"></i>
                    @if($usuarios_ti->id === auth()->id())  <!-- CAMBIADO -->
                        Mi Perfil
                    @else
                        Editar Usuario: {{ $usuarios_ti->nombres }}  <!-- CAMBIADO -->
                    @endif
                </h1>
                <a href="{{ route('usuarios-ti.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Información
                    </h5>
                </div>
                <div class="card-body">
                    <!-- CAMBIO IMPORTANTE: usar $usuarios_ti en el route -->
                    <form action="{{ route('usuarios-ti.update', $usuarios_ti) }}" method="POST">  <!-- CAMBIADO -->
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label">Usuario *</label>
                                <input type="text" class="form-control @error('usuario') is-invalid @enderror" 
                                       id="usuario" name="usuario" value="{{ old('usuario', $usuarios_ti->usuario) }}" required>  <!-- CAMBIADO -->
                                @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="rol" class="form-label">Rol *</label>
                                <select class="form-select @error('rol') is-invalid @enderror" id="rol" name="rol" required
                                    {{ $usuarios_ti->id === auth()->id() ? 'disabled' : '' }}>  <!-- CAMBIADO -->
                                    <option value="">Seleccionar rol</option>
                                    <option value="ADMIN" {{ old('rol', $usuarios_ti->rol) == 'ADMIN' ? 'selected' : '' }}>Administrador</option>  <!-- CAMBIADO -->
                                    <option value="AUXILIAR-TI" {{ old('rol', $usuarios_ti->rol) == 'AUXILIAR-TI' ? 'selected' : '' }}>Auxiliar TI</option>  <!-- CAMBIADO -->
                                    <option value="PERSONAL-TI" {{ old('rol', $usuarios_ti->rol) == 'PERSONAL-TI' ? 'selected' : '' }}>Personal TI</option>  <!-- CAMBIADO -->
                                </select>
                                @if($usuarios_ti->id === auth()->id())  <!-- CAMBIADO -->
                                    <input type="hidden" name="rol" value="{{ $usuarios_ti->rol }}">  <!-- CAMBIADO -->
                                    <small class="form-text text-muted">No puedes cambiar tu propio rol</small>
                                @endif
                                @error('rol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nombres" class="form-label">Nombres *</label>
                                <input type="text" class="form-control @error('nombres') is-invalid @enderror" 
                                       id="nombres" name="nombres" value="{{ old('nombres', $usuarios_ti->nombres) }}" required>  <!-- CAMBIADO -->
                                @error('nombres')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control @error('apellidos') is-invalid @enderror" 
                                       id="apellidos" name="apellidos" value="{{ old('apellidos', $usuarios_ti->apellidos) }}">  <!-- CAMBIADO -->
                                @error('apellidos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="puesto" class="form-label">Puesto</label>
                                <input type="text" class="form-control @error('puesto') is-invalid @enderror" 
                                       id="puesto" name="puesto" value="{{ old('puesto', $usuarios_ti->puesto) }}">  <!-- CAMBIADO -->
                                @error('puesto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" name="telefono" value="{{ old('telefono', $usuarios_ti->telefono) }}">  <!-- CAMBIADO -->
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Campos de contraseña -->
                            <div class="col-12 mt-4">
                                <h5 class="text-primary">
                                    <i class="fas fa-lock me-2"></i>Cambio de Contraseña
                                </h5>
                                <p class="text-muted">Dejar en blanco si no deseas cambiar la contraseña</p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="contrasena" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control @error('contrasena') is-invalid @enderror" 
                                       id="contrasena" name="contrasena">
                                <small class="form-text text-muted">Mínimo 8 caracteres, debe contener mayúsculas, minúsculas y números</small>
                                @error('contrasena')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="contrasena_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" 
                                       id="contrasena_confirmation" name="contrasena_confirmation">
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="reset" class="btn btn-secondary me-2">
                                <i class="fas fa-undo me-2"></i>Restablecer
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection