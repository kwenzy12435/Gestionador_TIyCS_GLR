@extends('layouts.app')
@section('title', 'Detalle de Monitoreo')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-clipboard-check me-2"></i>Detalle del monitoreo</h1>
@endsection

@section('content')
<div class="card p-4 shadow-sm">
  <div class="row g-3">
    <div class="col-md-6">
      <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($monitoreo->fecha)->format('d/m/Y') }}</p>
      <p><strong>Hora:</strong> {{ $monitoreo->hora }}</p>
      <p><strong>Descarga:</strong> {{ number_format($monitoreo->velocidad_descarga,2) }} Mbps</p>
      <p><strong>Subida:</strong> {{ number_format($monitoreo->velocidad_subida,2) }} Mbps</p>
    </div>
    <div class="col-md-6">
      <p><strong>Experiencia WiFi:</strong> {{ $monitoreo->porcentaje_experiencia_wifi }} %</p>
      <p><strong>Clientes conectados:</strong> {{ $monitoreo->clientes_conectados }}</p>
      <p><strong>Responsable:</strong> {{ $monitoreo->usuarioResponsable?->usuario ?? '—' }}</p>
      <p><strong>Observaciones:</strong> {{ $monitoreo->observaciones ?: '—' }}</p>
    </div>
  </div>
  <div class="text-end mt-3">
    <a href="{{ route('monitoreo-red.edit', $monitoreo->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Editar</a>
    <a href="{{ route('monitoreo-red.index') }}" class="btn btn-outline-secondary">Volver</a>
  </div>
</div>
@endsection
