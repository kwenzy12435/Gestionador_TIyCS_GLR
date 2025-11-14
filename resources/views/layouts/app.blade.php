@php
  /** @var \App\Models\UsuarioTI|null $authUser */
  $appTitle = trim($__env->yieldContent('title')) ?: 'Gestionador TI';
  $authUser = auth()->user();
  $isAdmin  = $authUser && ($authUser->rol ?? null) === 'ADMIN';
@endphp
<!DOCTYPE html>
<html lang="es">
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
  <aside class="offcanvas offcanvas-start app-sidebar" tabindex="-1" id="appSidebar" aria-label="Menú lateral">
    <div class="offcanvas-header px-4">
      <a class="d-flex align-items-center gap-2 text-decoration-none" href="{{ route('dashboard') }}">
        <img src="{{ asset('front/logo.png') }}" alt="Logo" class="brand-logo">
        <span class="offcanvas-title mb-0 fw-bold">Gestionador TI</span>
      </a>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>

    <div class="offcanvas-body px-3">
      <nav class="nav flex-column app-menu">
        <div class="mt-2 small text-uppercase fw-semibold text-muted ps-3">Principal</div>

        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           href="{{ route('dashboard') }}" aria-current="{{ request()->routeIs('dashboard') ? 'page' : '' }}">
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

        <a class="nav-link {{ request()->routeIs('colaboradores.*') ? 'active' : '' }}"
           href="{{ route('colaboradores.index') }}">
          <i class="bi bi-people me-2"></i>Colaboradores
        </a>

        <a class="nav-link {{ request()->routeIs('licencias.*') ? 'active' : '' }}"
           href="{{ route('licencias.index') }}">
          <i class="bi bi-key me-2"></i>Licencias
        </a>

        <div class="mt-4 small text-uppercase fw-semibold text-muted ps-3">Operaciones</div>

        {{-- Reporte de Actividades --}}
        <a class="nav-link {{ request()->routeIs('reporte_actividades.*') ? 'active' : '' }}"
           href="{{ route('reporte_actividades.index') }}">
          <i class="bi bi-clipboard2-check me-2"></i>Reporte de Actividades
        </a>

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

        @if($isAdmin)
          <div class="mt-4 small text-uppercase fw-semibold text-muted ps-3">Administración</div>

          <a class="nav-link {{ request()->routeIs('usuarios-ti.*') ? 'active' : '' }}"
             href="{{ route('usuarios-ti.index') }}">
            <i class="bi bi-person-gear me-2"></i>Usuarios TI
          </a>

          <a class="nav-link {{ request()->routeIs('admin.bajas.*') ? 'active' : '' }}"
             href="{{ route('admin.bajas.index') }}">
            <i class="bi bi-box-arrow-down me-2"></i>Bajas de Dispositivos
          </a>

          <a class="nav-link {{ request()->routeIs('admin.configsistem.*') ? 'active' : '' }}"
             href="{{ route('admin.configsistem.index') }}">
            <i class="bi bi-gear me-2"></i>Configuración del Sistema
          </a>
        @endif
      </nav>
    </div>
  </aside>

  {{-- Topbar --}}
  <header class="app-topbar navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
      <button class="btn btn-icon me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar" aria-controls="appSidebar" aria-label="Abrir menú">
        <i class="bi bi-list fs-3"></i>
      </button>

      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
        <img src="{{ asset('front/logo.png') }}" alt="Logo" class="brand-logo">
        <span class="brand-title d-none d-sm-inline fw-bold">Gestionador TI</span>
      </a>

      {{-- Buscador global opcional --}}
      <form class="d-none d-md-flex ms-3 flex-grow-1" role="search" method="GET" action="{{ route('dashboard') }}" aria-label="Búsqueda rápida">
        <div class="input-group input-group-sm w-50">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input type="search" class="form-control" name="q" placeholder="Buscar en módulos…" value="{{ request('q') }}">
        </div>
      </form>

      <div class="ms-auto d-flex align-items-center gap-3">
        @hasSection('breadcrumb')
          <nav aria-label="breadcrumb" class="d-none d-lg-block">
            <ol class="breadcrumb mb-0">@yield('breadcrumb')</ol>
          </nav>
        @endif

        <div class="dropdown">
          <button class="btn btn-user d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menú de usuario">
            <i class="bi bi-person-circle me-2 fs-4"></i>
            <span class="d-none d-sm-inline">
              @auth
                {{ $authUser->usuario ?? $authUser->name ?? 'Usuario' }}
              @else
                Invitado
              @endauth
            </span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow border-0">
            <li class="px-3 py-2 small text-muted">
              @auth
                <div class="fw-semibold">{{ $authUser->nombres ?? $authUser->name ?? 'Usuario' }}</div>
                <div>{{ $authUser->rol ?? '—' }}</div>
              @else
                No autenticado
              @endauth
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item" href="{{ route('profile.edit') }}">
                <i class="bi bi-person me-2"></i>Mi Perfil
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('colaboradores.index') }}">
                <i class="bi bi-people me-2"></i>Colaboradores
              </a>
            </li>
            @if($isAdmin)
              <li>
                <a class="dropdown-item" href="{{ route('admin.configsistem.index') }}">
                  <i class="bi bi-gear me-2"></i>Configuración
                </a>
              </li>
            @endif
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
  

  {{-- Contenido --}}
  <main class="app-content container-fluid">
    @include('partials.flash')
    {{-- Si usas el bloque de errores globales: --}}
    {{-- @include('partials.validation-errors') --}}

    @hasSection('page-header')
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>@yield('page-header')</div>
        <div class="d-flex gap-2">@yield('header-actions')</div>
      </div>
    @endif

    @yield('content')
  </main>

  <footer class="app-footer text-center small py-3 mt-4 border-top">
    <span>&copy; {{ now()->year }} Grupo López-Rosa — Gestionador TI</span>
  </footer>

  {{-- Loader global (oculto por defecto) --}}
  <div id="globalLoader" class="app-preloader is-hide" aria-hidden="true">
    <div class="app-preloader__spinner" role="status" aria-label="Cargando"></div>
    <div class="mt-3 small text-muted">Cargando…</div>
  </div>

 {{-- ===================== --}}
{{-- Confirm Modal + Loader --}}
{{-- ===================== --}}

{{-- Modal de confirmación global --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmTitle">Confirmar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p id="confirmMessage" class="mb-0">¿Estás seguro?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" id="confirmCancel" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmOk">Sí, continuar</button>
      </div>
    </div>
  </div>
</div>

{{-- Loader overlay (capa de carga) --}}
<div id="appLoader" class="app-loader d-none" aria-hidden="true">
  <div class="spinner-border" role="status" aria-hidden="true"></div>
  <span class="visually-hidden">Cargando…</span>
</div>

  @stack('scripts')
</body>
</html>
