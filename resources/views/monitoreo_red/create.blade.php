@extends('layouts.app')
@section('title', 'Nuevo Monitoreo de Red')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-clipboard-plus me-2"></i>Registrar Monitoreo de Red</h1>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-4 shadow-sm">
    <form method="POST" action="{{ route('monitoreo_red.store') }}" novalidate>
        @csrf
        
        <div class="row g-3">
            <!-- Información Básica -->
            <div class="col-12">
                <h5 class="fw-bold text-brand mb-3"><i class="bi bi-calendar-event me-2"></i>Información General</h5>
            </div>

            <div class="col-md-4">
                <label for="fecha" class="form-label fw-semibold">Fecha *</label>
                <input type="date" name="fecha" id="fecha" 
                       class="form-control @error('fecha') is-invalid @enderror" 
                       value="{{ old('fecha', date('Y-m-d')) }}" 
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
                    <option value="">Seleccionar responsable...</option>
                    @foreach($usuariosTi as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('responsable') == $usuario->id ? 'selected' : '' }}>
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
                       value="{{ old('velocidad_descarga') }}" 
                       step="0.01" min="0" max="1000" 
                       required 
                       placeholder="0.00">
                <div class="form-text">Velocidad medida en Mbps</div>
                @error('velocidad_descarga')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="velocidad_subida" class="form-label fw-semibold">Velocidad de Subida (Mbps) *</label>
                <input type="number" name="velocidad_subida" id="velocidad_subida" 
                       class="form-control @error('velocidad_subida') is-invalid @enderror" 
                       value="{{ old('velocidad_subida') }}" 
                       step="0.01" min="0" max="1000" 
                       required 
                       placeholder="0.00">
                <div class="form-text">Velocidad medida en Mbps</div>
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
                       value="{{ old('porcentaje_experiencia_wifi') }}" 
                       min="0" max="100" 
                       required 
                       placeholder="0-100">
                <div class="form-text">Porcentaje de calidad de experiencia WiFi</div>
                @error('porcentaje_experiencia_wifi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="clientes_conectados" class="form-label fw-semibold">Clientes Conectados *</label>
                <input type="number" name="clientes_conectados" id="clientes_conectados" 
                       class="form-control @error('clientes_conectados') is-invalid @enderror" 
                       value="{{ old('clientes_conectados') }}" 
                       min="0" max="1000" 
                       required 
                       placeholder="0">
                <div class="form-text">Número total de clientes conectados</div>
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
                          maxlength="500"
                          placeholder="Notas, incidencias, jitter, latencia, pérdidas, etc.">{{ old('observaciones') }}</textarea>
                <div class="form-text">
                    <span id="charCount">0</span>/500 caracteres
                </div>
                @error('observaciones')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Botones -->
        <div class="text-end mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-brand">
                <i class="bi bi-check2 me-1"></i>Guardar Monitoreo
            </button>
            <a href="{{ route('monitoreo_red.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg me-1"></i>Cancelar
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
        
        // Inicializar contador
        charCount.textContent = observaciones.value.length;
    }

    // Validación de fecha máxima (hoy)
    const fechaInput = document.getElementById('fecha');
    if (fechaInput) {
        fechaInput.max = new Date().toISOString().split('T')[0];
    }

    // Indicadores visuales para velocidades
    const velocidadDescarga = document.getElementById('velocidad_descarga');
    const velocidadSubida = document.getElementById('velocidad_subida');
    const experienciaWifi = document.getElementById('porcentaje_experiencia_wifi');

    function updateVisualFeedback(input, goodThreshold, warningThreshold) {
        input.addEventListener('input', function() {
            const value = parseFloat(this.value) || 0;
            
            if (value >= goodThreshold) {
                this.classList.remove('is-warning', 'is-danger');
                this.classList.add('is-valid');
            } else if (value >= warningThreshold) {
                this.classList.remove('is-valid', 'is-danger');
                this.classList.add('is-warning');
            } else {
                this.classList.remove('is-valid', 'is-warning');
                this.classList.add('is-danger');
            }
        });
    }

    // Aplicar feedback visual
    if (velocidadDescarga) updateVisualFeedback(velocidadDescarga, 100, 50);
    if (velocidadSubida) updateVisualFeedback(velocidadSubida, 50, 20);
    if (experienciaWifi) updateVisualFeedback(experienciaWifi, 80, 60);
});
</script>

<style>
.is-valid { border-color: #198754 !important; }
.is-warning { border-color: #ffc107 !important; }
.is-danger { border-color: #dc3545 !important; }
</style>
@endpush