    @extends('layouts.app')
@section('title', 'Editar Licencia')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Licencia</h1>
@endsection

@section('content')
@include('partials.flash')

@if($errors->any())
  <div class="alert alert-danger">
    <div class="fw-semibold mb-1">Por favor corrige los siguientes campos:</div>
    <ul class="mb-0">
      @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
    </ul>
  </div>
@endif

<div class="card p-4 shadow-sm">
  <form method="POST" action="{{ route('licencias.update', $licencia) }}" id="licenciaForm" novalidate>
    @csrf
    @method('PUT')

    <div class="row g-3">
      <div class="col-12">
        <h5 class="fw-bold text-brand mb-3"><i class="bi bi-person-badge me-2"></i>Información de la Cuenta</h5>
      </div>

      <div class="col-md-6">
        <label for="cuenta" class="form-label fw-semibold">Cuenta *</label>
        <input type="text" name="cuenta" id="cuenta"
               class="form-control @error('cuenta') is-invalid @enderror"
               value="{{ old('cuenta', $licencia->cuenta) }}" required maxlength="150">
        @error('cuenta') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label for="plataforma_id" class="form-label fw-semibold">Plataforma *</label>
        <select name="plataforma_id" id="plataforma_id"
                class="form-select @error('plataforma_id') is-invalid @enderror" required>
          @foreach($plataformas as $plataforma)
            <option value="{{ $plataforma->id }}" {{ old('plataforma_id', $licencia->plataforma_id) == $plataforma->id ? 'selected' : '' }}>
              {{ $plataforma->nombre }}
            </option>
          @endforeach
        </select>
        @error('plataforma_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-12 mt-4">
        <h5 class="fw-bold text-brand mb-3"><i class="bi bi-person-gear me-2"></i>Asignación y Vigencia</h5>
      </div>

      <div class="col-md-6">
        <label for="colaborador_id" class="form-label fw-semibold">Asignado a</label>
        <select name="colaborador_id" id="colaborador_id"
                class="form-select @error('colaborador_id') is-invalid @enderror">
          <option value="">— Sin asignar —</option>
          @foreach($colaboradores as $colaborador)
            <option value="{{ $colaborador->id }}" {{ old('colaborador_id', $licencia->colaborador_id) == $colaborador->id ? 'selected' : '' }}>
              {{ $colaborador->nombre }} {{ $colaborador->apellidos }} — {{ $colaborador->email }}
            </option>
          @endforeach
        </select>
        @error('colaborador_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label for="expiracion" class="form-label fw-semibold">Fecha de Expiración</label>
        <input type="date" name="expiracion" id="expiracion"
               class="form-control @error('expiracion') is-invalid @enderror"
               value="{{ old('expiracion', $licencia->expiracion ? $licencia->expiracion->format('Y-m-d') : '') }}"
               min="{{ now()->format('Y-m-d') }}">
        <div class="form-text">Opcional. Si se deja vacío, la licencia no expira.</div>
        @error('expiracion') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-12 mt-4">
        <h5 class="fw-bold text-brand mb-3"><i class="bi bi-shield-lock me-2"></i>Seguridad</h5>
      </div>

      <div class="col-12">
        <label for="contrasena" class="form-label fw-semibold">Nueva Contraseña</label>
        <div class="input-group">
          <input type="password" name="contrasena" id="contrasena"
                 class="form-control @error('contrasena') is-invalid @enderror"
                 minlength="8" maxlength="255" autocomplete="new-password"
                 placeholder="Dejar vacío para mantener la actual">
          <button type="button" class="btn btn-outline-secondary" id="togglePassword"><i class="bi bi-eye"></i></button>
          <button type="button" class="btn btn-outline-primary" id="generatePassword"><i class="bi bi-dice-5"></i></button>
        </div>
        <div class="form-text"><i class="bi bi-info-circle me-1"></i> Déjalo vacío si no quieres cambiarla.</div>
        @error('contrasena') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="text-end mt-4 pt-3 border-top">
      <button type="submit" class="btn btn-warning"><i class="bi bi-save2 me-1"></i>Actualizar Licencia</button>
      <a href="{{ route('licencias.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('contrasena');
  const toggle = document.getElementById('togglePassword');
  const genBtn = document.getElementById('generatePassword');
  toggle.addEventListener('click', () => {
    const type = input.type === 'password' ? 'text' : 'password';
    input.type = type; toggle.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
  });
  genBtn.addEventListener('click', () => {
    const cs = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    let p=""; for(let i=0;i<12;i++) p+=cs[Math.floor(Math.random()*cs.length)];
    input.value=p; input.type='text'; toggle.innerHTML='<i class="bi bi-eye-slash"></i>';
  });
  const exp = document.getElementById('expiracion'); if (exp) exp.min = new Date().toISOString().split('T')[0];
});
</script>
@endpush
