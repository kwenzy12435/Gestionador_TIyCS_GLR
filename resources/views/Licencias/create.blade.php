@extends('layouts.app')

@section('title', 'Nueva Licencia - Sistema de Gestión TI')

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
                <h1 class="h2">Registrar Nueva Licencia</h1>
                <a href="{{ route('licencias.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('licencias.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cuenta" class="form-label">Cuenta *</label>
                                <input type="text" class="form-control @error('cuenta') is-invalid @enderror" 
                                       id="cuenta" name="cuenta" value="{{ old('cuenta') }}" required>
                                @error('cuenta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contrasena" class="form-label">Contraseña *</label>
                                <input type="text" class="form-control @error('contrasena') is-invalid @enderror" 
                                       id="contrasena" name="contrasena" value="{{ old('contrasena') }}" required>
                                @error('contrasena')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="plataforma_id" class="form-label">Plataforma</label>
                                <select class="form-select @error('plataforma_id') is-invalid @enderror" id="plataforma_id" name="plataforma_id">
                                    <option value="">Seleccionar plataforma</option>
                                    @foreach($plataformas as $plataforma)
                                        <option value="{{ $plataforma->id }}" {{ old('plataforma_id') == $plataforma->id ? 'selected' : '' }}>
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
                                        <option value="{{ $colaborador->id }}" {{ old('colaborador_id') == $colaborador->id ? 'selected' : '' }}>
                                            {{ $colaborador->nombre }} {{ $colaborador->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('colaborador_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="expiracion" class="form-label">Fecha de Expiración</label>
                                <input type="date" class="form-control @error('expiracion') is-invalid @enderror" 
                                       id="expiracion" name="expiracion" value="{{ old('expiracion') }}">
                                @error('expiracion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Registrar Licencia
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection