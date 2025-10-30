@extends('layouts.app')
@section('title', 'Licencias por expirar')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Licencias por expirar (30 días)</h1>
@endsection

@section('content')
<div class="card p-3 shadow-sm licencias-table">
  <form method="GET" class="mb-3">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Buscar…" value="{{ $search ?? '' }}">
      <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Cuenta</th>
          <th>Plataforma</th>
          <th>Colaborador</th>
          <th>Expiración</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($licencias as $l)
          @php
            $exp = \Carbon\Carbon::parse($l->expiracion);
            $days = now()->diffInDays($exp, false);
            $badge = $days < 0 ? 'bg-danger' : ($days <= 7 ? 'bg-warning text-dark' : 'bg-success');
          @endphp
          <tr>
            <td class="text-nowrap">{{ $l->cuenta }}</td>
            <td>{{ $l->plataforma?->nombre ?? '—' }}</td>
            <td>{{ $l->colaborador?->nombres }} {{ $l->colaborador?->apellidos }}</td>
            <td><span class="badge {{ $badge }}">{{ $exp->format('d/m/Y') }} ({{ $days < 0 ? $days : "+$days" }} días)</span></td>
            <td class="text-end">
              <a href="{{ route('licencias.show', $l->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
              <a href="{{ route('licencias.edit', $l->id) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted py-3">No hay licencias próximas a expirar.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
