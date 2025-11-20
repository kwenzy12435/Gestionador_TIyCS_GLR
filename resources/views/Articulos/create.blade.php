@extends('layouts.app')
@section('title', 'Nuevo Artículo')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i>Registrar artículo</h1>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-4 shadow-sm">
    {{-- Resumen de errores (opcional, ayuda al usuario) --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <div class="fw-semibold mb-1">Por favor corrige los siguientes campos:</div>
        <ul class="mb-0 small">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('articulos.store') }}" novalidate id="formArticulo">
        @csrf
        
        <div class="row g-3">
            {{-- Categoría --}}
            <div class="col-md-6">
                <label for="categoria_id" class="form-label fw-semibold">Categoría *</label>
                <select name="categoria_id" id="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror" required>
                    <option value="">Seleccionar categoría…</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" 
                            {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Subcategoría --}}
            <div class="col-md-6">
                <label for="subcategoria_id" class="form-label fw-semibold">Subcategoría</label>
                <select name="subcategoria_id" id="subcategoria_id" class="form-select @error('subcategoria_id') is-invalid @enderror">
                    <option value="">— Ninguna —</option>
                    @foreach($subcategorias as $subcategoria)
                        <option value="{{ $subcategoria->id }}" 
                            data-categoria="{{ $subcategoria->categoria_id }}"
                            {{ old('subcategoria_id') == $subcategoria->id ? 'selected' : '' }}>
                            {{ $subcategoria->nombre }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Se filtrará automáticamente según la categoría seleccionada.</div>
                @error('subcategoria_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nombre --}}
            <div class="col-12">
                <label for="nombre" class="form-label fw-semibold">Nombre del artículo *</label>
                <input type="text" name="nombre" id="nombre" 
                       class="form-control @error('nombre') is-invalid @enderror" 
                       value="{{ old('nombre') }}" 
                       required maxlength="150" 
                       placeholder="Ingrese el nombre del artículo">
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Descripción --}}
            <div class="col-12">
                <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                <textarea name="descripcion" id="descripcion" 
                          class="form-control @error('descripcion') is-invalid @enderror" 
                          rows="3" 
                          placeholder="Descripción opcional del artículo">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Cantidad y Unidades --}}
            <div class="col-md-3">
                <label for="cantidad" class="form-label fw-semibold">Cantidad *</label>
                <input type="number" name="cantidad" id="cantidad" 
                       class="form-control @error('cantidad') is-invalid @enderror" 
                       value="{{ old('cantidad', 0) }}" 
                       required min="0" step="1">
                @error('cantidad')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="unidades" class="form-label fw-semibold">Unidades *</label>
                <select name="unidades" id="unidades" class="form-select @error('unidades') is-invalid @enderror" required>
                    <option value="">Seleccionar…</option>
                    <option value="piezas"   {{ old('unidades') == 'piezas' ? 'selected' : '' }}>Piezas</option>
                    <option value="cajas"    {{ old('unidades') == 'cajas' ? 'selected' : '' }}>Cajas</option>
                    <option value="paquetes" {{ old('unidades') == 'paquetes' ? 'selected' : '' }}>Paquetes</option>
                </select>
                @error('unidades')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Ubicación y Fecha --}}
            <div class="col-md-3">
                <label for="ubicacion" class="form-label fw-semibold">Ubicación *</label>
                <select name="ubicacion" id="ubicacion" class="form-select @error('ubicacion') is-invalid @enderror" required>
                    <option value="">Seleccionar…</option>
                    @foreach(['cajon1','rafa','cajon4','almacen','oficina'] as $u)
                      <option value="{{ $u }}" {{ old('ubicacion') == $u ? 'selected' : '' }}>
                        {{ ucfirst($u) }}
                      </option>
                    @endforeach
                </select>
                @error('ubicacion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="fecha_ingreso" class="form-label fw-semibold">Fecha de ingreso *</label>
                <input type="date" name="fecha_ingreso" id="fecha_ingreso" 
                       class="form-control @error('fecha_ingreso') is-invalid @enderror" 
                       value="{{ old('fecha_ingreso', date('Y-m-d')) }}" 
                       required>
                @error('fecha_ingreso')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Estado --}}
            <div class="col-md-6">
                <label for="estado" class="form-label fw-semibold">Estado *</label>
                <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                    <option value="">Seleccionar…</option>
                    @foreach(['Disponible', 'pocas piezas', 'no disponible'] as $e)
                      <option value="{{ $e }}" {{ old('estado') == $e ? 'selected' : '' }}>{{ $e }}</option>
                    @endforeach
                </select>
                @error('estado')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Botones --}}
        <div class="text-end mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-brand">
                <i class="bi bi-check2 me-1"></i>Guardar artículo
            </button>
            <a href="{{ route('articulos.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg me-1"></i>Cancelar
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoriaSelect = document.getElementById('categoria_id');
    const subcategoriaSelect = document.getElementById('subcategoria_id');
    const subcategoriaOptions = Array.from(subcategoriaSelect.options);

    function filtrarSubcategorias() {
        const categoriaId = categoriaSelect.value;

        // limpia (deja sólo la primera)
        while (subcategoriaSelect.options.length > 1) {
            subcategoriaSelect.remove(1);
        }

        if (categoriaId) {
            subcategoriaOptions.forEach(option => {
                if (option.value && option.dataset.categoria === categoriaId) {
                    subcategoriaSelect.add(option);
                }
            });
        }
    }

    categoriaSelect.addEventListener('change', filtrarSubcategorias);

    if (categoriaSelect.value) {
        filtrarSubcategorias();
    }
});
</script>
@endpush
