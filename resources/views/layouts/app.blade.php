<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Gestión TI')</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- App CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('styles')

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- NAVBAR - SOLO MOSTRAR SI EL USUARIO ESTÁ AUTENTICADO -->
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-server me-2"></i>Sistema Gestión TI
            </a>

            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user me-1"></i> {{ auth()->user()->nombres }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <!-- Opción para usuarios NO ADMIN -->
                        @if(auth()->user()->rol !== 'ADMIN')
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                               data-bs-target="#editarCredencialesModal">
                                <i class="fas fa-key me-2"></i>Cambiar Usuario/Contraseña
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @endif

                        <!-- Opción para Mi Perfil (visible para todos) -->
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-cog me-2"></i>Mi Perfil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- CONTENIDO DE LA VISTA -->
    <main class="container my-4">
        @yield('content')
    </main>

    <!-- Modal para editar credenciales (solo para NO ADMIN y usuarios autenticados) -->
    @auth
        @if(auth()->user()->rol !== 'ADMIN')
        <div class="modal fade" id="editarCredencialesModal" tabindex="-1"
             aria-labelledby="editarCredencialesModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editarCredencialesModalLabel">
                            <i class="fas fa-key me-2"></i>Cambiar Credenciales
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('usuarios-ti.actualizar-credenciales') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                            <div class="mb-3">
                                <label for="usuario" class="form-label">Nuevo Usuario *</label>
                                <input type="text" class="form-control @error('usuario') is-invalid @enderror"
                                       id="usuario" name="usuario"
                                       value="{{ old('usuario', auth()->user()->usuario) }}" required>
                                @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="contrasena_actual" class="form-label">Contraseña Actual *</label>
                                <input type="password" class="form-control @error('contrasena_actual') is-invalid @enderror"
                                       id="contrasena_actual" name="contrasena_actual" required>
                                @error('contrasena_actual')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nueva_contrasena" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control @error('nueva_contrasena') is-invalid @enderror"
                                       id="nueva_contrasena" name="nueva_contrasena">
                                <small class="form-text text-muted">Dejar vacío si no quieres cambiar la contraseña</small>
                                @error('nueva_contrasena')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nueva_contrasena_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" class="form-control"
                                       id="nueva_contrasena_confirmation" name="nueva_contrasena_confirmation">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endauth

    <!-- Scripts: jQuery (si lo necesitas) y Bootstrap Bundle 5.3 (incluye Popper) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>