<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Sistema TI</title>
    <link rel="stylesheet" href="/css/dashboard.css">
    <style>
        .container { max-width: 600px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        input, select { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; }
        .btn-primary { background: #4a6cf7; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .error { color: #dc3545; font-size: 0.875rem; }
    </style>
</head>
<body class="dashboard-body">
    <div class="dashboard-header">
        <h1 class="dashboard-welcome">Editar Usuario: {{ $user->usuario }}</h1>
        <div class="dashboard-user-info">
            <a href="{{ route('users.index') }}" class="dashboard-logout-btn">← Volver</a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="usuario">Usuario *</label>
                    <input type="text" id="usuario" name="usuario" value="{{ old('usuario', $user->usuario) }}" required>
                    @error('usuario') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="nombres">Nombres *</label>
                    <input type="text" id="nombres" name="nombres" value="{{ old('nombres', $user->nombres) }}" required>
                    @error('nombres') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="apellidos">Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos', $user->apellidos) }}">
                </div>

                <div class="form-group">
                    <label for="contrasena">Nueva Contraseña (dejar vacío para no cambiar)</label>
                    <input type="password" id="contrasena" name="contrasena">
                    @error('contrasena') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="puesto">Puesto</label>
                    <input type="text" id="puesto" name="puesto" value="{{ old('puesto', $user->puesto) }}">
                </div>

                <div class="form-group">
                    <label for="rol">Rol *</label>
                    <select id="rol" name="rol" required>
                        <option value="">Seleccionar rol</option>
                        <option value="admin" {{ (old('rol', $user->rol) == 'admin') ? 'selected' : '' }}>Administrador</option>
                        <option value="tecnico" {{ (old('rol', $user->rol) == 'tecnico') ? 'selected' : '' }}>Técnico</option>
                        <option value="supervisor" {{ (old('rol', $user->rol) == 'supervisor') ? 'selected' : '' }}>Supervisor</option>
                    </select>
                    @error('rol') <div class="error">{{ $message }}</div> @enderror
                </div>


                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>