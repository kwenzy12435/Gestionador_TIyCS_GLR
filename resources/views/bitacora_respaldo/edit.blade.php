@extends('layouts.app')

@section('title', 'Editar Registro - Bitácora Respaldos')

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
                        <a class="nav-link" href="{{ route('bitacora_respaldo.index') }}">
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
                <h1 class="h2">Editar Registro #{{ $bitacora->id }}</h1>
                <a href="{{ route('bitacora_respaldo.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('bitacora_respaldo.update', $bitacora->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="empresa_id" class="form-label">Empresa *</label>
                                <select class="form-select @error('empresa_id') is-invalid @enderror" 
                                        id="empresa_id" name="empresa_id" required>
                                    <option value="contabilidad" {{ old('empresa_id', $bitacora->empresa_id) == 'contabilidad' ? 'selected' : '' }}>Contabilidad</option>
                                    <option value="nomina" {{ old('empresa_id', $bitacora->empresa_id) == 'nomina' ? 'selected' : '' }}>Nómina</option>
                                </select>
                                @error('empresa_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="usuario_ti_id" class="form-label">Usuario TI *</label>
                                <select class="form-select @error('usuario_ti_id') is-invalid @enderror" 
                                        id="usuario_ti_id" name="usuario_ti_id" required>
                                    <option value="">Seleccionar usuario TI</option>
                                    @foreach($usuariosTi as $usuario)
                                        <option value="{{ $usuario->id }}" {{ old('usuario_ti_id', $bitacora->usuario_ti_id) == $usuario->id ? 'selected' : '' }}>
                                            {{ $usuario->nombres }} {{ $usuario->apellido }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('usuario_ti_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="respaldo_nominas" 
                                           name="respaldo_nominas" value="1" {{ old('respaldo_nominas', $bitacora->respaldo_nominas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="respaldo_nominas">Respaldo Nóminas</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="respaldo_contabilidad" 
                                           name="respaldo_contabilidad" value="1" {{ old('respaldo_contabilidad', $bitacora->respaldo_contabilidad) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="respaldo_contabilidad">Respaldo Contabilidad</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fecha_respaldo" class="form-label">Fecha de Respaldo *</label>
                                <input type="date" class="form-control @error('fecha_respaldo') is-invalid @enderror" 
                                       id="fecha_respaldo" name="fecha_respaldo" 
                                       value="{{ old('fecha_respaldo', $bitacora->fecha_respaldo->format('Y-m-d')) }}" required>
                                @error('fecha_respaldo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select @error('estado') is-invalid @enderror" 
                                        id="estado" name="estado" required>
                                    <option value="no hecho" {{ old('estado', $bitacora->estado) == 'no hecho' ? 'selected' : '' }}>No Hecho</option>
                                    <option value="Hecho" {{ old('estado', $bitacora->estado) == 'Hecho' ? 'selected' : '' }}>Hecho</option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="ubicacion_guardado" class="form-label">Ubicación de Guardado</label>
                                <input type="text" class="form-control @error('ubicacion_guardado') is-invalid @enderror" 
                                       id="ubicacion_guardado" name="ubicacion_guardado" 
                                       value="{{ old('ubicacion_guardado', $bitacora->ubicacion_guardado) }}">
                                @error('ubicacion_guardado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="acciones_alternativas" class="form-label">Acciones Alternativas</label>
                                <textarea class="form-control @error('acciones_alternativas') is-invalid @enderror" 
                                          id="acciones_alternativas" name="acciones_alternativas" rows="3">{{ old('acciones_alternativas', $bitacora->acciones_alternativas) }}</textarea>
                                @error('acciones_alternativas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection