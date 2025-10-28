@extends('layouts.app')

@section('title', 'Editar Usuario TI - Sistema de Gestión TI')

@section('content')


<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                <a class="nav-link active" href="{{ route('usuarios-ti.index') }}"><i class="fas fa-users me-2"></i>Usuarios TI</a>
            </nav>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="fas fa-edit me-2"></i>Editar Usuario</h1>
                <a href="{{ route('usuarios-ti.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Volver</a>
            </div>

            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('usuarios-ti.update', $usuario) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label">Usuario *</label>
                                <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario" value="{{ old('usuario', $usuario->usuario) }}" required>
                                @error('usuario') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="rol" class="form-label">Rol *</label>
                                <select class="form-select @error('rol') is-invalid @enderror" id="rol" name="rol" required>
                                    <option value="">Seleccionar rol</option>
                                    <option value="ADMIN" {{ old('rol', $usuario->rol) == 'ADMIN' ? 'selected' : '' }}>Administrador</option>
                                    <option value="AUXILIAR-TI" {{ old('rol', $usuario->rol) == 'AUXILIAR-TI' ? 'selected' : '' }}>Auxiliar TI</option>
                                    <option value="PERSONAL-TI" {{ old('rol', $usuario->rol) == 'PERSONAL-TI' ? 'selected' : '' }}>Personal TI</option>
                                </select>
                                @error('rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nombres" class="form-label">Nombres *</label>
                                <input type="text" class="form-control @error('nombres') is-invalid @enderror" id="nombres" name="nombres" value="{{ old('nombres', $usuario->nombres) }}" required>
                                @error('nombres') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}">
                                @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="puesto" class="form-label">Puesto</label>
                                <input type="text" class="form-control @error('puesto') is-invalid @enderror" id="puesto" name="puesto" value="{{ old('puesto', $usuario->puesto) }}">
                                @error('puesto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $usuario->telefono) }}">
                                @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contrasena" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control @error('contrasena') is-invalid @enderror" id="contrasena" name="contrasena">
                                <small class="form-text text-muted">Dejar vacío si no deseas cambiar la contraseña. Mínimo 8 caracteres si se proporciona.</small>
                                @error('contrasena') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contrasena_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="contrasena_confirmation" name="contrasena_confirmation">
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('usuarios-ti.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Actualizar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
