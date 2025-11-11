@extends('layouts.app')
@section('title', 'Editar Registro de Respaldo')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Registro de Respaldo</h1>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-4 shadow-sm">
    <form method="POST" action="{{ route('bitacora-respaldo.update', $bitacoraRespaldo) }}">
        @csrf 
        @method('PUT')
        
        <div class="row g-3">
            <!-- Empresa -->
            <div class="col-md-4">
                <label for="empresa_id" class="form-label fw-semibold">Empresa *</label>
                <select name="empresa_id" id="empresa_id" class="form-select @error('empresa_id') is-invalid @enderror" required>
                    <option value="contabilidad" {{ old('empresa_id', $bitacoraRespaldo->empresa_id) == 'contabilidad' ? 'selected' : '' }}>Contabilidad</option>
                    <option value="nomina" {{ old('empresa_id', $bitacoraRespaldo->empresa_id) == 'nomina' ? 'selected' : '' }}>Nómina</option>
                </select>
                @error('empresa_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Fecha de Respaldo -->
            <div class="col-md-4">
                <label for="fecha_respaldo" class="form-label fw-semibold">Fecha de Respaldo *</label>
                <input type="date" name="fecha_respaldo" id="fecha_respaldo" 
                       class="form-control @error('fecha_respaldo') is-invalid @enderror" 
                       value="{{ old('fecha_respaldo', $bitacoraRespaldo->fecha_respaldo->format('Y-m-d')) }}" 
                       required>
                @error('fecha_respaldo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Estado -->
            <div class="col-md-4">
                <label for="estado" class="form-label fw-semibold">Estado *</label>
                <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                    <option value="no hecho" {{ old('estado', $bitacoraRespaldo->estado) == 'no hecho' ? 'selected' : '' }}>No Hecho</option>
                    <option value="Hecho" {{ old('estado', $bitacoraRespaldo->estado) == 'Hecho' ? 'selected' : '' }}>Hecho</option>
                </select>
                @error('estado')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Responsable TI -->
            <div class="col-md-6">
                <label for="usuario_ti_id" class="form-label fw-semibold">Responsable TI *</label>
                <select name="usuario_ti_id" id="usuario_ti_id" class="form-select @error('usuario_ti_id') is-invalid @enderror" required>
                    @foreach($usuariosTi as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('usuario_ti_id', $bitacoraRespaldo->usuario_ti_id) == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->usuario }} — {{ $usuario->nombres }} {{ $usuario->apellidos }}
                        </option>
                    @endforeach
                </select>
                @error('usuario_ti_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Ubicación de Guardado -->
            <div class="col-md-6">
                <label for="ubicacion_guardado" class="form-label fw-semibold">Ubicación de Guardado</label>
                <input type="text" name="ubicacion_guardado" id="ubicacion_guardado" 
                       class="form-control @error('ubicacion_guardado') is-invalid @enderror" 
                       value="{{ old('ubicacion_guardado', $bitacoraRespaldo->ubicacion_guardado) }}" 
                       maxlength="255">
                @error('ubicacion_guardado')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

           
            <!-- Acciones Alternativas -->
            <div class="col-12">
                <label for="acciones_alternativas" class="form-label fw-semibold">Acciones Alternativas</label>
                <textarea name="acciones_alternativas" id="acciones_alternativas" 
                          class="form-control @error('acciones_alternativas') is-invalid @enderror" 
                          rows="3">{{ old('acciones_alternativas', $bitacoraRespaldo->acciones_alternativas) }}</textarea>
                @error('acciones_alternativas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Botones -->
        <div class="text-end mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-save2 me-1"></i>Actualizar Registro
            </button>
            <a href="{{ route('bitacora-respaldo.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </form>
</div>
@endsection