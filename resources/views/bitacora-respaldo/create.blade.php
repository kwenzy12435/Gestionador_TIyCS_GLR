@extends('layouts.app')
@section('title', 'Nuevo Registro de Respaldo')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-hdd-stack me-2"></i>Nuevo Registro de Respaldo</h1>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-4 shadow-sm">
    <form method="POST" action="{{ route('bitacora-respaldo.store') }}" novalidate>
        @csrf
        
        <div class="row g-3">
            <!-- Empresa -->
            <div class="col-md-4">
                <label for="empresa_id" class="form-label fw-semibold">Empresa *</label>
                <select name="empresa_id" id="empresa_id" class="form-select @error('empresa_id') is-invalid @enderror" required>
                    <option value="">Seleccionar empresa…</option>
                    <option value="contabilidad" {{ old('empresa_id') == 'contabilidad' ? 'selected' : '' }}>Contabilidad</option>
                    <option value="nomina" {{ old('empresa_id') == 'nomina' ? 'selected' : '' }}>Nómina</option>
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
                       value="{{ old('fecha_respaldo', date('Y-m-d')) }}" 
                       required>
                @error('fecha_respaldo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Estado -->
            <div class="col-md-4">
                <label for="estado" class="form-label fw-semibold">Estado *</label>
                <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                    <option value="">Seleccionar estado…</option>
                    <option value="no hecho" {{ old('estado') == 'no hecho' ? 'selected' : '' }}>No Hecho</option>
                    <option value="Hecho" {{ old('estado') == 'Hecho' ? 'selected' : '' }}>Hecho</option>
                </select>
                @error('estado')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Responsable TI -->
            <div class="col-md-6">
                <label for="usuario_ti_id" class="form-label fw-semibold">Responsable TI *</label>
                <select name="usuario_ti_id" id="usuario_ti_id" class="form-select @error('usuario_ti_id') is-invalid @enderror" required>
                    <option value="">Seleccionar responsable…</option>
                    @foreach($usuariosTi as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('usuario_ti_id') == $usuario->id ? 'selected' : '' }}>
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
                       value="{{ old('ubicacion_guardado') }}" 
                       maxlength="255" 
                       placeholder="p. ej., NAS\Backups\Contabilidad\2025-10-31.zip">
                @error('ubicacion_guardado')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

   

            <!-- Acciones Alternativas -->
            <div class="col-12">
                <label for="acciones_alternativas" class="form-label fw-semibold">Acciones Alternativas</label>
                <textarea name="acciones_alternativas" id="acciones_alternativas" 
                          class="form-control @error('acciones_alternativas') is-invalid @enderror" 
                          rows="3" 
                          placeholder="p. ej., Respaldo manual a disco externo por caída de red.">{{ old('acciones_alternativas') }}</textarea>
                @error('acciones_alternativas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Botones -->
        <div class="text-end mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-brand">
                <i class="bi bi-check2 me-1"></i>Guardar Registro
            </button>
            <a href="{{ route('bitacora-respaldo.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg me-1"></i>Cancelar
            </a>
        </div>
    </form>
</div>
@endsection