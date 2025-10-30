@extends('layouts.app')
@section('title', 'Detalles del Usuario TI')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-person-vcard me-2"></i>Detalle del Usuario</h1>
@endsection

@section('content')
<div class="card p-4 shadow-sm">
  <div class="row g-3">
    <div class="col-md-6">
      <p><strong>Usuario:</strong> {{ $usuario->usuario }}</p>
      <p><strong>Nombre:</strong> {{ $usuario->nombres }} {{ $usuario->apellidos }}</p>
      <p><strong>Puesto:</strong> {{ $usuario->puesto ?? '—' }}</p>
      <p><strong>Teléfono:</strong> {{ $usuario->telefono ?? '—' }}</p>
      <p><strong>Rol:</strong> <span class="badge bg-info">{{ $usuario->rol }}</span></p>
    </div>
    <div class="col-12 text-end">
      <a href="{{ route('usuarios-ti.edit', $usuario) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Editar</a>
      <a href="{{ route('usuarios-ti.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>
  </div>
</div>
@endsection
