@extends('layouts.app')
@section('title', 'Usuarios TI')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-people me-2"></i>Usuarios TI</h1>
@endsection

@section('header-actions')
  <a href="{{ route('usuarios-ti.create') }}" class="btn btn-brand">
    <i class="bi bi-plus-lg me-1"></i>Nuevo usuario
  </a>
@endsection

@section('content')
@include('Partials.flash')

<div class="card p-3 shadow-sm usuarios-ti-table">
  <form method="GET" class="mb-3">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Buscar usuario..." value="{{ $search ?? '' }}">
      <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Usuario</th>
          <th>Nombre</th>
          <th>Puesto</th>
          <th>Teléfono</th>
          <th>Rol</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($usuarios as $u)
          <tr>
            <td>{{ $u->id }}</td>
            <td>{{ $u->usuario }}</td>
            <td>{{ $u->nombres }} {{ $u->apellidos }}</td>
            <td>{{ $u->puesto ?? '—' }}</td>
            <td>{{ $u->telefono ?? '—' }}</td>
            <td>
              <span class="badge {{ $u->rol === 'ADMIN' ? 'bg-danger' : ($u->rol === 'AUXILIAR-TI' ? 'bg-info' : 'bg-secondary') }}">
                {{ $u->rol }}
              </span>
            </td>
            <td class="text-end">
              <a href="{{ route('usuarios-ti.show', $u) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
              <a href="{{ route('usuarios-ti.edit', $u) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('usuarios-ti.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(this)">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-3">No se encontraron usuarios.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/usuarios-ti.js')
@endpush
