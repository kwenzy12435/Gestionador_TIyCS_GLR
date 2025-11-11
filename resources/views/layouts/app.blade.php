@php
    $appTitle = trim($__env->yieldContent('title')) ?: 'Sistema de Gestión TI';
@endphp
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

    <div class="offcanvas offcanvas-start app-sidebar" tabindex="-1" id="appSidebar">
        <div class="offcanvas-header px-4">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('front/logo.png') }}" alt="Logo" class="brand-logo">
                <h6 class="offcanvas-title mb-0 fw-bold">Gestión TI</h6>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>

        <div class="offcanvas-body px-3">
            <nav class="nav flex-column app-menu">
                <div class="mt-2 small text-uppercase fw-semibold text-muted ps-3">Principal</div>
                
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>

                <div class="mt-4 small text-uppercase fw-semibold text-muted ps-3">Inventario & Activos</div>

                <a class="nav-link {{ request()->routeIs('inventario-dispositivos.*') ? 'active' : '' }}"
                    href="{{ route('inventario-dispositivos.index') }}">
                    <i class="bi bi-laptop me-2"></i>Inventario de Dispositivos
                </a>

                <a class="nav-link {{ request()->routeIs('articulos.*') ? 'active' : '' }}"
                    href="{{ route('articulos.index') }}">
                    <i class="bi bi-box-seam me-2"></i>Inventario de Artículos
                </a>

                <a class="nav-link {{ request()->routeIs('licencias.*') ? 'active' : '' }}"
                    href="{{ route('licencias.index') }}">
                    <i class="bi bi-key me-2"></i>Licencias
                </a>

                <div class="mt-4 small text-uppercase fw-semibold text-muted ps-3">Operaciones</div>

                <a class="nav-link {{ request()->routeIs('bitacora-respaldo.*') ? 'active' : '' }}"
                    href="{{ route('bitacora-respaldo.index') }}">
                    <i class="bi bi-journal-text me-2"></i>Bitácora de Respaldos
                </a>

                <a class="nav-link {{ request()->routeIs('monitoreo_red.*') ? 'active' : '' }}"
                    href="{{ route('monitoreo_red.index') }}">
                    <i class="bi bi-diagram-3 me-2"></i>Monitoreo de Red
                </a>

                @if (Route::has('tickets.index'))
                    <a class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}"
                        href="{{ route('tickets.index') }}">
                        <i class="bi bi-ticket-detailed me-2"></i>Tickets de Soporte
                    </a>
                @endif
                
                <div class="mt-4 small text-uppercase fw-semibold text-muted ps-3">Administración</div>

                <a class="nav-link {{ request()->routeIs('usuarios-ti.*') ? 'active' : '' }}"
                    href="{{ route('usuarios-ti.index') }}">
                    <i class="bi bi-people me-2"></i>Usuarios TI
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.bajas.*') ? 'active' : '' }}"
                    href="{{ route('admin.bajas.index') }}">
                    <i class="bi bi-box-arrow-down me-2"></i>Bajas de Dispositivos
                </a>

               <a class="nav-link {{ request()->routeIs('admin.configsistem.*') ? 'active' : '' }}"
   href="{{ route('admin.configsistem.index') }}">
    <i class="bi bi-gear me-2"></i>Configuración del Sistema
</a>
            </nav>
        </div>
    </div>

    <header class="app-topbar navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <button class="btn btn-icon me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar" aria-controls="appSidebar">
                <i class="bi bi-list fs-3"></i>
            </button>

            <a class="navbar-brand d-flex align-items-center gap-2"
                href="{{ route('dashboard') }}">
                <img src="{{ asset('front/logo.png') }}" alt="Logo" class="brand-logo">
                <span class="brand-title d-none d-sm-inline fw-bold">Sistema de Gestión TI</span>
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">
                
                @hasSection('breadcrumb')
                    <nav aria-label="breadcrumb" class="d-none d-lg-block">
                        <ol class="breadcrumb mb-0">@yield('breadcrumb')</ol>
                    </nav>
                @endif

                <div class="form-check form-switch ms-2">
                    <input class="form-check-input" type="checkbox" id="themeSwitch" role="switch">
                    <label class="form-check-label small text-muted" for="themeSwitch">
                        <span class="d-none d-md-inline">Tema Oscuro</span>
                    </label>
                </div>

                <div class="dropdown">
                    <button class="btn btn-user d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-2 fs-4"></i>
                        <span class="d-none d-sm-inline">
                            @auth
                                {{ auth()->user()->name }}
                            @else
                                Admin
                            @endauth
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person me-2"></i>Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.configsistem.index') }}">
    <i class="bi bi-gear me-2"></i>Configuración
</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <main class="app-content container-fluid">
        @include('Partials.flash')

        @hasSection('page-header')
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>@yield('page-header')</div> 
                <div class="d-flex gap-2">@yield('header-actions')</div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="app-footer text-center small py-3 mt-4 border-top">
        <span>&copy; {{ now()->year }} Grupo López-Rosa — **Sistema de Gestión TI**</span>
    </footer>

    <script>
        (function () {
            const KEY = 'app-theme';
            const root = document.documentElement;
            const switcher = document.getElementById('themeSwitch');

            const savedTheme = localStorage.getItem(KEY);
            if (savedTheme) {
                root.setAttribute('data-theme', savedTheme);
                if (switcher) switcher.checked = (savedTheme === 'dark');
            } else {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const initialTheme = prefersDark ? 'dark' : 'light';
                root.setAttribute('data-theme', initialTheme);
                if (switcher) switcher.checked = prefersDark;
            }

            if (switcher) {
                switcher.addEventListener('change', () => {
                    const nextTheme = switcher.checked ? 'dark' : 'light';
                    root.setAttribute('data-theme', nextTheme);
                    localStorage.setItem(KEY, nextTheme);
                });
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>