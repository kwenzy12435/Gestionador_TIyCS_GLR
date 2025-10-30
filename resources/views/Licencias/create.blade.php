@extends('layouts.app')
@section('title', 'Nueva Licencia')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-key me-2"></i>Registrar licencia</h1>
@endsection

@section('content')
@include('Partials.flash')

<div class="card p-4 shadow-sm">
  <form method="POST" action="{{ route('licencias.store') }}" novalidate>
    @csrf
    <div class="row g-3">

      <div class="col-md-6">
        <label class="form-label">Cuenta</label>
        <input type="text" name="cuenta" class="form-control" required maxlength="150">
      </div>

      <div class="col-md-6">
        <label class="form-label">Plataforma</label>
        <select name="plataforma_id" class="form-select" required>
          <option value="">Seleccionar...</option>
          @foreach($plataformas as $p)
            <option value="{{ $p->id }}">{{ $p->nombre }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Asignado a (opcional)</label>
        <select name="colaborador_id" class="form-select">
          <option value="">— Sin asignar —</option>
          @foreach($colaboradores as $c)
            <option value="{{ $c->id }}">{{ $c->nombres }} {{ $c->apellidos }} — {{ $c->email }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Expiración (opcional)</label>
        <input type="date" name="expiracion" class="form-control" min="{{ now()->toDateString() }}">
      </div>

      <div class="col-12">
        <label class="form-label">Contraseña</label>
        <div class="input-group">
          <input type="password" name="contrasena" class="form-control" required minlength="8" autocomplete="new-password">
          <button class="btn btn-outline-secondary" type="button" id="genPwdBtn"><i class="bi bi-magic"></i></button>
        </div>
        <div class="form-text">Mínimo 8 caracteres. Se almacena cifrada.</div>
      </div>

    </div>
    <div class="text-end mt-4">
      <button type="submit" class="btn btn-brand"><i class="bi bi-check2 me-1"></i>Guardar</button>
      <a href="{{ route('licencias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection

@push('scripts')
@vite('resources/js/licencias.js')
@endpush
