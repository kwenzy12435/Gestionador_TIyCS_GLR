@extends('layouts.app')
@section('title', 'Licencias')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-key me-2"></i>Licencias</h1>
@endsection

@section('header-actions')
  <a href="{{ route('licencias.create') }}" class="btn btn-brand">
    <i class="bi bi-plus-lg me-1"></i>Nueva licencia
  </a>
  <a href="{{ route('licencias.por_expiar', request()->only('search')) }}" class="btn btn-outline-warning">
    <i class="bi bi-exclamation-triangle me-1"></i>Por expirar (30 días)
  </a>
@endsection

@section('content')
@include('Partials.flash')

<div class="card p-3 shadow-sm licencias-table">
  <form method="GET" class="mb-3">
    <div class="input-group">
      <input type="text" name="search" class="form-control"
             placeholder="Buscar cuenta, colaborador, plataforma, email…"
             value="{{ $search ?? '' }}">
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
            $exp = $l->expiracion ? \Carbon\Carbon::parse($l->expiracion) : null;
            $days = $exp ? now()->diffInDays($exp, false) : null;
            $badge = !$exp ? 'bg-secondary' : ($days < 0 ? 'bg-danger' : ($days <= 7 ? 'bg-warning text-dark' : 'bg-success'));
            $label = !$exp ? '—' : $exp->format('d/m/Y') . " (" . ($days < 0 ? $days : "+$days") . " días)";
          @endphp
          <tr>
            <td class="text-nowrap">{{ $l->cuenta }}</td>
            <td>{{ $l->plataforma?->nombre ?? '—' }}</td>
            <td>{{ $l->colaborador?->nombres }} {{ $l->colaborador?->apellidos }}</td>
            <td><span class="badge {{ $badge }}">{{ $label }}</span></td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-dark me-1"
                      data-action="reveal"
                      data-id="{{ $l->id }}"
                      title="Revelar contraseña">
                <i class="bi bi-eye-slash"></i>
              </button>
              <a href="{{ route('licencias.show', $l->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
              <a href="{{ route('licencias.edit', $l->id) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('licencias.destroy', $l->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(this)">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted py-3">No hay licencias registradas.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Modal Revelar Contraseña --}}
<div class="modal fade" id="revealModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" id="revealForm">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-shield-lock me-2"></i>Confirmar identidad</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="licencia_id" id="licenciaId">
        <div class="mb-3">
          <label class="form-label">Tu contraseña de usuario</label>
          <input type="password" name="password" class="form-control" required minlength="8" autocomplete="current-password">
          <div class="form-text">Se valida contra tu cuenta antes de revelar la contraseña.</div>
        </div>
        <div class="mb-0 d-none" id="revealResult">
          <label class="form-label">Contraseña de la licencia</label>
          <div class="input-group">
            <input type="text" class="form-control" id="revealedPassword" readonly>
            <button class="btn btn-outline-secondary" type="button" id="copyBtn">
              <i class="bi bi-clipboard"></i>
            </button>
          </div>
          <div class="form-text mt-1 text-danger"><i class="bi bi-exclamation-triangle me-1"></i>No compartas esta contraseña por canales inseguros.</div>
        </div>
        <div class="alert alert-danger d-none mt-3" id="revealError"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-brand" type="submit" id="revealSubmit">
          <i class="bi bi-eye me-1"></i>Revelar
        </button>
        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/licencias.js')
@endpush
