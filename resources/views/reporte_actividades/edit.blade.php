@extends('layouts.app')
@section('title', 'Editar Reporte de Actividad')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Reporte</h1>
@endsection

@section('content')
@include('Partials.flash')

<div class="card p-4 shadow-sm">
  <form method="POST" action="{{ route('reporte_actividades.update', $reporte->id) }}">
    @csrf
    @method('PUT')
    <div class="row g-3">

      <div class="col-md-4">
        <label for="fecha" class="form-label fw-semibold">Fecha <span class="text-danger">*</span></label>
        <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" 
               value="{{ old('fecha', $reporte->fecha) }}" required>
        @error('fecha')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4">
        <label for="colaborador_id" class="form-label fw-semibold">Colaborador</label>
        <select name="colaborador_id" id="colaborador_id" class="form-select @error('colaborador_id') is-invalid @enderror">
          <option value="">Seleccionar...</option>
          @foreach($colaboradores as $c)
            <option value="{{ $c->id }}" 
              {{ (old('colaborador_id', $reporte->colaborador_id) == $c->id) ? 'selected' : '' }}>
              {{ $c->nombres }} {{ $c->apellidos }}
            </option>
          @endforeach
        </select>
        @error('colaborador_id')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4">
        <label for="canal_id" class="form-label fw-semibold">Canal</label>
        <select name="canal_id" id="canal_id" class="form-select @error('canal_id') is-invalid @enderror">
          <option value="">Seleccionar...</option>
          @foreach($canales as $c)
            <option value="{{ $c->id }}" 
              {{ (old('canal_id', $reporte->canal_id) == $c->id) ? 'selected' : '' }}>
              {{ $c->nombre }}
            </option>
          @endforeach
        </select>
        @error('canal_id')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label for="naturaleza_id" class="form-label fw-semibold">Naturaleza</label>
        <select name="naturaleza_id" id="naturaleza_id" class="form-select @error('naturaleza_id') is-invalid @enderror">
          <option value="">Seleccionar...</option>
          @foreach($naturalezas as $n)
            <option value="{{ $n->id }}" 
              {{ (old('naturaleza_id', $reporte->naturaleza_id) == $n->id) ? 'selected' : '' }}>
              {{ $n->nombre }}
            </option>
          @endforeach
        </select>
        @error('naturaleza_id')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label for="usuario_ti_id" class="form-label fw-semibold">Usuario TI</label>
        <select name="usuario_ti_id" id="usuario_ti_id" class="form-select @error('usuario_ti_id') is-invalid @enderror">
          <option value="">Seleccionar...</option>
          @foreach($usuariosTi as $u)
            <option value="{{ $u->id }}" 
              {{ (old('usuario_ti_id', $reporte->usuario_ti_id) == $u->id) ? 'selected' : '' }}>
              {{ $u->usuario }} ({{ $u->nombres }} {{ $u->apellidos }})
            </option>
          @endforeach
        </select>
        @error('usuario_ti_id')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label for="actividad" class="form-label fw-semibold">Actividad <span class="text-danger">*</span></label>
        <input type="text" name="actividad" id="actividad" class="form-control @error('actividad') is-invalid @enderror" 
               value="{{ old('actividad', $reporte->actividad) }}" required>
        @error('actividad')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-12">
        <label for="descripcion" class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
        <textarea name="descripcion" id="descripcion" rows="4" class="form-control @error('descripcion') is-invalid @enderror" 
                  required>{{ old('descripcion', $reporte->descripcion) }}</textarea>
        @error('descripcion')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

    </div>

    <div class="text-end mt-4">
      <button type="submit" class="btn btn-brand">
        <i class="bi bi-save2 me-1"></i>Actualizar Reporte
      </button>
      <a href="{{ route('reporte_actividades.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Cancelar
      </a>
    </div>
  </form>
</div>
@endsection