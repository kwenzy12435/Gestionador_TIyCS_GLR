@extends('layouts.app')
@section('title', 'Nuevo Usuario TI')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-person-plus me-2"></i>Registrar Usuario TI</h1>
@endsection

@section('content')
@include('Partials.flash')

<div class="card p-4 shadow-sm">
  <form method="POST" action="{{ route('usuarios-ti.store') }}" novalidate>
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Usuario</label>
        <input type="text" name="usuario" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Rol</label>
        <select name="rol" class="form-select" required>
          <option value="">Seleccionar...</option>
          <option value="ADMIN">ADMIN</option>
          <option value="AUXILIAR-TI">AUXILIAR-TI</option>
          <option value="PERSONAL-TI">PERSONAL-TI</option>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Nombres</label>
        <input type="text" name="nombres" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Apellidos</label>
        <input type="text" name="apellidos" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label">Puesto</label>
        <input type="text" name="puesto" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label">Contraseña</label>
        <input type="password" name="contrasena" class="form-control" required minlength="8">
      </div>

      <div class="col-md-6">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" name="contrasena_confirmation" class="form-control" required>
      </div>
    </div>

    <div class="text-end mt-4">
      <button type="submit" class="btn btn-brand"><i class="bi bi-check2 me-1"></i>Guardar</button>
      <a href="{{ route('usuarios-ti.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
