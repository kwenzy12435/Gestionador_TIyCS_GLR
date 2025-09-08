<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestión TI</title>
    <link rel="stylesheet" href="/css/dashboard.css?v={{ time() }}">
</head>
<body class="dashboard-body">
    <div class="dashboard-header">
        <h1 class="dashboard-welcome">¡Hola, {{ $user->nombre }}!</h1>
        <div class="dashboard-user-info">
            <span>Rol: <strong>{{ $user->rol }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" class="dashboard-logout-form">
                @csrf
                <button type="submit" class="dashboard-logout-btn">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <div class="dashboard-container">
        <h2 class="dashboard-title">Panel de Control - Sistema de Gestión TI</h2>
        
        <div class="dashboard-buttons-grid">
            <!-- Tus 7 botones aquí -->
            <a href="#" class="dashboard-btn dashboard-btn-1">
                <div class="dashboard-btn-icon">💻</div>
                <div class="dashboard-btn-text">Inventario</div>
                <div class="dashboard-btn-desc">Gestión de dispositivos</div>
            </a>

          <a href="/users" class="dashboard-btn dashboard-btn-2">
                <div class="dashboard-btn-icon">👥</div>
                <div class="dashboard-btn-text">Colaboradores</div>
                <div class="dashboard-btn-desc">Gestión de usuarios</div>
            </a>

            <a href="#" class="dashboard-btn dashboard-btn-3">
                <div class="dashboard-btn-icon">📊</div>
                <div class="dashboard-btn-text">Reportes</div>
                <div class="dashboard-btn-desc">Reportes de actividades</div>
            </a>

            <a href="#" class="dashboard-btn dashboard-btn-4">
                <div class="dashboard-btn-icon">🔧</div>
                <div class="dashboard-btn-text">Soporte</div>
                <div class="dashboard-btn-desc">Tickets de soporte</div>
            </a>

            <a href="#" class="dashboard-btn dashboard-btn-5">
                <div class="dashboard-btn-icon">📦</div>
                <div class="dashboard-btn-text">Artículos</div>
                <div class="dashboard-btn-desc">Gestión de artículos</div>
            </a>

            <a href="#" class="dashboard-btn dashboard-btn-6">
                <div class="dashboard-btn-icon">🔒</div>
                <div class="dashboard-btn-text">Licencias</div>
                <div class="dashboard-btn-desc">Gestión de licencias</div>
            </a>

            <a href="#" class="dashboard-btn dashboard-btn-7">
                <div class="dashboard-btn-icon">💾</div>
                <div class="dashboard-btn-text">Backups</div>
                <div class="dashboard-btn-desc">Respaldos CONPAQ</div>
            </a>
        </div>
    </div>
</body>
</html>