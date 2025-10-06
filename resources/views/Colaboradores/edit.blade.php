@extends('layouts.app')

@section('title', 'Editar Colaborador - Sistema de Gestión TI')

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
                <h1 class="h2">Editar Colaborador</h1>
                <a href="{{ route('colaboradores.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('colaboradores.update', $colaborador->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label">Usuario *</label>
                                <input type="text" class="form-control @error('usuario') is-invalid @enderror" 
                                       id="usuario" name="usuario" value="{{ old('usuario', $colaborador->usuario) }}" required>
                                @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre', $colaborador->nombre) }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control @error('apellidos') is-invalid @enderror" 
                                       id="apellidos" name="apellidos" value="{{ old('apellidos', $colaborador->apellidos) }}">
                                @error('apellidos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="puesto" class="form-label">Puesto</label>
                                <input type="text" class="form-control @error('puesto') is-invalid @enderror" 
                                       id="puesto" name="puesto" value="{{ old('puesto', $colaborador->puesto) }}">
                                @error('puesto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="departamento_id" class="form-label">Departamento</label>
                                <select class="form-select @error('departamento_id') is-invalid @enderror" 
                                        id="departamento_id" name="departamento_id">
                                    <option value="">Seleccionar departamento</option>
                                    @foreach($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}" {{ (old('departamento_id', $colaborador->departamento_id) == $departamento->id) ? 'selected' : '' }}>
                                            {{ $departamento->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departamento_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="anydesk_id" class="form-label">Anydesk ID</label>
                                <input type="text" class="form-control @error('anydesk_id') is-invalid @enderror" 
                                       id="anydesk_id" name="anydesk_id" value="{{ old('anydesk_id', $colaborador->anydesk_id) }}">
                                @error('anydesk_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Colaborador
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection