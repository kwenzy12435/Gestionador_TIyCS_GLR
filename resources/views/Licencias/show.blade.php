@extends('layouts.app')
@section('title', 'Detalle de Licencia')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-eye me-2"></i>Detalle de licencia</h1>
@endsection

@section('content')
<div class="card p-4 shadow-sm">
  <div class="row g-3">
    <div class="col-md-6">
      <p><strong>Cuenta:</strong> {{ $licencia->cuenta }}</p>
      <p><strong>Plataforma:</strong> {{ $licencia->plataforma?->nombre ?? '—' }}</p>
      <p><strong>Colaborador:</strong> {{ $licencia->colaborador?->nombres }} {{ $licencia->colaborador?->apellidos }} ({{ $licencia->colaborador?->email }})</p>
    </div>
    <div class="col-md-6">
      @if($licencia->expiracion)
        @php
          $exp = \Carbon\Carbon::parse($licencia->expiracion);
          $days = now()->diffInDays($exp, false);
          $badge = $days < 0 ? 'bg-danger' : ($days <= 7 ? 'bg-warning text-dark' : 'bg-success');
        @endphp
        <p><strong>Expiración:</strong> <span class="badge {{ $badge }}">{{ $exp->format('d/m/Y') }} ({{ $days < 0 ? $days : "+$days" }} días)</span></p>
      @else
        <p><strong>Expiración:</strong> <span class="badge bg-secondary">—</span></p>
      @endif
    </div>
  </div>

  <div class="d-flex gap-2 mt-3">
    <a href="{{ route('licencias.edit', $licencia->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Editar</a>
    <a href="{{ route('licencias.ver_contrasena', $licencia->id) }}" class="btn btn-outline-dark"><i class="bi bi-eye-slash"></i> Ver contraseña</a>
    <a href="{{ route('licencias.index') }}" class="btn btn-outline-secondary">Volver</a>
  </div>
</div>
@endsection
