@extends('layouts.app')
@section('title', 'Nuevo Monitoreo')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-clipboard-plus me-2"></i>Registrar monitoreo</h1>
@endsection

@section('content')
@include('Partials.flash')

<div class="card p-4 shadow-sm">
  <form method="POST" action="{{ route('monitoreo-red.store') }}" novalidate>
    @csrf
    <div class="row g-3">

      <div class="col-md-4">
        <label class="form-label">Fecha</label>
        <input type="date" name="fecha" class="form-control" required max="{{ now()->toDateString() }}">
      </div>

      <div class="col-md-4">
        <label class="form-label">Velocidad de descarga (Mbps)</label>
        <input type="number" step="0.01" min="0" max="1000" name="velocidad_descarga" class="form-control" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Velocidad de subida (Mbps)</label>
        <input type="number" step="0.01" min="0" max="1000" name="velocidad_subida" class="form-control" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Experiencia WiFi (%)</label>
        <input type="number" min="0" max="100" name="porcentaje_experiencia_wifi" class="form-control" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Clientes conectados</label>
        <input type="number" min="0" max="1000" name="clientes_conectados" class="form-control" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Responsable</label>
        <select name="responsable" class="form-select" required>
          <option value="">Seleccionar...</option>
          @foreach($usuariosTi as $u)
            <option value="{{ $u->id }}">{{ $u->usuario }} — {{ $u->nombres }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-12">
        <label class="form-label">Observaciones (opcional)</label>
        <textarea name="observaciones" rows="3" class="form-control" maxlength="500" placeholder="Notas, incidencias, jitter, latencia, pérdidas, etc."></textarea>
      </div>

    </div>
    <div class="text-end mt-4">
      <button type="submit" class="btn btn-brand"><i class="bi bi-check2 me-1"></i>Guardar</button>
      <a href="{{ route('monitoreo-red.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
