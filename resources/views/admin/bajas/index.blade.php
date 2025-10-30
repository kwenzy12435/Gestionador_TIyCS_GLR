@extends('layouts.app')
@section('title','Log de Bajas (ADMIN)')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-archive me-2"></i>Log de Bajas</h1>
@endsection

@section('header-actions')
  {{-- Exportar PDF preservando filtros actuales --}}
  <a href="{{ route('admin.bajas.export.pdf', request()->query()) }}" class="btn btn-outline-secondary">
    <i class="bi bi-filetype-pdf me-1"></i> Exportar PDF
  </a>
@endsection

@section('content')
@include('Partials.flash')

<div class="card p-3 shadow-sm admin-bajas">
  {{-- Filtros --}}
  <form method="GET" action="{{ route('admin.bajas.index') }}" class="mb-3" id="filtroBajas">
    <div class="row g-2 align-items-end">
      <div class="col-12 col-md-4">
        <label class="form-label">Buscar</label>
        <input type="text" name="search" class="form-control"
               placeholder="Modelo, serie, usuario, MAC, razón, TI, marca…"
               value="{{ request('search','') }}">
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label">Desde</label>
        <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label">Hasta</label>
        <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
      </div>
      <div class="col-12 col-md-2 d-grid">
        <button class="btn btn-brand" type="submit"><i class="bi bi-search me-1"></i>Filtrar</button>
      </div>
    </div>
    <div class="mt-2 d-flex gap-2 flex-wrap">
      <button class="btn btn-sm btn-outline-secondary" type="button" data-preset="hoy">Hoy</button>
      <button class="btn btn-sm btn-outline-secondary" type="button" data-preset="7d">Últimos 7 días</button>
      <button class="btn btn-sm btn-outline-secondary" type="button" data-preset="30d">Últimos 30 días</button>
      <a href="{{ route('admin.bajas.index') }}" class="btn btn-sm btn-outline-danger">Limpiar</a>
    </div>
  </form>

  {{-- Tabla --}}
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Tipo</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>N.º Serie</th>
          <th>Usuario</th>
          <th>MAC</th>
          <th>TI responsable</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bajas as $b)
          <tr>
            <td>{{ \Carbon\Carbon::parse($b->fecha)->format('d/m/Y') }}</td>
            <td>{{ $b->tipo }}</td>
            <td>{{ $b->marca_nombre ?? '—' }}</td>
            <td>{{ $b->modelo }}</td>
            <td class="text-nowrap">{{ $b->numero_serie }}</td>
            <td>{{ $b->usuario_nombre }}</td>
            <td class="text-monospace">{{ $b->mac_address ?? '—' }}</td>
            <td>{{ $b->ti_usuario ?? ($b->ti_nombres ? $b->ti_nombres.' '.$b->ti_apellidos : '—') }}</td>
            <td class="text-end">
              <a href="{{ route('admin.bajas.show', $b->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr><td colspan="9" class="text-center text-muted py-3">No hay registros.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Paginación con filtros preservados --}}
  <div class="mt-2">
    {{ $bajas->withQueryString()->links() }}
  </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/admin_bajas.js')
@endpush
