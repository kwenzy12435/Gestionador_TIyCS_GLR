@extends('layouts.app')
@section('title', 'Reporte de Actividades')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-clipboard-data me-2"></i>Reportes de Actividad</h1>
@endsection

@section('header-actions')
  <a href="{{ route('reporte_actividades.create') }}" class="btn btn-brand">
    <i class="bi bi-plus-lg me-1"></i>Nuevo reporte
  </a>
@endsection

@section('content')
@include('Partials.flash')

<div class="card p-3 shadow-sm reporte-actividades-table">
  <form method="GET" class="mb-3">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Buscar por colaborador, canal, naturaleza o actividad..." value="{{ $search ?? '' }}">
      <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Actividad</th>
          <th>Colaborador</th>
          <th>Canal</th>
          <th>Naturaleza</th>
          <th>Usuario TI</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($reportes as $r)
          <tr>
            <td>{{ \Carbon\Carbon::parse($r->fecha)->format('d/m/Y') }}</td>
            <td>{{ $r->actividad }}</td>
            <td>{{ $r->colaborador?->nombres }} {{ $r->colaborador?->apellidos }}</td>
            <td>{{ $r->canal?->nombre ?? '—' }}</td>
            <td>{{ $r->naturaleza?->nombre ?? '—' }}</td>
            <td>{{ $r->usuarioTi?->usuario ?? '—' }}</td>
            <td class="text-end">
              <a href="{{ route('reporte_actividades.show', $r->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
              <a href="{{ route('reporte_actividades.edit', $r->id) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('reporte_actividades.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(this)">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-3">No hay reportes registrados.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/reporte_actividades.js')
@endpush
