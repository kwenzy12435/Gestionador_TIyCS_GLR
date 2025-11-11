@extends('layouts.app')
@section('title', 'Editar Monitoreo de Red')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Monitoreo de Red</h1>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-4 shadow-sm">
    <form method="POST" action="{{ route('monitoreo_red.update', $monitoreoRed) }}">
        @csrf 
        @method('PUT')
        
        <div class="row g-3">
            <!-- Información Básica -->
            <div class="col-12">
                <h5 class="fw-bold text-brand mb-3"><i class="bi bi-calendar-event me-2"></i>Información General</h5>
            </div>

            <div class="col-md-4">
                <label for="fecha" class="form-label fw-semibold">Fecha *</label>
                <input type="date" name="fecha" id="fecha" 
                       class="form-control @error('fecha') is-invalid @enderror" 
                       value="{{ old('fecha', $monitoreoRed->fecha->format('Y-m-d')) }}" 
                       required 
                       max="{{ date('Y-m-d') }}">
                @error('fecha')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="responsable" class="form-label fw-semibold">Responsable *</label>
                <select name="responsable" id="responsable" 
                        class="form-select @error('responsable') is-invalid @enderror" required>
                    @foreach($usuariosTi as $usuario)
                        <option value="{{ $usuario->id }}" 
                            {{ old('responsable', $monitoreoRed->responsable) == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->usuario }} — {{ $usuario->nombres }}
                        </option>
                    @endforeach
                </select>
                @error('responsable')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Velocidades de Red -->
            <div class="col-12 mt-4">
                <h5 class="fw-bold text-brand mb-3"><i class="bi bi-speedometer2 me-2"></i>Velocidades de Red</h5>
            </div>

            <div class="col-md-4">
                <label for="velocidad_descarga" class="form-label fw-semibold">Velocidad de Descarga (Mbps) *</label>
                <input type="number" name="velocidad_descarga" id="velocidad_descarga" 
                       class="form-control @error('velocidad_descarga') is-invalid @enderror" 
                       value="{{ old('velocidad_descarga', $monitoreoRed->velocidad_descarga) }}" 
                       step="0.01" min="0" max="1000" 
                       required>
                @error('velocidad_descarga')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="velocidad_subida" class="form-label fw-semibold">Velocidad de Subida (Mbps) *</label>
                <input type="number" name="velocidad_subida" id="velocidad_subida" 
                       class="form-control @error('velocidad_subida') is-invalid @enderror" 
                       value="{{ old('velocidad_subida', $monitoreoRed->velocidad_subida) }}" 
                       step="0.01" min="0" max="1000" 
                       required>
                @error('velocidad_subida')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Calidad de Servicio -->
            <div class="col-12 mt-4">
                <h5 class="fw-bold text-brand mb-3"><i class="bi bi-wifi me-2"></i>Calidad de Servicio</h5>
            </div>

            <div class="col-md-4">
                <label for="porcentaje_experiencia_wifi" class="form-label fw-semibold">Experiencia WiFi (%) *</label>
                <input type="number" name="porcentaje_experiencia_wifi" id="porcentaje_experiencia_wifi" 
                       class="form-control @error('porcentaje_experiencia_wifi') is-invalid @enderror" 
                       value="{{ old('porcentaje_experiencia_wifi', $monitoreoRed->porcentaje_experiencia_wifi) }}" 
                       min="0" max="100" 
                       required>
                @error('porcentaje_experiencia_wifi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="clientes_conectados" class="form-label fw-semibold">Clientes Conectados *</label>
                <input type="number" name="clientes_conectados" id="clientes_conectados" 
                       class="form-control @error('clientes_conectados') is-invalid @enderror" 
                       value="{{ old('clientes_conectados', $monitoreoRed->clientes_conectados) }}" 
                       min="0" max="1000" 
                       required>
                @error('clientes_conectados')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Observaciones -->
            <div class="col-12 mt-4">
                <h5 class="fw-bold text-brand mb-3"><i class="bi bi-chat-text me-2"></i>Observaciones</h5>
            </div>

            <div class="col-12">
                <label for="observaciones" class="form-label fw-semibold">Observaciones</label>
                <textarea name="observaciones" id="observaciones" 
                          class="form-control @error('observaciones') is-invalid @enderror" 
                          rows="4" 
                          maxlength="500">{{ old('observaciones', $monitoreoRed->observaciones) }}</textarea>
                <div class="form-text">
                    <span id="charCount">{{ strlen($monitoreoRed->observaciones ?? '') }}</span>/500 caracteres
                </div>
                @error('observaciones')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Información de Auditoría -->
        <div class="mt-4 pt-3 border-top">
            <h6 class="fw-semibold text-muted mb-3">
                <i class="bi bi-info-circle me-2"></i>Información de Auditoría
            </h6>
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">
                        <strong>Registrado:</strong> 
                        {{ $monitoreoRed->created_at->format('d/m/Y H:i') }}
                    </small>
                </div>
                <div class="col-md-6">
                    <small class="text-muted">
                        <strong>Última actualización:</strong> 
                        {{ $monitoreoRed->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="text-end mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-save2 me-1"></i>Actualizar Monitoreo
            </button>
            <a href="{{ route('monitoreo_red.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Contador de caracteres para observaciones
    const observaciones = document.getElementById('observaciones');
    const charCount = document.getElementById('charCount');
    
    if (observaciones && charCount) {
        observaciones.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            
            if (this.value.length > 450) {
                charCount.className = 'text-warning';
            } else if (this.value.length > 490) {
                charCount.className = 'text-danger';
            } else {
                charCount.className = 'text-muted';
            }
        });
    }

    // Validación de fecha máxima (hoy)
    const fechaInput = document.getElementById('fecha');
    if (fechaInput) {
        fechaInput.max = new Date().toISOString().split('T')[0];
    }
});
</script>
@endpush