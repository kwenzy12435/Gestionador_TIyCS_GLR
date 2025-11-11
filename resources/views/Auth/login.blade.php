@extends('layouts.login')

@section('title', 'Iniciar Sesión - Sistema de Gestión TI')

@section('content')
  <div class="auth-box">
    {{-- Logo IMAGEN1 --}}
    <div class="text-center mb-3">
      <img src="{{ asset('Front/IMAGEN1.png') }}" alt="Grupo López-Rosa" class="auth-logo">
    </div>

    {{-- Card de login --}}
    <div class="auth-card">
      <h1 class="auth-header">Sistema de Gestión TI</h1>

      {{-- Mensaje de sesión cerrada u otros status --}}
      @if (session('status'))
        <div class="alert alert-validation alert-auto mb-3" role="alert">
          <i class="fas fa-info-circle me-2"></i>{{ session('status') }}
        </div>
      @endif

      {{-- Mensaje de éxito --}}
      @if (session('success'))
        <div class="alert alert-success alert-auto mb-3" role="alert">
          <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
      @endif

      {{-- Errores globales --}}
      @if ($errors->any())
        <div class="alert alert-validation alert-auto mb-3" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i>
          Se detectaron errores en el formulario, verifica los campos marcados.
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

       {{-- Usuario --}}
<div class="mb-3">
    <label for="usuario" class="form-label fw-semibold">Usuario</label>
    <div class="input-group input-pill">
        <span class="input-group-text">
            <i class="fas fa-user"></i>
        </span>
        <input
            type="text"
            id="usuario"
            name="usuario"
            class="form-control @error('usuario') is-invalid @enderror"
            placeholder="Ingresa tu usuario"
            value="{{ old('usuario') }}"
            autocomplete="username"
            autofocus
            required
        >
    </div>
    @error('usuario')
        <div class="invalid-feedback d-block error-message">
            <i class="fas fa-exclamation-circle me-1"></i>
            {{ $message }}
        </div>
    @enderror
</div>

{{-- Contraseña --}}
<div class="mb-3">
    <label for="contrasena" class="form-label fw-semibold">Contraseña</label>
    <div class="input-group input-pill position-relative">
        <span class="input-group-text">
            <i class="fas fa-lock"></i>
        </span>
        <input
            type="password"
            id="contrasena"
            name="contrasena"
            class="form-control @error('contrasena') is-invalid @enderror"
            placeholder="Ingresa tu contraseña"
            autocomplete="current-password"
            required
        >
        <button type="button" class="btn-eye" data-target="#contrasena" aria-label="Mostrar u ocultar contraseña">
            <i class="fas fa-eye-slash"></i>
        </button>
    </div>
    @error('contrasena')
        <div class="invalid-feedback d-block error-message">
            <i class="fas fa-exclamation-circle me-1"></i>
            {{ $message }}
        </div>
    @enderror
</div>

        {{-- Botón --}}
        <button type="submit" class="btn btn-salmon w-100">
          Iniciar sesión
        </button>
      </form>
    </div>
  </div>
@endsection