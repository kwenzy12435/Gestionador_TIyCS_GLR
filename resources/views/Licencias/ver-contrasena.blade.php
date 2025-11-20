@extends('layouts.app')
@section('title', 'Ver Contraseña - ' . $licencia->cuenta)

@section('page-header')
<h1 class="h3 mb-0 fw-bold">
    <i class="bi bi-shield-lock me-2"></i>Ver Contraseña
</h1>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">{{ $licencia->cuenta }}</h5>
                <small class="text-light">{{ $licencia->plataforma->nombre ?? 'Sin plataforma' }}</small>
            </div>
            
            <div class="card-body p-4">
                @if(!isset($mostrarContrasena))
                    <!-- Formulario para ingresar contraseña -->
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock display-4 text-brand"></i>
                        <h4 class="mt-3">Verificación Requerida</h4>
                        <p class="text-muted">Por seguridad, ingresa tu contraseña para ver la contraseña de esta licencia.</p>
                    </div>

                    <form method="POST" action="{{ route('licencias.procesar-ver-contrasena', $licencia) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Tu Contraseña *</label>
                            <input type="password" name="password" id="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   required autocomplete="current-password"
                                   placeholder="Ingresa tu contraseña de usuario">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-brand">
                                <i class="bi bi-check2 me-1"></i>Verificar y Mostrar Contraseña
                            </button>
                            <a href="{{ route('licencias.show', $licencia) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Volver
                            </a>
                        </div>
                    </form>
                @else
                    <!-- Mostrar contraseña revelada -->
                    <div class="text-center mb-4">
                        <i class="bi bi-key display-4 text-success"></i>
                        <h4 class="mt-3">Contraseña Revelada</h4>
                        <p class="text-muted">Esta contraseña es confidencial. No la compartas.</p>
                    </div>

                    <div class="alert alert-success">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Contraseña:</strong>
                            <button type="button" class="btn btn-sm btn-outline-dark" onclick="copiarContrasena()">
                                <i class="bi bi-clipboard"></i> Copiar
                            </button>
                        </div>
                        <code class="fs-5 d-block mt-2 text-center" id="contrasenaTexto">{{ $contrasenaRevelada }}</code>
                    </div>

                    <div class="alert alert-warning">
                        <small>
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Por seguridad, esta pantalla se cerrará automáticamente después de 30 segundos.
                        </small>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('licencias.show', $licencia) }}" class="btn btn-success">
                            <i class="bi bi-check2 me-1"></i>Listo
                        </a>
                    </div>

                    <script>
                        function copiarContrasena() {
                            const contrasena = document.getElementById('contrasenaTexto').textContent;
                            navigator.clipboard.writeText(contrasena).then(() => {
                                // Opcional: mostrar mensaje de éxito
                                alert('Contraseña copiada al portapapeles');
                            });
                        }

                        // Cerrar automáticamente después de 30 segundos
                        setTimeout(() => {
                            window.location.href = "{{ route('licencias.show', $licencia) }}";
                        }, 30000);
                    </script>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection