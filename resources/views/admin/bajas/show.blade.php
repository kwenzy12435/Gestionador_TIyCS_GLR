@extends('layouts.app')
@section('title','Detalle de Baja')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-archive me-2"></i>Detalle de Baja</h1>
@endsection

@section('content')
<div class="card p-4 shadow-sm">
  <div class="row g-3">
    <div class="col-md-6">
      <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($baja->fecha)->format('d/m/Y') }}</p>
      <p><strong>Tipo:</strong> {{ $baja->tipo }}</p>
      <p><strong>Marca:</strong> {{ $baja->marca_nombre ?? '—' }}</p>
      <p><strong>Modelo:</strong> {{ $baja->modelo }}</p>
      <p><strong>N.º de serie:</strong> {{ $baja->numero_serie }}</p>
      <p><strong>MAC:</strong> {{ $baja->mac_address ?? '—' }}</p>
    </div>
    <div class="col-md-6">
      <p><strong>Usuario asociado:</strong> {{ $baja->usuario_nombre ?? '—' }}</p>
      <p><strong>Registro ID:</strong> {{ $baja->registro_id ?? '—' }}</p>
      <p><strong>Razón de baja:</strong> {{ $baja->razon_baja ?? '—' }}</p>
      <p><strong>Observaciones:</strong> {{ $baja->observaciones ?? '—' }}</p>
      <p><strong>Responsable TI:</strong>
        @if($baja->ti_usuario)
          {{ $baja->ti_usuario }}
        @elseif($baja->ti_nombres || $baja->ti_apellidos)
          {{ $baja->ti_nombres }} {{ $baja->ti_apellidos }}
        @else
          —
        @endif
      </p>
      <p><strong>Puesto TI:</strong> {{ $baja->ti_puesto ?? '—' }}</p>
      <p><strong>Teléfono TI:</strong> {{ $baja->ti_telefono ?? '—' }}</p>
    </div>
  </div>

  <div class="text-end mt-3">
    <a href="{{ route('admin.bajas.index', request()->query()) }}" class="btn btn-outline-secondary">Volver</a>
    <a href="{{ route('admin.bajas.export.pdf', request()->query()) }}" class="btn btn-outline-secondary">
      <i class="bi bi-filetype-pdf me-1"></i> Exportar PDF
    </a>
  </div>
</div>
@endsection
