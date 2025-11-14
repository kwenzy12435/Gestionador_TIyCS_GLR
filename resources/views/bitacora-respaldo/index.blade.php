@extends('layouts.app')
@section('title', 'Bitácora de Respaldo')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-hdd-stack me-2"></i>Bitácora de Respaldo</h1>
@endsection

@section('header-actions')
  <a href="{{ route('bitacora-respaldo.create') }}" class="btn btn-brand" data-loading="true">
    <i class="bi bi-plus-lg me-1"></i>Nuevo Registro
  </a>
@endsection

@section('content')

<div class="card p-3 shadow-sm card-hoverable">
  {{-- Buscador --}}
  <form method="GET" action="{{ route('bitacora-respaldo.index') }}" class="mb-3" role="search" aria-label="Buscar en bitácora">
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input
        type="text"
        name="search"
        class="form-control"
        placeholder="Buscar: empresa, estado, ubicación, acciones, fecha, usuario TI…"
        value="{{ $search ?? '' }}"
      >
      <button class="btn btn-outline-primary" type="submit" title="Buscar">
        <i class="bi bi-search"></i>
      </button>
      @if($search)
        <a href="{{ route('bitacora-respaldo.index') }}" class="btn btn-outline-secondary" title="Limpiar">
          <i class="bi bi-x-lg"></i>
        </a>
      @endif
    </div>

    <div class="mt-2 d-flex gap-2 flex-wrap">
      <button type="button" class="btn btn-sm btn-outline-secondary quick-search" data-search="contabilidad">
        Contabilidad
      </button>
      <button type="button" class="btn btn-sm btn-outline-secondary quick-search" data-search="nomina">
        Nómina
      </button>
      <button type="button" class="btn btn-sm btn-outline-success quick-search" data-search="Hecho">
        Hecho
      </button>
      <button type="button" class="btn btn-sm btn-outline-warning quick-search" data-search="no hecho">
        No Hecho
      </button>
    </div>
  </form>

  {{-- Tabla --}}
  <div class="table-responsive">
    <table class="table table-striped align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th style="width:80px">#</th>
          <th style="width:160px">Empresa</th>
          <th style="width:160px">Fecha Respaldo</th>
          <th style="width:120px">Estado</th>
          <th>Ubicación</th>
          <th style="width:220px">Responsable TI</th>
          <th class="text-end" style="width:160px">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bitacoras as $bitacora)
          @php
            $badgeEmpresa = match(strtolower((string)$bitacora->empresa_id)) {
              'contabilidad' => 'bg-primary',
              'nomina', 'nómina' => 'bg-info text-dark',
              default => 'bg-secondary'
            };

            $badgeEstado = match(strtolower((string)$bitacora->estado)) {
              'hecho'     => 'bg-success',
              'no hecho'  => 'bg-warning text-dark',
              default     => 'bg-secondary'
            };
          @endphp
          <tr>
            <td class="fw-semibold text-monospace">{{ $bitacora->id }}</td>

            <td>
              <span class="badge {{ $badgeEmpresa }} text-uppercase">
                {{ $bitacora->empresa_id ?? '—' }}
              </span>
            </td>

            <td class="text-nowrap">
              {{ optional($bitacora->fecha_respaldo)->format('d/m/Y') ?? '—' }}
            </td>

            <td>
              <span class="badge {{ $badgeEstado }}">{{ $bitacora->estado ?? '—' }}</span>
            </td>

            <td class="text-truncate" style="max-width: 260px" title="{{ $bitacora->ubicacion_guardado }}">
              {{ $bitacora->ubicacion_guardado ?? '—' }}
            </td>

            <td>
              @if($bitacora->usuarioTi)
                <small>
                  <strong>{{ $bitacora->usuarioTi->usuario }}</strong><br>
                  {{ $bitacora->usuarioTi->nombres }} {{ $bitacora->usuarioTi->apellidos }}
                </small>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>

            <td class="text-end text-nowrap">
              <a href="{{ route('bitacora-respaldo.show', $bitacora) }}"
                 class="btn btn-sm btn-outline-primary"
                 title="Ver detalles"
                 data-bs-toggle="tooltip">
                <i class="bi bi-eye"></i>
              </a>

              <a href="{{ route('bitacora-respaldo.edit', $bitacora) }}"
                 class="btn btn-sm btn-outline-warning"
                 title="Editar"
                 data-bs-toggle="tooltip"
                 data-loading="true">
                <i class="bi bi-pencil"></i>
              </a>

              <form
                action="{{ route('bitacora-respaldo.destroy', $bitacora) }}"
                method="POST"
                class="d-inline"
                data-confirm="¿Eliminar el registro #{{ $bitacora->id }}?"
                data-confirm-title="Eliminar registro"
                data-confirm-variant="danger"
                data-loading="true"
              >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" data-bs-toggle="tooltip">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              <i class="bi bi-hdd-stack display-5 d-block mb-2"></i>
              No hay registros de respaldo
              @if($search)
                <br><small>Intenta con otros términos de búsqueda</small>
              @endif
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Paginación --}}
  @if($bitacoras->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
      <div class="small text-muted">
        Mostrando {{ $bitacoras->firstItem() }}–{{ $bitacoras->lastItem() }} de {{ $bitacoras->total() }}
      </div>
      {{ $bitacoras->onEachSide(1)->links() }}
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
  // Botones de búsqueda rápida
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.quick-search').forEach(btn => {
      btn.addEventListener('click', function () {
        const input = document.querySelector('input[name="search"]');
        if (!input) return;
        input.value = this.dataset.search || '';
        input.closest('form').submit();
      });
    });
  });
</script>
@endpush
