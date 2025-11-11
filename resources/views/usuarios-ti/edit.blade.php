@extends('layouts.app')
@section('title', 'Editar Usuario TI')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Usuario TI</h1>
@endsection

@section('content')
<div class="card p-4 shadow-sm">
  @php($key = $usuario->getRouteKey())
  <form method="POST" action="{{ route('usuarios-ti.update', ['usuarioTi' => $key]) }}">
    @csrf
    @method('PUT')
    <div class="row g-3">
      <div class="col-md-6">
        <label for="usuario" class="form-label fw-semibold">Usuario <span class="text-danger">*</span></label>
        <input type="text" name="usuario" id="usuario" class="form-control @error('usuario') is-invalid @enderror" value="{{ old('usuario', $usuario->usuario) }}" required>
        @error('usuario')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="rol" class="form-label fw-semibold">Rol <span class="text-danger">*</span></label>
        <select name="rol" id="rol" class="form-select @error('rol') is-invalid @enderror" required>
          <option value="">Seleccionar rol...</option>
          @foreach($roles as $rol)
            <option value="{{ $rol }}" {{ old('rol', $usuario->rol) == $rol ? 'selected' : '' }}>{{ $rol }}</option>
          @endforeach
        </select>
        @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="nombres" class="form-label fw-semibold">Nombres <span class="text-danger">*</span></label>
        <input type="text" name="nombres" id="nombres" class="form-control @error('nombres') is-invalid @enderror" value="{{ old('nombres', $usuario->nombres) }}" required>
        @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="apellidos" class="form-label fw-semibold">Apellidos</label>
        <input type="text" name="apellidos" id="apellidos" class="form-control @error('apellidos') is-invalid @enderror" value="{{ old('apellidos', $usuario->apellidos) }}">
        @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="puesto" class="form-label fw-semibold">Puesto</label>
        <input type="text" name="puesto" id="puesto" class="form-control @error('puesto') is-invalid @enderror" value="{{ old('puesto', $usuario->puesto) }}">
        @error('puesto')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="telefono" class="form-label fw-semibold">Teléfono</label>
        <input type="text" name="telefono" id="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $usuario->telefono) }}">
        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="contrasena" class="form-label fw-semibold">Nueva contraseña</label>
        <input type="password" name="contrasena" id="contrasena" class="form-control @error('contrasena') is-invalid @enderror" minlength="8">
        <div class="form-text">Dejar en blanco para mantener la contraseña actual. Mínimo 8 caracteres.</div>
        @error('contrasena')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6">
        <label for="contrasena_confirmation" class="form-label fw-semibold">Confirmar contraseña</label>
        <input type="password" name="contrasena_confirmation" id="contrasena_confirmation" class="form-control">
      </div>
    </div>

    <div class="text-end mt-4">
      <button type="submit" class="btn btn-brand"><i class="bi bi-save2 me-1"></i>Actualizar Usuario</button>
      <a href="{{ route('usuarios-ti.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Cancelar</a>
    </div>
  </form>
</div>
@endsection
