@extends('layouts.app')
@section('title', 'Editar Usuario TI')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Usuario TI</h1>
@endsection

@section('content')
@include('Partials.flash')

<div class="card p-4 shadow-sm">
  <form method="POST" action="{{ route('usuarios-ti.update', $usuario) }}">
    @csrf
    @method('PUT')
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Usuario</label>
        <input type="text" name="usuario" class="form-control" value="{{ $usuario->usuario }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Rol</label>
        <select name="rol" class="form-select" required>
          @foreach (['ADMIN','AUXILIAR-TI','PERSONAL-TI'] as $rol)
            <option value="{{ $rol }}" @selected($usuario->rol==$rol)>{{ $rol }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nombres</label>
        <input type="text" name="nombres" class="form-control" value="{{ $usuario->nombres }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Apellidos</label>
        <input type="text" name="apellidos" class="form-control" value="{{ $usuario->apellidos }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Puesto</label>
        <input type="text" name="puesto" class="form-control" value="{{ $usuario->puesto }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="{{ $usuario->telefono }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Nueva contraseña (opcional)</label>
        <input type="password" name="contrasena" class="form-control" minlength="8">
      </div>
      <div class="col-md-6">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" name="contrasena_confirmation" class="form-control">
      </div>
    </div>
    <div class="text-end mt-4">
      <button class="btn btn-brand"><i class="bi bi-save2 me-1"></i>Actualizar</button>
      <a href="{{ route('usuarios-ti.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
