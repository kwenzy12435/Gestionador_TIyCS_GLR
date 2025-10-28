@extends('layouts.login')

@section('title', 'Iniciar Sesión - Sistema de Gestión TI')

@section('content')
<div class="auth-bg">
  {{-- Video con imagen de respaldo --}}
  <video autoplay muted loop playsinline poster="{{ asset('Front/img1.jpg') }}">
    <source src="{{ asset('Front/VIDEO 1.mp4') }}" type="video/mp4">
  </video>
  <img class="bg-img" src="{{ asset('Front/img1.jpg') }}" alt="Fondo">
</div>

<div class="container auth-container">
  <div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-11 col-md-8 col-lg-6 col-xl-5">

      {{-- Logo --}}
      <img class="auth-logo" src="{{ asset('Front/IMAGEN1.png') }}" alt="Grupo López-Rosa">

      <div class="auth-card">
        <div class="auth-header">Sistema De Gestion TI</div>

        <div class="p-4 p-md-5">
          {{-- Errores --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- Formulario --}}
          <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Usuario --}}
            <div class="mb-4">
              <label for="email" class="form-label fw-bold">Usuario</label>
              <div class="input-group input-pill">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text"
                       id="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       required autofocus
                       placeholder="Usuario">
              </div>
              @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- Contraseña --}}
            <div class="mb-2">
              <label for="password" class="form-label fw-bold">Contraseña</label>
              <div class="input-group input-pill">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required
                       placeholder="Contraseña">
                <span class="input-group-text password-toggle" id="togglePassword" title="Mostrar/Ocultar">
                  <i class="fas fa-eye-slash"></i>
                </span>
              </div>
              @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- Enlace recuperar contraseña --}}
            <div class="mb-3">
              @if (Route::has('password.request'))
                <a class="forgot-link" href="{{ route('password.request') }}">olvide mi contraseña</a>
              @endif
            </div>

            {{-- Recordar sesión --}}
            <div class="mb-4 form-check">
              <input type="checkbox" class="form-check-input" id="remember" name="remember">
              <label class="form-check-label fw-bold" for="remember">Recordar sesion</label>
            </div>

            {{-- Botón --}}
            <button type="submit" class="btn btn-salmon w-100">iniciar sesion</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Mostrar/Ocultar contraseña
  (function(){
    const input = document.getElementById('password');
    const btn = document.getElementById('togglePassword');
    if(btn && input){
      btn.addEventListener('click', function(){
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        this.querySelector('i')?.classList.toggle('fa-eye');
        this.querySelector('i')?.classList.toggle('fa-eye-slash');
      });
    }
  })();
</script>
@endpush
