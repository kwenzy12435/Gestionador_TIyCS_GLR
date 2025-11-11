@extends('layouts.app')
@section('title', 'Ver Contraseña de Licencia')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-shield-lock me-2"></i>Ver Contraseña de Licencia</h1>
@endsection

@section('content')
{{-- ⚠ Evitar duplicado: NO mostrar errores globales aquí --}}
{{-- Muestra solo flashes de éxito/estado, no errores de validación --}}
@includeWhen(session('success') || session('status') || session('info') || session('warning'), 'Partials.flash')

<div class="card p-4 shadow-sm">
  <div class="row">
    <div class="col-md-6">
      {{-- Información de la Licencia --}}
      <div class="mb-4">
        <h5 class="fw-bold text-brand">Información de la Licencia</h5>
        <div class="border-start border-3 border-primary ps-3 mt-3">
          <p class="mb-2">
            <strong>Cuenta:</strong><br>
            <code class="fs-5">{{ $licencia->cuenta }}</code>
          </p>
          <p class="mb-2">
            <strong>Plataforma:</strong><br>
            <span class="badge bg-info text-dark">{{ $licencia->plataforma->nombre ?? '—' }}</span>
          </p>
          @if($licencia->colaborador)
          <p class="mb-0">
            <strong>Asignado a:</strong><br>
            {{ $licencia->colaborador->nombre }} {{ $licencia->colaborador->apellidos }}
          </p>
          @endif
        </div>
      </div>
    </div>

    <div class="col-md-6">
      @if(!isset($mostrarContrasena))
        {{-- Formulario de Verificación --}}
        <form method="POST" action="{{ route('licencias.procesar-ver-contrasena', $licencia) }}" novalidate>
          @csrf

          <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Por seguridad, debes verificar tu identidad para ver esta contraseña.
          </div>

          <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Tu Contraseña de Usuario *</label>
            <input
              type="password"
              name="password"
              id="password"
              class="form-control @error('password') is-invalid @enderror"
              required minlength="8"
              autocomplete="current-password"
              placeholder="Ingresa tu contraseña">
            @error('password')
              {{-- Importante: NO envolver en __() para evitar 'validation.' --}}
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Mínimo 8 caracteres.</div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-brand">
              <i class="bi bi-unlock me-1"></i>Verificar y Revelar
            </button>
            <a href="{{ route('licencias.show', $licencia) }}" class="btn btn-outline-secondary">
              <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
          </div>
        </form>
      @else
        {{-- Contraseña Revelada --}}
        <div class="alert alert-success">
          <i class="bi bi-check-circle me-2"></i>
          Identidad verificada correctamente
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Contraseña de la Licencia</label>
          <div class="input-group">
            <input type="text" class="form-control" id="revealedPassword" value="{{ $contrasenaRevelada }}" readonly>
            <button class="btn btn-outline-success" type="button" id="copyPasswordBtn" aria-label="Copiar contraseña">
              <i class="bi bi-clipboard"></i>
            </button>
          </div>
          <div class="form-text">
            <i class="bi bi-clock me-1"></i>
            Esta vista se cerrará automáticamente por seguridad.
          </div>
        </div>

        <div class="alert alert-warning">
          <i class="bi bi-exclamation-triangle me-2"></i>
          <small><strong>Advertencia de seguridad:</strong> Esta contraseña es confidencial. No la compartas por canales inseguros.</small>
        </div>

        <div class="text-end">
          <a href="{{ route('licencias.show', $licencia) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver al Detalle
          </a>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
@if(isset($mostrarContrasena))
<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('copyPasswordBtn');
  const input = document.getElementById('revealedPassword');

  btn.addEventListener('click', async () => {
    try {
      if (navigator.clipboard?.writeText) {
        await navigator.clipboard.writeText(input.value);
      } else {
        // Fallback muy básico
        input.select();
        document.execCommand('copy');
      }
      const original = btn.innerHTML;
      btn.innerHTML = '<i class="bi bi-check2"></i>';
      btn.classList.replace('btn-outline-success', 'btn-success');
      setTimeout(() => {
        btn.innerHTML = original;
        btn.classList.replace('btn-success', 'btn-outline-success');
      }, 2000);
    } catch (e) {
      console.error(e);
    }
  });
});
</script>
@endif
@endpush
