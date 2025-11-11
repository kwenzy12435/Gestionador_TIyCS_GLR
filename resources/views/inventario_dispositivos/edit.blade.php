@extends('layouts.app')
@section('title', 'Editar Dispositivo')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Dispositivo</h1>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-4 shadow-sm">
    <form method="POST" action="{{ route('inventario-dispositivos.update', $inventarioDispositivo) }}">
        @csrf 
        @method('PUT')
        
        <div class="row g-3">
            <!-- Información Básica -->
            <div class="col-12">
                <h5 class="fw-bold text-brand mb-3"><i class="bi bi-info-circle me-2"></i>Información Básica</h5>
            </div>

            <div class="col-md-3">
                <label for="estado" class="form-label fw-semibold">Estado *</label>
                <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                    @foreach($estados as $estado)
                        <option value="{{ $estado }}" 
                            {{ old('estado', $inventarioDispositivo->estado) == $estado ? 'selected' : '' }}>
                            {{ ucfirst($estado) }}
                        </option>
                    @endforeach
                </select>
                @error('estado')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="tipo_id" class="form-label fw-semibold">Tipo *</label>
                <select name="tipo_id" id="tipo_id" class="form-select @error('tipo_id') is-invalid @enderror" required>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" 
                            {{ old('tipo_id', $inventarioDispositivo->tipo_id) == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="marca_id" class="form-label fw-semibold">Marca *</label>
                <select name="marca_id" id="marca_id" class="form-select @error('marca_id') is-invalid @enderror" required>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->id }}" 
                            {{ old('marca_id', $inventarioDispositivo->marca_id) == $marca->id ? 'selected' : '' }}>
                            {{ $marca->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('marca_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="colaborador_id" class="form-label fw-semibold">Asignado a</label>
                <select name="colaborador_id" id="colaborador_id" class="form-select @error('colaborador_id') is-invalid @enderror">
                    <option value="">— Sin asignar —</option>
                    @foreach($colaboradores as $colaborador)
                        <option value="{{ $colaborador->id }}" 
                            {{ old('colaborador_id', $inventarioDispositivo->colaborador_id) == $colaborador->id ? 'selected' : '' }}>
                            {{ $colaborador->nombre }} {{ $colaborador->apellidos }}
                        </option>
                    @endforeach
                </select>
                @error('colaborador_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Información del Modelo y Series -->
            <div class="col-12 mt-4">
                <h5 class="fw-bold text-brand mb-3"><i class="bi bi-tags me-2"></i>Identificación</h5>
            </div>

            <div class="col-md-4">
                <label for="modelo" class="form-label fw-semibold">Modelo *</label>
                <input type="text" name="modelo" id="modelo" 
                       class="form-control @error('modelo') is-invalid @enderror" 
                       value="{{ old('modelo', $inventarioDispositivo->modelo) }}" 
                       required maxlength="100">
                @error('modelo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="numero_serie" class="form-label fw-semibold">Número de Serie *</label>
                <input type="text" name="numero_serie" id="numero_serie" 
                       class="form-control @error('numero_serie') is-invalid @enderror" 
                       value="{{ old('numero_serie', $inventarioDispositivo->numero_serie) }}" 
                       required maxlength="100">
                @error('numero_serie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="serie" class="form-label fw-semibold">Serie (Alterno)</label>
                <input type="text" name="serie" id="serie" 
                       class="form-control @error('serie') is-invalid @enderror" 
                       value="{{ old('serie', $inventarioDispositivo->serie) }}" 
                       maxlength="100">
                @error('serie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Especificaciones Técnicas -->
            <div class="col-12 mt-4">
                <h5 class="fw-bold text-brand mb-3"><i class="bi bi-cpu me-2"></i>Especificaciones Técnicas</h5>
            </div>

            <div class="col-md-4">
                <label for="mac" class="form-label fw-semibold">Dirección MAC</label>
                <input type="text" name="mac" id="mac" 
                       class="form-control @error('mac') is-invalid @enderror" 
                       value="{{ old('mac', $inventarioDispositivo->mac) }}" 
                       maxlength="50" 
                       placeholder="Formato: XX:XX:XX:XX:XX:XX">
                <div class="form-text">Formato: 00:1A:2B:3C:4D:5E o 00-1A-2B-3C-4D-5E</div>
                @error('mac')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="procesador" class="form-label fw-semibold">Procesador</label>
                <input type="text" name="procesador" id="procesador" 
                       class="form-control @error('procesador') is-invalid @enderror" 
                       value="{{ old('procesador', $inventarioDispositivo->procesador) }}" 
                       maxlength="100">
                @error('procesador')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="memoria_ram" class="form-label fw-semibold">Memoria RAM</label>
                <input type="text" name="memoria_ram" id="memoria_ram" 
                       class="form-control @error('memoria_ram') is-invalid @enderror" 
                       value="{{ old('memoria_ram', $inventarioDispositivo->memoria_ram) }}" 
                       maxlength="100">
                @error('memoria_ram')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="ssd" class="form-label fw-semibold">SSD</label>
                <input type="text" name="ssd" id="ssd" 
                       class="form-control @error('ssd') is-invalid @enderror" 
                       value="{{ old('ssd', $inventarioDispositivo->ssd) }}" 
                       maxlength="100">
                @error('ssd')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="hdd" class="form-label fw-semibold">HDD</label>
                <input type="text" name="hdd" id="hdd" 
                       class="form-control @error('hdd') is-invalid @enderror" 
                       value="{{ old('hdd', $inventarioDispositivo->hdd) }}" 
                       maxlength="100">
                @error('hdd')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="color" class="form-label fw-semibold">Color</label>
                <input type="text" name="color" id="color" 
                       class="form-control @error('color') is-invalid @enderror" 
                       value="{{ old('color', $inventarioDispositivo->color) }}" 
                       maxlength="50">
                @error('color')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Información de Compra y Garantía -->
            <div class="col-12 mt-4">
                <h5 class="fw-bold text-brand mb-3"><i class="bi bi-receipt me-2"></i>Información de Compra</h5>
            </div>

            <div class="col-md-4">
                <label for="costo" class="form-label fw-semibold">Costo (MXN)</label>
                <input type="number" name="costo" id="costo" 
                       class="form-control @error('costo') is-invalid @enderror" 
                       value="{{ old('costo', $inventarioDispositivo->costo) }}" 
                       step="0.01" min="0" max="999999.99">
                @error('costo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="fecha_compra" class="form-label fw-semibold">Fecha de Compra</label>
                <input type="date" name="fecha_compra" id="fecha_compra" 
                       class="form-control @error('fecha_compra') is-invalid @enderror" 
                       value="{{ old('fecha_compra', $inventarioDispositivo->fecha_compra ? $inventarioDispositivo->fecha_compra->format('Y-m-d') : '') }}">
                @error('fecha_compra')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="garantia_hasta" class="form-label fw-semibold">Garantía Hasta</label>
                <input type="date" name="garantia_hasta" id="garantia_hasta" 
                       class="form-control @error('garantia_hasta') is-invalid @enderror" 
                       value="{{ old('garantia_hasta', $inventarioDispositivo->garantia_hasta ? $inventarioDispositivo->garantia_hasta->format('Y-m-d') : '') }}">
                @error('garantia_hasta')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Botones -->
        <div class="text-end mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-save2 me-1"></i>Actualizar Dispositivo
            </button>
            <a href="{{ route('inventario-dispositivos.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de fecha de garantía
    const fechaCompra = document.getElementById('fecha_compra');
    const garantiaHasta = document.getElementById('garantia_hasta');
    
    if (fechaCompra && garantiaHasta) {
        fechaCompra.addEventListener('change', function() {
            if (this.value) {
                garantiaHasta.min = this.value;
            }
        });

        // Establecer min si ya hay fecha de compra
        if (fechaCompra.value) {
            garantiaHasta.min = fechaCompra.value;
        }
    }

    // Formato automático para MAC
    const macInput = document.getElementById('mac');
    if (macInput) {
        macInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^a-fA-F0-9]/g, '');
            if (value.length > 12) value = value.substr(0, 12);
            
            let formatted = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 2 === 0) {
                    formatted += ':';
                }
                formatted += value[i];
            }
            
            e.target.value = formatted.toUpperCase();
        });
    }
});
</script>
@endpush