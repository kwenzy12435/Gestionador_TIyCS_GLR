@extends('layouts.app')
@section('title', 'Reportes de Actividad')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-clipboard-data me-2"></i>Reportes de Actividad</h1>
@endsection

@section('content')


<div class="card shadow-sm">
  <div class="card-header bg-white py-3">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h5 class="mb-0">Lista de Reportes</h5>
      </div>
      <div class="col-md-6 text-end">
        <a href="{{ route('reporte_actividades.create') }}" class="btn btn-brand">
          <i class="bi bi-plus-circle me-1"></i>Nuevo Reporte
        </a>
      </div>
    </div>
  </div>

  <div class="card-body">
    <form method="GET" action="{{ route('reporte_actividades.index') }}" class="mb-4">
      <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Buscar en reportes..."
               value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">
          <i class="bi bi-search"></i>
        </button>
        @if(request('search'))
          <a href="{{ route('reporte_actividades.index') }}" class="btn btn-outline-danger">
            <i class="bi bi-x-circle"></i>
          </a>
        @endif
      </div>
    </form>

    @if($reportes->count() > 0)
      <div class="table-responsive">
        <table class="table table-hover table-striped">
          <thead class="table-light">
            <tr>
              <th>Fecha</th>
              <th>Actividad</th>
              <th>Colaborador</th>
              <th>Canal</th>
              <th>Usuario TI</th>
              <th width="120">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($reportes as $reporte)
              <tr>
                <td>{{ \Carbon\Carbon::parse($reporte->fecha)->format('d/m/Y') }}</td>
                <td class="fw-semibold">
                  {{ \Illuminate\Support\Str::limit($reporte->actividad, 80) }}
                </td>
                <td>
                  {{ $reporte->colaborador?->nombre_completo ?? '—' }}
                </td>
                <td>{{ $reporte->canal?->nombre ?? '—' }}</td>
                <td>{{ $reporte->usuarioTi?->usuario ?? '—' }}</td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <a href="{{ route('reporte_actividades.show', $reporte->id) }}"
                       class="btn btn-outline-primary" title="Ver">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('reporte_actividades.edit', $reporte->id) }}"
                       class="btn btn-outline-warning" title="Editar">
                      <i class="bi bi-pencil"></i>
                    </a>
                     <form action="{{ route('reporte_actividades.destroy', $reporte->id) }}"
      method="POST"
      class="d-inline"
      data-confirm="¿Eliminar este reporte?"
      data-confirm-title="Eliminar reporte de actividades"
      data-confirm-variant="danger">
  @csrf
  @method('DELETE')
  <button type="submit" class="btn btn-outline-danger" title="Eliminar">
    <i class="bi bi-trash"></i>
  </button>
</form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted">
          Mostrando {{ $reportes->firstItem() }} - {{ $reportes->lastItem() }} de {{ $reportes->total() }} registros
        </div>
        {{ $reportes->links() }}
      </div>
    @else
      <div class="text-center py-5">
        <i class="bi bi-clipboard-x display-1 text-muted"></i>
        <h4 class="text-muted mt-3">No se encontraron reportes</h4>
        @if(request('search'))
          <p class="text-muted">Intenta con otros términos de búsqueda</p>
          <a href="{{ route('reporte_actividades.index') }}" class="btn btn-brand mt-2">
            Ver todos los reportes
          </a>
        @else
          <a href="{{ route('reporte_actividades.create') }}" class="btn btn-brand mt-2">
            <i class="bi bi-plus-circle me-1"></i>Crear Primer Reporte
          </a>
        @endif
      </div>
    @endif
  </div>
</div>
@endsection
