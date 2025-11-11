@extends('layouts.app')
@section('title', 'Nueva Licencia')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-key me-2"></i>Registrar Nueva Licencia</h1>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-4 shadow-sm">
    <form method="POST" action="{{ route('licencias.store') }}" novalidate id="licenciaForm">
        @csrf
        
        <div class="row g-3">
            <!-- Información de la Cuenta -->
            <div class="col-12">
                <h5 class="fw-bold text-brand mb-3"><i class="bi bi-person-badge me-2"></i>Información de la Cuenta</h5>
            </div>

            <div class="col-md-6">
                <label for="cuenta" class="form-label fw-semibold">Cuenta *</label>
                <input type="text" name="cuenta" id="cuenta" 
                       class="form-control @error('cuenta') is-invalid @enderror" 
                       value="{{ old('cuenta') }}" 
                       required maxlength="150" 
                       placeholder="Nombre de usuario o email de la cuenta">
                @error('cuenta')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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
                @error('plataforma_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Asignación y Expiración -->
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
                @error('colaborador_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="expiracion" class="form-label fw-semibold">Fecha de Expiración</label>
                <input type="date" name="expiracion" id="expiracion" 
                       class="form-control @error('expiracion') is-invalid @enderror" 
                       value="{{ old('expiracion') }}" 
                       min="{{ now()->format('Y-m-d') }}">
                <div class="form-text">Opcional. Si se deja vacío, la licencia no expira.</div>
                @error('expiracion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Contraseña -->
            <div class="col-12 mt-4">
                <h5 class="fw-bold text-brand mb-3"><i class="bi bi-shield-lock me-2"></i>Seguridad</h5>
            </div>

            <div class="col-12">
                <label for="contrasena" class="form-label fw-semibold">Contraseña *</label>
                <div class="input-group">
                    <input type="password" name="contrasena" id="contrasena" 
                           class="form-control @error('contrasena') is-invalid @enderror" 
                           required minlength="8" maxlength="255" 
                           autocomplete="new-password"
                           placeholder="Contraseña de la licencia">
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="generatePassword">
                        <i class="bi bi-dice-5"></i>
                    </button>
                </div>
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>
                    Mínimo 8 caracteres. La contraseña se almacenará cifrada en la base de datos.
                </div>
                @error('contrasena')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Indicador de fortaleza de contraseña -->
            <div class="col-12">
                <div class="password-strength mt-2">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%"></div>
                    </div>
                    <small class="text-muted" id="passwordFeedback">Ingresa una contraseña</small>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="text-end mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-brand">
                <i class="bi bi-check2 me-1"></i>Guardar Licencia
            </button>
            <a href="{{ route('licencias.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg me-1"></i>Cancelar
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contrasenaInput = document.getElementById('contrasena');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const generatePasswordBtn = document.getElementById('generatePassword');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordFeedback = document.getElementById('passwordFeedback');

    // Alternar visibilidad de contraseña
    togglePasswordBtn.addEventListener('click', function() {
        const type = contrasenaInput.getAttribute('type') === 'password' ? 'text' : 'password';
        contrasenaInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });

    // Generar contraseña segura
    generatePasswordBtn.addEventListener('click', function() {
        const length = 12;
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let password = "";
        
        for (let i = 0; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }
        
        contrasenaInput.value = password;
        contrasenaInput.setAttribute('type', 'text');
        togglePasswordBtn.innerHTML = '<i class="bi bi-eye-slash"></i>';
        checkPasswordStrength(password);
    });

    // Verificar fortaleza de contraseña
    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = "";

        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]/)) strength += 25;
        if (password.match(/[A-Z]/)) strength += 25;
        if (password.match(/[0-9]/)) strength += 15;
        if (password.match(/[^a-zA-Z0-9]/)) strength += 10;

        // Determinar color y mensaje
        if (strength < 50) {
            passwordStrength.className = "progress-bar bg-danger";
            feedback = "Contraseña débil";
        } else if (strength < 75) {
            passwordStrength.className = "progress-bar bg-warning";
            feedback = "Contraseña media";
        } else {
            passwordStrength.className = "progress-bar bg-success";
            feedback = "Contraseña fuerte";
        }

        passwordStrength.style.width = strength + "%";
        passwordFeedback.textContent = feedback;
    }

    // Escuchar cambios en la contraseña
    contrasenaInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
    });

    // Validación de fecha de expiración
    const expiracionInput = document.getElementById('expiracion');
    if (expiracionInput) {
        expiracionInput.min = new Date().toISOString().split('T')[0];
    }
});
</script>
@endpush