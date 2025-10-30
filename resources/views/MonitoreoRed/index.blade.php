@extends('layouts.app')
@section('title', 'Monitoreo de Red')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-reception-4 me-2"></i>Monitoreo de Red</h1>
@endsection

@section('header-actions')
  <a href="{{ route('monitoreo-red.create') }}" class="btn btn-brand">
    <i class="bi bi-plus-lg me-1"></i>Nuevo registro
  </a>
@endsection

@section('content')
@include('Partials.flash')

<div class="card p-3 shadow-sm monitoreo-table">
  <form method="GET" class="mb-3">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Buscar por fecha, responsable, observaciones..." value="{{ $search ?? '' }}">
      <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Descarga (Mbps)</th>
          <th>Subida (Mbps)</th>
          <th>Experiencia WiFi (%)</th>
          <th>Clientes</th>
          <th>Responsable</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($monitoreos as $m)
          <tr>
            <td>{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y') }}</td>
            <td>{{ $m->hora }}</td>
            <td>{{ number_format($m->velocidad_descarga, 2) }}</td>
            <td>{{ number_format($m->velocidad_subida, 2) }}</td>
            <td>
              <span class="badge {{ $m->porcentaje_experiencia_wifi >= 80 ? 'bg-success' : ($m->porcentaje_experiencia_wifi >= 60 ? 'bg-warning text-dark' : 'bg-danger') }}">
                {{ $m->porcentaje_experiencia_wifi }}
              </span>
            </td>
            <td>{{ $m->clientes_conectados }}</td>
            <td>{{ $m->usuarioResponsable?->usuario ?? '—' }}</td>
            <td class="text-end">
              <a href="{{ route('monitoreo-red.show', $m->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
              <a href="{{ route('monitoreo-red.edit', $m->id) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('monitoreo-red.destroy', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(this)">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted py-3">Sin registros.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/monitoreo_red.js')
@endpush
