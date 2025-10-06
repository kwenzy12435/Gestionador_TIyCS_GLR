@extends('layouts.app')

@section('title', 'Nuevo Monitoreo de Red - Sistema de Gestión TI')

@section('content')

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('monitoreo-red.index') }}">
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
                <h1 class="h2">Nuevo Monitoreo de Red</h1>
                <a href="{{ route('monitoreo-red.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('monitoreo-red.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha" class="form-label">Fecha *</label>
                                <input type="date" class="form-control @error('fecha') is-invalid @enderror" 
                                       id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="hora" class="form-label">Hora *</label>
                                <input type="time" class="form-control" 
                                 id="hora" name="hora" value="{{ date('H:i') }}" readonly>

                                @error('hora')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="velocidad_descarga" class="form-label">Velocidad de Descarga (Mbps) *</label>
                                <input type="number" step="0.01" class="form-control @error('velocidad_descarga') is-invalid @enderror" 
                                       id="velocidad_descarga" name="velocidad_descarga" value="{{ old('velocidad_descarga') }}" required>
                                @error('velocidad_descarga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="velocidad_subida" class="form-label">Velocidad de Subida (Mbps) *</label>
                                <input type="number" step="0.01" class="form-control @error('velocidad_subida') is-invalid @enderror" 
                                       id="velocidad_subida" name="velocidad_subida" value="{{ old('velocidad_subida') }}" required>
                                @error('velocidad_subida')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="porcentaje_experiencia_wifi" class="form-label">% Experiencia Wi-Fi *</label>
                                <input type="number" step="0.01" class="form-control @error('porcentaje_experiencia_wifi') is-invalid @enderror" 
                                       id="porcentaje_experiencia_wifi" name="porcentaje_experiencia_wifi" 
                                       value="{{ old('porcentaje_experiencia_wifi') }}" min="0" max="100" required>
                                @error('porcentaje_experiencia_wifi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="clientes_conectados" class="form-label">Clientes Conectados *</label>
                                <input type="number" class="form-control @error('clientes_conectados') is-invalid @enderror" 
                                       id="clientes_conectados" name="clientes_conectados" value="{{ old('clientes_conectados') }}" min="0" required>
                                @error('clientes_conectados')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="responsable" class="form-label">Responsable *</label>
                                <select class="form-select @error('responsable') is-invalid @enderror" 
                                        id="responsable" name="responsable" required>
                                    <option value="">Seleccionar responsable</option>
                                    @foreach($usuariosTi as $usuario)
                                        <option value="{{ $usuario->id }}" {{ old('responsable') == $usuario->id ? 'selected' : '' }}>
                                            {{ $usuario->nombres }} {{ $usuario->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsable')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                          id="observaciones" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection