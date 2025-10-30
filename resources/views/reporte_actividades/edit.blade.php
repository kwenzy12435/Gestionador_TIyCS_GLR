@extends('layouts.app')
@section('title', 'Editar Reporte de Actividad')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Reporte</h1>
@endsection

@section('content')
<div class="card p-4 shadow-sm">
  <form method="POST" action="{{ route('reporte_actividades.update', $reporte->id) }}">
    @csrf
    @method('PUT')
    <div class="row g-3">

      <div class="col-md-4">
        <label class="form-label">Fecha</label>
        <input type="date" name="fecha" class="form-control" value="{{ $reporte->fecha }}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Colaborador</label>
        <select name="colaborador_id" class="form-select">
          <option value="">Seleccionar...</option>
          @foreach($colaboradores as $c)
            <option value="{{ $c->id }}" @selected($reporte->colaborador_id==$c->id)>
              {{ $c->nombres }} {{ $c->apellidos }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Canal</label>
        <select name="canal_id" class="form-select">
          <option value="">Seleccionar...</option>
          @foreach($canales as $c)
            <option value="{{ $c->id }}" @selected($reporte->canal_id==$c->id)>{{ $c->nombre }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Naturaleza</label>
        <select name="naturaleza_id" class="form-select">
          <option value="">Seleccionar...</option>
          @foreach($naturalezas as $n)
            <option value="{{ $n->id }}" @selected($reporte->naturaleza_id==$n->id)>{{ $n->nombre }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Usuario TI</label>
        <select name="usuario_ti_id" class="form-select">
          <option value="">Seleccionar...</option>
          @foreach($usuariosTi as $u)
            <option value="{{ $u->id }}" @selected($reporte->usuario_ti_id==$u->id)>{{ $u->usuario }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Actividad</label>
        <input type="text" name="actividad" class="form-control" value="{{ $reporte->actividad }}" required>
      </div>

      <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" rows="4" class="form-control" required>{{ $reporte->descripcion }}</textarea>
      </div>

    </div>

    <div class="text-end mt-4">
      <button class="btn btn-brand"><i class="bi bi-save2 me-1"></i>Actualizar</button>
      <a href="{{ route('reporte_actividades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
