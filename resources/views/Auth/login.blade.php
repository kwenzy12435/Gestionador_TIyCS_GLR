@extends('layouts.login')

@section('title', 'Iniciar Sesión - Sistema de Gestión TI')

@section('content')
<div class="auth-bg">

  <video autoplay muted loop playsinline poster="{{ asset('Front/img1.jpg') }}">
    <source src="{{ asset('Front/VIDEO 1.mp4') }}" type="video/mp4">
  </video>
  <img class="bg-img" src="{{ asset('Front/img1.jpg') }}" alt="Fondo">
</div>

<div class="container auth-container">
  <div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-11 col-md-8 col-lg-6 col-xl-5">

 
      <img class="auth-logo" src="{{ asset('Front/IMAGEN1.png') }}" alt="Grupo López-Rosa">

      <div class="auth-card">
        <div class="auth-header">Sistema De Gestion TI</div>

        <div class="p-4 p-md-5">
          {{-- Mostrar errores generales --}}
          @if($errors->any())
            
          @endif

          <form method="POST" action="{{ route('login') }}">
            @csrf

     
            <div class="mb-3">
              <label for="usuario" class="form-label fw-bold">Usuario</label>
              <div class="input-group input-pill no-eye">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" id="usuario" name="usuario" class="form-control {{ $errors->has('usuario') ? 'is-invalid' : '' }}" placeholder="Usuario" value="{{ old('usuario') }}" autofocus>
              </div>
              {{-- Mostrar error específico del usuario con traducción --}}
              @error('usuario')
                <div class="invalid-feedback d-block error-message">
                  <i class="fas fa-exclamation-triangle"></i> 
                  @php
                    echo App\Helpers\ValidationTranslator::translate($message);
                  @endphp
                </div>
              @enderror
            </div>

        
            <div class="mb-3">
              <label for="contrasena" class="form-label fw-bold">Contraseña</label>
              <div class="input-group input-pill has-eye">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" id="contrasena" name="contrasena" class="form-control {{ $errors->has('contrasena') ? 'is-invalid' : '' }}" placeholder="Contraseña">
                <button type="button" id="togglePassword" class="btn-eye" aria-label="Mostrar u ocultar">
                  <i class="fas fa-eye-slash"></i>
                </button>
              </div>
             
              @error('contrasena')
                <div class="invalid-feedback d-block error-message">
                  <i class="fas fa-exclamation-triangle"></i> 
                  @php
                    echo App\Helpers\ValidationTranslator::translate($message);
                  @endphp
                </div>
              @enderror
            </div>

      
            <div class="mb-4 form-check">
              <input type="checkbox" class="form-check-input" id="remember" name="remember">
              <label class="form-check-label fw-bold" for="remember">Recordar sesión</label>
            </div>


            <button type="submit" class="btn btn-salmon w-100">iniciar sesión</button>
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
  document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('contrasena');
    const toggleButton = document.getElementById('togglePassword');
    
    if (toggleButton && passwordInput) {
      toggleButton.addEventListener('click', function() {
        const isPasswordVisible = passwordInput.type === 'text';
        
        // Cambiar tipo de input
        passwordInput.type = isPasswordVisible ? 'password' : 'text';
        
        // Cambiar icono
        const icon = this.querySelector('i');
        if (icon) {
          icon.classList.toggle('fa-eye', !isPasswordVisible);
          icon.classList.toggle('fa-eye-slash', isPasswordVisible);
        }
      });
    }

    // Auto-ocultar mensajes de error después de 5 segundos
    setTimeout(function() {
      const errorMessages = document.querySelectorAll('.alert-validation, .error-message');
      errorMessages.forEach(function(message) {
        message.style.opacity = '0';
        message.style.transition = 'opacity 0.5s ease';
        setTimeout(function() {
          message.remove();
        }, 500);
      });
    }, 5000);
  });
</script>
@endpush