@extends('layouts.app')
@section('title', 'Nueva Licencia')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-key me-2"></i>Registrar Nueva Licencia</h1>
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
  <form method="POST" action="{{ route('licencias.store') }}" novalidate id="licenciaForm">
    @csrf

    {{-- Información de la Cuenta --}}
    <div class="row g-3">
      <div class="col-12">
        <h5 class="fw-bold text-brand mb-3"><i class="bi bi-person-badge me-2"></i>Información de la Cuenta</h5>
      </div>

      <div class="col-md-6">
        <label for="cuenta" class="form-label fw-semibold">Cuenta *</label>
        <input type="text" name="cuenta" id="cuenta"
               class="form-control @error('cuenta') is-invalid @enderror"
               value="{{ old('cuenta') }}" required maxlength="150"
               placeholder="Nombre de usuario o email de la cuenta">
        @error('cuenta') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label for="plataforma_id" class="form-label fw-semibold">Plataforma *</label>
        <select name="plataforma_id" id="plataforma_id"
                class="form-select @error('plataforma_id') is-invalid @enderror" required>
          <option value="">Seleccionar plataforma...</option>
          @foreach($plataformas as $plataforma)
            <option value="{{ $plataforma->id }}" {{ old('plataforma_id') == $plataforma->id ? 'selected' : '' }}>
              {{ $plataforma->nombre }}
            </option>
          @endforeach
        </select>
        @error('plataforma_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Asignación y Expiración --}}
      <div class="col-12 mt-4">
        <h5 class="fw-bold text-brand mb-3"><i class="bi bi-person-gear me-2"></i>Asignación y Vigencia</h5>
      </div>

      <div class="col-md-6">
        <label for="colaborador_id" class="form-label fw-semibold">Asignado a</label>
        <select name="colaborador_id" id="colaborador_id"
                class="form-select @error('colaborador_id') is-invalid @enderror">
          <option value="">— Sin asignar —</option>
          @foreach($colaboradores as $colaborador)
            <option value="{{ $colaborador->id }}" {{ old('colaborador_id') == $colaborador->id ? 'selected' : '' }}>
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
               value="{{ old('expiracion') }}" min="{{ now()->format('Y-m-d') }}">
        <div class="form-text">Opcional. Si se deja vacío, la licencia no expira.</div>
        @error('expiracion') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Seguridad --}}
      <div class="col-12 mt-4">
        <h5 class="fw-bold text-brand mb-3"><i class="bi bi-shield-lock me-2"></i>Seguridad</h5>
      </div>

      <div class="col-12">
        <label for="contrasena" class="form-label fw-semibold">Contraseña *</label>
        <div class="input-group">
          <input type="password" name="contrasena" id="contrasena"
                 class="form-control @error('contrasena') is-invalid @enderror"
                 required minlength="8" maxlength="255" autocomplete="new-password"
                 placeholder="Contraseña de la licencia">
          <button type="button" class="btn btn-outline-secondary" id="togglePassword"><i class="bi bi-eye"></i></button>
          <button type="button" class="btn btn-outline-primary" id="generatePassword"><i class="bi bi-dice-5"></i></button>
        </div>
        <div class="form-text">
          <i class="bi bi-info-circle me-1"></i> Mínimo 8 caracteres. La contraseña se almacenará cifrada.
        </div>
        @error('contrasena') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-12">
        <div class="password-strength mt-2">
          <div class="progress" style="height:6px;"><div class="progress-bar" id="passwordStrength" role="progressbar" style="width:0%"></div></div>
          <small class="text-muted" id="passwordFeedback">Ingresa una contraseña</small>
        </div>
      </div>
    </div>

    <div class="text-end mt-4 pt-3 border-top">
      <button type="submit" class="btn btn-brand"><i class="bi bi-check2 me-1"></i>Guardar Licencia</button>
      <a href="{{ route('licencias.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg me-1"></i>Cancelar</a>
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
  const bar = document.getElementById('passwordStrength');
  const fb  = document.getElementById('passwordFeedback');

  toggle.addEventListener('click', () => {
    const type = input.type === 'password' ? 'text' : 'password';
    input.type = type; toggle.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
  });

  genBtn.addEventListener('click', () => {
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    let pwd = ""; for (let i=0;i<12;i++) pwd += charset[Math.floor(Math.random()*charset.length)];
    input.value = pwd; input.type='text'; toggle.innerHTML='<i class="bi bi-eye-slash"></i>'; check(pwd);
  });

  function check(p) {
    let s=0; if(p.length>=8)s+=25; if(/[a-z]/.test(p))s+=25; if(/[A-Z]/.test(p))s+=25; if(/[0-9]/.test(p))s+=15; if(/[^a-zA-Z0-9]/.test(p))s+=10;
    bar.style.width=s+'%'; bar.className='progress-bar '+(s<50?'bg-danger':(s<75?'bg-warning':'bg-success'));
    fb.textContent = s<50?'Contraseña débil':(s<75?'Contraseña media':'Contraseña fuerte');
  }
  input.addEventListener('input', e=>check(e.target.value));

  const exp = document.getElementById('expiracion'); if (exp) exp.min = new Date().toISOString().split('T')[0];
});
</script>
@endpush
