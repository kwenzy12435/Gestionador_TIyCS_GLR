@extends('layouts.app')
@section('title', 'Artículos')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i>Artículos</h1>
@endsection

@section('header-actions')
  <a href="{{ route('articulos.create') }}" class="btn btn-brand" data-loading="true">
    <i class="bi bi-plus-lg me-1"></i>Nuevo artículo
  </a>
@endsection

@section('content')


<div class="card p-3 shadow-sm card-hoverable">
  {{-- Buscador --}}
  <form method="GET" class="mb-3" action="{{ route('articulos.index') }}" role="search" aria-label="Buscar artículos">
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input
        type="text"
        name="search"
        class="form-control"
        placeholder="Buscar: nombre, descripción, unidades, ubicación, estado, fecha…"
        value="{{ $search ?? '' }}"
      >
      <button class="btn btn-outline-primary" type="submit" title="Buscar">
        <i class="bi bi-search"></i>
      </button>
      @if($search)
        <a href="{{ route('articulos.index') }}" class="btn btn-outline-secondary" title="Limpiar">
          Limpiar
        </a>
      @endif
    </div>
  </form>

  {{-- Tabla --}}
  <div class="table-responsive">
    <table class="table table-striped align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th style="width:80px">#</th>
          <th>Nombre</th>
          <th>Categoría</th>
          <th>Subcategoría</th>
          <th class="text-end" style="width:120px">Cantidad</th>
          <th style="width:120px">Unidades</th>
          <th style="width:140px">Ubicación</th>
          <th style="width:140px">Fecha ingreso</th>
          <th style="width:120px">Estado</th>
          <th class="text-end" style="width:160px">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($articulos as $articulo)
          @php
            $badgeEstado = match($articulo->estado) {
              'Disponible'     => 'bg-success',
              'pocas piezas'   => 'bg-warning text-dark',
              'no disponible'  => 'bg-secondary',
              default          => 'bg-light text-dark'
            };
            $badgeUbicacion = match(strtolower((string)$articulo->ubicacion)) {
              'almacen', 'almacén' => 'bg-primary',
              'oficina'            => 'bg-info text-dark',
              default              => 'bg-dark'
            };
          @endphp
          <tr>
            <td class="fw-semibold text-monospace">{{ $articulo->id }}</td>
            <td class="fw-semibold">
              <a href="{{ route('articulos.show', $articulo) }}" class="link-underline link-underline-opacity-0">
                {{ $articulo->nombre }}
              </a>
            </td>
            <td>{{ $articulo->categoria->nombre ?? '—' }}</td>
            <td>{{ $articulo->subcategoria->nombre ?? '—' }}</td>
            <td class="fw-bold text-end">{{ number_format($articulo->cantidad) }}</td>
            <td><span class="badge bg-light text-dark text-uppercase">{{ $articulo->unidades }}</span></td>
            <td>
              <span class="badge {{ $badgeUbicacion }} text-uppercase">
                {{ $articulo->ubicacion ?? '—' }}
              </span>
            </td>
            <td class="text-nowrap">
              {{ optional($articulo->fecha_ingreso)->format('d/m/Y') ?? '—' }}
            </td>
            <td>
              <span class="badge {{ $badgeEstado }}">{{ $articulo->estado ?? '—' }}</span>
            </td>
            <td class="text-end text-nowrap">
              <a href="{{ route('articulos.show', $articulo) }}"
                 class="btn btn-sm btn-outline-primary"
                 title="Ver"
                 data-bs-toggle="tooltip">
                <i class="bi bi-eye"></i>
              </a>

              <a href="{{ route('articulos.edit', $articulo) }}"
                 class="btn btn-sm btn-outline-warning"
                 title="Editar"
                 data-bs-toggle="tooltip"
                 data-loading="true">
                <i class="bi bi-pencil"></i>
              </a>

              <form
                action="{{ route('articulos.destroy', $articulo) }}"
                method="POST"
                class="d-inline"
                data-confirm="¿Eliminar el artículo «{{ $articulo->nombre }}»?"
                data-confirm-title="Eliminar artículo"
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
            <td colspan="10" class="text-center text-muted py-4">
              <i class="bi bi-inbox display-5 d-block mb-2"></i>
              No hay artículos registrados
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
  @if($articulos->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
      <div class="small text-muted">
        Mostrando {{ $articulos->firstItem() }}–{{ $articulos->lastItem() }} de {{ $articulos->total() }}
      </div>
      {{ $articulos->onEachSide(1)->links() }}
    </div>
  @endif
</div>
@endsection
