@extends('layouts.app')
@section('title', 'Editar Licencia')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar licencia</h1>
@endsection

@section('content')
<div class="card p-4 shadow-sm">
  <form method="POST" action="{{ route('licencias.update', $licencia->id) }}">
    @csrf @method('PUT')
    <div class="row g-3">

      <div class="col-md-6">
        <label class="form-label">Cuenta</label>
        <input type="text" name="cuenta" class="form-control" value="{{ $licencia->cuenta }}" required maxlength="150">
      </div>

      <div class="col-md-6">
        <label class="form-label">Plataforma</label>
        <select name="plataforma_id" class="form-select" required>
          @foreach($plataformas as $p)
            <option value="{{ $p->id }}" @selected($licencia->plataforma_id==$p->id)>{{ $p->nombre }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Asignado a</label>
        <select name="colaborador_id" class="form-select">
          <option value="">— Sin asignar —</option>
          @foreach($colaboradores as $c)
            <option value="{{ $c->id }}" @selected($licencia->colaborador_id==$c->id)>{{ $c->nombres }} {{ $c->apellidos }} — {{ $c->email }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Expiración</label>
        <input type="date" name="expiracion" class="form-control" value="{{ $licencia->expiracion }}">
      </div>

      <div class="col-12">
        <label class="form-label">Nueva contraseña (opcional)</label>
        <div class="input-group">
          <input type="password" name="contrasena" class="form-control" minlength="8" autocomplete="new-password">
          <button class="btn btn-outline-secondary" type="button" id="genPwdBtn"><i class="bi bi-magic"></i></button>
        </div>
        <div class="form-text">Déjala vacía para mantener la actual.</div>
      </div>

    </div>
    <div class="text-end mt-4">
      <button class="btn btn-brand"><i class="bi bi-save2 me-1"></i>Actualizar</button>
      <a href="{{ route('licencias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection

@push('scripts')
@vite('resources/js/licencias.js')
@endpush
