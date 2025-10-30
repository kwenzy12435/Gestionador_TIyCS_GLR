@extends('layouts.app')
@section('title','Ver contraseña')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-shield-lock me-2"></i>Ver contraseña de licencia</h1>
@endsection

@section('content')
@include('Partials.flash')

<div class="card p-4 shadow-sm" x-data>
  <form method="POST" action="{{ route('licencias.procesar_ver_contrasena', $licencia->id) }}" novalidate>
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <p class="mb-1"><strong>Cuenta:</strong> {{ $licencia->cuenta }}</p>
        <p class="mb-1"><strong>Plataforma:</strong> {{ $licencia->plataforma?->nombre ?? '—' }}</p>
      </div>
      <div class="col-md-6">
        <label class="form-label">Confirma tu contraseña</label>
        <input type="password" name="password" class="form-control" required minlength="8" autocomplete="current-password">
      </div>
    </div>
    <div class="text-end mt-3">
      <button class="btn btn-brand" type="submit"><i class="bi bi-unlock me-1"></i>Revelar</button>
      <a href="{{ route('licencias.show', $licencia->id) }}" class="btn btn-outline-secondary">Volver</a>
    </div>
  </form>

  @isset($mostrarContrasena)
  <hr class="my-4">
  <div>
    <label class="form-label">Contraseña de la licencia</label>
    <div class="input-group">
      <input type="text" class="form-control" value="{{ $contrasenaRevelada }}" readonly id="pwdView">
      <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('pwdView').value)">
        <i class="bi bi-clipboard"></i>
      </button>
    </div>
    <div class="form-text mt-1 text-danger"><i class="bi bi-exclamation-triangle me-1"></i>No compartas esta contraseña por canales inseguros.</div>
  </div>
  @endisset
</div>
@endsection
