<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestión TI</title>
    <link rel="stylesheet" href="/css/login.css?v={{ time() }}">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-header">
            <h1>Sistema de Gestión TI</h1>
            <p>Ingrese sus credenciales</p>
        </div>
        
        @if(session('error'))
            <div class="login-alert login-alert-error">{{ session('error') }}</div>
        @endif
        
        @if(session('success'))
            <div class="login-alert login-alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="login-form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" value="{{ old('usuario') }}" required autofocus>
            </div>
            
            <div class="login-form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            
            <button type="submit" class="login-btn">Iniciar Sesión</button>
        </form>

        <div class="login-footer">
            <p>Usuario: admin / Contraseña: password</p>
            <p>¿Problemas para acceder? Contacte al administrador</p>
        </div>
    </div>
</body>
</html>