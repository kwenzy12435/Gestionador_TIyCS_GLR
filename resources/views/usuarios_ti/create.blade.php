@extends('layouts.app')

@section('title', 'Registrar Usuario TI - Sistema de Gestión TI')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-server me-2"></i>Sistema Gestión TI
        </a>
        <!-- Resto del navbar -->
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <!-- Mismo sidebar -->
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Registrar Nuevo Usuario TI</h1>
                <a href="{{ route('usuarios-ti.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('usuarios-ti.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label">Usuario *</label>
                                <input type="text" class="form-control @error('usuario') is-invalid @enderror" 
                                       id="usuario" name="usuario" value="{{ old('usuario') }}" required>
                                @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nombres" class="form-label">Nombres *</label>
                                <input type="text" class="form-control @error('nombres') is-invalid @enderror" 
                                       id="nombres" name="nombres" value="{{ old('nombres') }}" required>
                                @error('nombres')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control @error('apellidos') is-invalid @enderror" 
                                       id="apellidos" name="apellidos" value="{{ old('apellidos') }}">
                                @error('apellidos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="puesto" class="form-label">Puesto</label>
                                <input type="text" class="form-control @error('puesto') is-invalid @enderror" 
                                       id="puesto" name="puesto" value="{{ old('puesto') }}">
                                @error('puesto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" name="telefono" value="{{ old('telefono') }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="rol" class="form-label">Rol *</label>
                                <select class="form-select @error('rol') is-invalid @enderror" id="rol" name="rol" required>
                                    <option value="">Seleccionar rol</option>
                                    <option value="ADMIN" {{ old('rol') == 'ADMIN' ? 'selected' : '' }}>Administrador</option>
                                    <option value="AUXILIAR-TI" {{ old('rol') == 'AUXILIAR-TI' ? 'selected' : '' }}>Auxiliar TI</option>
                                    <option value="PERSONAL-TI" {{ old('rol') == 'PERSONAL-TI' ? 'selected' : '' }}>Personal TI</option>
                                </select>
                                @error('rol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="contrasena" class="form-label">Contraseña *</label>
                                <input type="password" class="form-control @error('contrasena') is-invalid @enderror" 
                                       id="contrasena" name="contrasena" required>
                                @error('contrasena')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="contrasena_confirmation" class="form-label">Confirmar Contraseña *</label>
                                <input type="password" class="form-control" 
                                       id="contrasena_confirmation" name="contrasena_confirmation" required>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Registrar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection