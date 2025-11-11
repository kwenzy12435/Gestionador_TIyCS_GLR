@extends('layouts.app')
@section('title', 'Configuración del Sistema')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold">
    <i class="bi bi-gear-wide-connected me-2"></i>Configuración del Sistema
  </h1>
@endsection

@section('header-actions')
  <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#registroModal" data-action="create">
    <i class="bi bi-plus-lg me-1"></i>Nuevo registro
  </button>
@endsection

@section('content')
@include('partials.flash')

<div class="admin-configsistem">
  <ul class="nav nav-pills mb-3 flex-wrap gap-2">
    @foreach($tablas as $slug => $nombre)
      <li class="nav-item">
        <a class="nav-link {{ $tabla_actual === $slug ? 'active' : '' }}"
           href="{{ route('admin.configsistem.index', ['tabla' => $slug]) }}">
          {{ $nombre }}
        </a>
      </li>
    @endforeach
  </ul>

  <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
    <div class="h5 mb-0 fw-semibold">{{ $nombre_tabla }}</div>

    <form method="GET" action="{{ route('admin.configsistem.index', ['tabla' => $tabla_actual]) }}" class="d-flex gap-2">
      <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar..."
             value="{{ request('search') }}">
      <button class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i></button>
      <a href="{{ route('admin.configsistem.index', ['tabla' => $tabla_actual]) }}" class="btn btn-sm btn-outline-danger">
        Limpiar
      </a>
    </form>
  </div>

  <div class="card p-3 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th style="width:80px">ID</th>
            @if($tabla_actual === 'subcategorias')
              <th>Categoría</th>
            @endif
            <th>Nombre</th>
            <th class="text-end" style="width:160px">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @php
            $filtered = collect($datos);
            if (request('search')) {
              $s = mb_strtolower(request('search'));
              $filtered = $filtered->filter(function($row) use ($s, $tabla_actual) {
                $nombre = mb_strtolower($row->nombre ?? '');
                $cat    = $tabla_actual === 'subcategorias' ? mb_strtolower(optional($row->categoria)->nombre ?? '') : '';
                return str_contains($nombre, $s) || ($cat && str_contains($cat, $s));
              });
            }
          @endphp

          @forelse($filtered as $r)
            <tr>
              <td class="text-monospace">{{ $r->id }}</td>
              @if($tabla_actual === 'subcategorias')
                <td>{{ $r->categoria?->nombre ?? '—' }}</td>
              @endif
              <td>{{ $r->nombre }}</td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-warning me-1"
                        data-action="edit"
                        data-id="{{ $r->id }}"
                        data-nombre="{{ $r->nombre }}"
                        @if($tabla_actual === 'subcategorias')
                          data-categoria_id="{{ $r->categoria_id }}"
                        @endif
                        data-bs-toggle="modal" data-bs-target="#registroModal">
                  <i class="bi bi-pencil"></i>
                </button>

                <form class="d-inline" method="POST"
                      action="{{ route('admin.configsistem.destroy', ['tabla' => $tabla_actual, 'id' => $r->id]) }}"
                      onsubmit="return confirmDelete(this)">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="{{ $tabla_actual === 'subcategorias' ? 4 : 3 }}" class="text-center text-muted py-3">
                No hay registros.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="registroModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="formRegistro" method="POST"
          action="{{ route('admin.configsistem.store', ['tabla' => $tabla_actual]) }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Nuevo registro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        @if($tabla_actual === 'subcategorias')
          <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" id="categoriaId" class="form-select" required>
              <option value="">Seleccionar...</option>
              @foreach($categorias as $c)
                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
              @endforeach
            </select>
          </div>
        @endif

        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" id="nombre" class="form-control" required maxlength="100">
          <div class="form-text">Máximo 100 caracteres. Único por catálogo.</div>
        </div>

        <div class="alert alert-danger d-none" id="modalError"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-brand" type="submit" id="modalSubmit">
          <i class="bi bi-check2 me-1"></i>Guardar
        </button>
        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
  @vite('resources/js/admin_configsistem.js')
@endpush
