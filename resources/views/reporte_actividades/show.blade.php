@extends('layouts.app')
@section('title', 'Detalle del Reporte')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-clipboard-check me-2"></i>Detalle del Reporte</h1>
@endsection

@section('content')
<div class="card p-4 shadow-sm">
  <div class="row g-3">
    <div class="col-md-6">
      <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reporte->fecha)->format('d/m/Y') }}</p>
      <p><strong>Actividad:</strong> {{ $reporte->actividad }}</p>
      <p><strong>Descripción:</strong> {{ $reporte->descripcion }}</p>
    </div>
    <div class="col-md-6">
      <p><strong>Colaborador:</strong> {{ $reporte->colaborador?->nombres }} {{ $reporte->colaborador?->apellidos }}</p>
      <p><strong>Canal:</strong> {{ $reporte->canal?->nombre ?? '—' }}</p>
      <p><strong>Naturaleza:</strong> {{ $reporte->naturaleza?->nombre ?? '—' }}</p>
      <p><strong>Usuario TI:</strong> {{ $reporte->usuarioTi?->usuario ?? '—' }}</p>
    </div>
  </div>
  <div class="text-end mt-3">
    <a href="{{ route('reporte_actividades.edit', $reporte->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Editar</a>
    <a href="{{ route('reporte_actividades.index') }}" class="btn btn-outline-secondary">Volver</a>
  </div>
</div>
@endsection
