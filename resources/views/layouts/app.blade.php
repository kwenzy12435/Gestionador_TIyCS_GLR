<!doctype html>
<html lang="es" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Sistema de Gestión TI')</title>

  @vite(['resources/scss/app.scss','resources/scss/ui.scss','resources/scss/modules.scss','resources/js/app.js'])

  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  @stack('styles')
</head>
<body class="ui-body" data-module="@yield('module','general')">

  {{-- TOPBAR --}}
  <header class="ui-topbar">
    <button class="ui-burger" id="btnSidebar"><i class="fa-solid fa-bars"></i></button>

    <a class="ui-brand" href="{{ route('dashboard') }}">
      <span class="ui-brand-logo"><i class="fa-solid fa-network-wired"></i></span>
      <span class="ui-brand-text">Sistema de Gestión TI</span>
      <span class="ui-brand-emblem">✣</span>
    </a>

    <div class="ui-breadcrumb">
      @yield('breadcrumb', 'ruta')
    </div>

    <div class="ui-actions">
      {{-- Dark / Light --}}
      <label class="ui-switch" title="Tema">
        <input type="checkbox" id="themeToggle">
        <span class="slider"></span>
        <span class="lbl">dark</span>
      </label>

      {{-- Usuario --}}
      <div class="ui-user">
        <button class="ui-user-btn" id="userBtn">
          <i class="fa-regular fa-circle-user"></i>
          <span class="d-none d-sm-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
        </button>
        <div class="ui-user-menu" id="userMenu">
          <a href="{{ route('profile.edit') }}"><i class="fa-solid fa-user-gear"></i> Perfil</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</button>
          </form>
          <a href="{{ route('admin.configsistem.index') }}"><i class="fa-solid fa-wrench"></i> Configuración</a>
        </div>
      </div>
    </div>
  </header>

  {{-- CONTENEDOR PRINCIPAL --}}
  <div class="ui-wrap">

    {{-- SIDEBAR --}}
    <aside class="ui-sidebar" id="sidebar">
      <nav>
        <div class="ui-side-title">Menu</div>
        <a href="{{ route('dashboard') }}" class="ui-side-link"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="{{ route('inventario-dispositivos.index') }}" class="ui-side-link"><i class="fa-solid fa-laptop"></i> Inventario de disp</a>
        <a href="{{ route('licencias.index') }}" class="ui-side-link"><i class="fa-solid fa-id-badge"></i> Licencias</a>
        <a href="{{ route('reporte_actividades.index') }}" class="ui-side-link"><i class="fa-solid fa-ticket"></i> Tickets</a>
        <a href="{{ route('bitacora_respaldo.index') }}" class="ui-side-link"><i class="fa-solid fa-database"></i> Bitácoras CONTPAQi</a>
        <a href="{{ route('articulos.index') }}" class="ui-side-link"><i class="fa-solid fa-boxes-stacked"></i> Inventario artículos</a>
        <a href="{{ route('monitoreo-red.index') }}" class="ui-side-link"><i class="fa-solid fa-network-wired"></i> Monitoreo de red</a>

        <div class="ui-side-title mt-3">Admin</div>
        <a href="{{ route('usuarios-ti.index') }}" class="ui-side-link"><i class="fa-solid fa-users-gear"></i> Usuarios TI</a>
        <a href="{{ route('admin.configsistem.index') }}" class="ui-side-link"><i class="fa-solid fa-sliders"></i> Configuración</a>
      </nav>
    </aside>

    {{-- CONTENIDO --}}
    <main class="ui-content">
      @includeWhen(session('status') || $errors->any(), 'partials.flash')
      @yield('content')
    </main>
  </div>

  {{-- OVERLAY DE CARGA --}}
  <div class="ui-loader" id="loader" hidden>
    <div class="ui-loader-card">
      <div class="ui-loader-logo">✣</div>
      <div class="ui-loader-ring"></div>
      <div class="ui-loader-text">Cargando...</div>
    </div>
  </div>

  @stack('scripts')
</body>
</html>
