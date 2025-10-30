@extends('layouts.app')
@section('title', 'Editar Monitoreo')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar monitoreo</h1>
@endsection

@section('content')
<div class="card p-4 shadow-sm">
  <form method="POST" action="{{ route('monitoreo-red.update', $monitoreo->id) }}">
    @csrf
    @method('PUT')
    <div class="row g-3">

      <div class="col-md-4">
        <label class="form-label">Fecha</label>
        <input type="date" name="fecha" class="form-control" value="{{ $monitoreo->fecha }}" required max="{{ now()->toDateString() }}">
      </div>

      <div class="col-md-4">
        <label class="form-label">Velocidad de descarga (Mbps)</label>
        <input type="number" step="0.01" min="0" max="1000" name="velocidad_descarga" class="form-control" value="{{ $monitoreo->velocidad_descarga }}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Velocidad de subida (Mbps)</label>
        <input type="number" step="0.01" min="0" max="1000" name="velocidad_subida" class="form-control" value="{{ $monitoreo->velocidad_subida }}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Experiencia WiFi (%)</label>
        <input type="number" min="0" max="100" name="porcentaje_experiencia_wifi" class="form-control" value="{{ $monitoreo->porcentaje_experiencia_wifi }}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Clientes conectados</label>
        <input type="number" min="0" max="1000" name="clientes_conectados" class="form-control" value="{{ $monitoreo->clientes_conectados }}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Responsable</label>
        <select name="responsable" class="form-select" required>
          <option value="">Seleccionar...</option>
          @foreach($usuariosTi as $u)
            <option value="{{ $u->id }}" @selected($monitoreo->responsable==$u->id)>
              {{ $u->id }} — {{ $u->usuario }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-12">
        <label class="form-label">Observaciones (opcional)</label>
        <textarea name="observaciones" rows="3" class="form-control" maxlength="500">{{ $monitoreo->observaciones }}</textarea>
      </div>

    </div>
    <div class="text-end mt-4">
      <button class="btn btn-brand"><i class="bi bi-save2 me-1"></i>Actualizar</button>
      <a href="{{ route('monitoreo-red.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
