@extends('layouts.app')
@section('title', 'Detalle del Reporte')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-clipboard-check me-2"></i>Detalle del Reporte</h1>
@endsection

@section('content')
@include('Partials.flash')

<div class="card shadow-sm">
  <div class="card-header bg-white">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h5 class="mb-0">Información del Reporte</h5>
      </div>
      <div class="col-md-6 text-end">
        <div class="btn-group">
          <a href="{{ route('reporte_actividades.edit', $reporte->id) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil me-1"></i>Editar
          </a>
          <form action="{{ route('reporte_actividades.destroy', $reporte->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" 
                    onclick="return confirm('¿Estás seguro de eliminar este reporte?')">
              <i class="bi bi-trash me-1"></i>Eliminar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="card-body">
    <div class="row g-4">
      <div class="col-md-6">
        <div class="border-bottom pb-3 mb-3">
          <h6 class="fw-semibold text-primary mb-2"><i class="bi bi-calendar-event me-2"></i>Información Principal</h6>
          <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reporte->fecha)->format('d/m/Y') }}</p>
          <p><strong>Actividad:</strong> {{ $reporte->actividad }}</p>
        </div>
        
        <div>
          <h6 class="fw-semibold text-primary mb-2"><i class="bi bi-card-text me-2"></i>Descripción</h6>
          <div class="bg-light p-3 rounded">
            {{ $reporte->descripcion }}
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="border-bottom pb-3 mb-3">
          <h6 class="fw-semibold text-primary mb-2"><i class="bi bi-people me-2"></i>Relaciones</h6>
          <p>
            <strong>Colaborador:</strong> 
            @if($reporte->colaborador)
              <span class="badge bg-info">{{ $reporte->colaborador->nombres }} {{ $reporte->colaborador->apellidos }}</span>
            @else
              <span class="text-muted">—</span>
            @endif
          </p>
          <p>
            <strong>Canal:</strong> 
            @if($reporte->canal)
              <span class="badge bg-secondary">{{ $reporte->canal->nombre }}</span>
            @else
              <span class="text-muted">—</span>
            @endif
          </p>
          <p>
            <strong>Naturaleza:</strong> 
            @if($reporte->naturaleza)
              <span class="badge bg-success">{{ $reporte->naturaleza->nombre }}</span>
            @else
              <span class="text-muted">—</span>
            @endif
          </p>
          <p>
            <strong>Usuario TI:</strong> 
            @if($reporte->usuarioTi)
              <span class="badge bg-primary">{{ $reporte->usuarioTi->usuario }}</span>
              <small class="text-muted">({{ $reporte->usuarioTi->nombres }} {{ $reporte->usuarioTi->apellidos }})</small>
            @else
              <span class="text-muted">—</span>
            @endif
          </p>
        </div>

        <div>
          <h6 class="fw-semibold text-primary mb-2"><i class="bi bi-clock me-2"></i>Auditoría</h6>
          <p><strong>Creado:</strong> {{ $reporte->created_at->format('d/m/Y H:i') }}</p>
          <p><strong>Actualizado:</strong> {{ $reporte->updated_at->format('d/m/Y H:i') }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="card-footer bg-white text-end">
    <a href="{{ route('reporte_actividades.index') }}" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i>Volver al Listado
    </a>
  </div>
</div>
@endsection