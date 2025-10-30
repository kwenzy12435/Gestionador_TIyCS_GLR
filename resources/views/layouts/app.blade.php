@php($appTitle = trim($__env->yieldContent('title')) ?: 'Sistema de Gestión TI')
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $appTitle }}</title>
  <link rel="icon" type="image/png" href="{{ asset('front/logo.png') }}"/>
  @vite(['resources/scss/app.scss','resources/js/app.js'])
  @stack('styles')
</head>
<body class="app-shell">
  {{-- Sidebar (offcanvas en móvil) --}}
  <div class="offcanvas offcanvas-start app-sidebar" tabindex="-1" id="appSidebar">
    <div class="offcanvas-header px-4">
      <div class="d-flex align-items-center gap-2">
        <img src="{{ asset('front/logo.png') }}" alt="Logo" class="brand-logo">
        <h6 class="offcanvas-title mb-0">Sistema de Gestión TI</h6>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body px-3">
      <nav class="nav flex-column app-menu">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
          <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('bajas.*') ? 'active' : '' }}" href="{{ route('bajas.index') }}">
          <i class="bi bi-box-arrow-down me-2"></i>Bajas de disp
        </a>
        <a class="nav-link {{ request()->routeIs('inventario-dispositivos.*') ? 'active' : '' }}" href="{{ route('inventario-dispositivos.index') }}">
          <i class="bi bi-laptop me-2"></i>Inventario de disp
        </a>
        <a class="nav-link {{ request()->routeIs('licencias.*') ? 'active' : '' }}" href="{{ route('licencias.index') }}">
          <i class="bi bi-key me-2"></i>Licencias
        </a>
        <a class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}" href="{{ route('tickets.index') }}">
          <i class="bi bi-ticket-detailed me-2"></i>Tickets
        </a>
        <a class="nav-link {{ request()->routeIs('bitacoras-contpaq.*') ? 'active' : '' }}" href="{{ route('bitacoras-contpaq.index') }}">
          <i class="bi bi-journal-text me-2"></i>Bitácoras CONTPAQi
        </a>
        <a class="nav-link {{ request()->routeIs('inventario-articulos.*') ? 'active' : '' }}" href="{{ route('inventario-articulos.index') }}">
          <i class="bi bi-box-seam me-2"></i>Inventario artículos
        </a>
        <a class="nav-link {{ request()->routeIs('monitoreo-red.*') ? 'active' : '' }}" href="{{ route('monitoreo-red.index') }}">
          <i class="bi bi-diagram-3 me-2"></i>Monitoreo de red
        </a>

        <div class="mt-4 small text-uppercase fw-semibold text-muted ps-3">Administración</div>
        <a class="nav-link {{ request()->routeIs('usuarios-ti.*') ? 'active' : '' }}" href="{{ route('usuarios-ti.index') }}">
          <i class="bi bi-people me-2"></i>Usuarios TI
        </a>
        <a class="nav-link {{ request()->routeIs('config-sistema.*') ? 'active' : '' }}" href="{{ route('config-sistema.index') }}">
          <i class="bi bi-gear me-2"></i>Configuración
        </a>
      </nav>
    </div>
  </div>

  {{-- Topbar --}}
  <header class="app-topbar navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
      <button class="btn btn-icon me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar">
        <i class="bi bi-list fs-3"></i>
      </button>

      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
        <img src="{{ asset('front/logo.png') }}" alt="Logo" class="brand-logo">
        <span class="brand-title d-none d-sm-inline">Sistema de Gestión TI</span>
      </a>

      <div class="ms-auto d-flex align-items-center gap-2">
        @hasSection('breadcrumb')
          <nav aria-label="breadcrumb" class="d-none d-lg-block">
            <ol class="breadcrumb mb-0">@yield('breadcrumb')</ol>
          </nav>
        @endif

        <div class="form-check form-switch ms-2">
          <input class="form-check-input" type="checkbox" id="themeSwitch">
          <label class="form-check-label small" for="themeSwitch"><span class="d-none d-md-inline">Dark</span></label>
        </div>

        <div class="dropdown">
          <button class="btn btn-user d-flex align-items-center" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle me-2 fs-4"></i>
            <span class="d-none d-sm-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li><a class="dropdown-item" href="{{ route('perfil.show') }}"><i class="bi bi-person me-2"></i>Perfil</a></li>
            <li><a class="dropdown-item" href="{{ route('config-sistema.index') }}"><i class="bi bi-gear me-2"></i>Configuración</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  {{-- Contenido --}}
  <main class="app-content container-fluid">
    @include('Partials.flash')

    @hasSection('page-header')
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>@yield('page-header')</div>
        <div class="d-flex gap-2">@yield('header-actions')</div>
      </div>
    @endif

    @yield('content')
  </main>

  <footer class="app-footer text-center small py-3">
    <span>&copy; {{ now()->year }} Grupo López-Rosa — Sistema de Gestión TI</span>
  </footer>

  <script>
    (function () {
      const KEY = 'app-theme';
      const root = document.documentElement;
      const switcher = document.getElementById('themeSwitch');

      const saved = localStorage.getItem(KEY);
      if (saved) {
        root.setAttribute('data-theme', saved);
        if (switcher) switcher.checked = (saved === 'dark');
      } else {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        root.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
        if (switcher) switcher.checked = prefersDark;
      }

      if (switcher) {
        switcher.addEventListener('change', () => {
          const next = switcher.checked ? 'dark' : 'light';
          root.setAttribute('data-theme', next);
          localStorage.setItem(KEY, next);
        });
      }
    })();
  </script>

  @stack('scripts')
</body>
</html>
