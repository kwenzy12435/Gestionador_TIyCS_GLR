@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold">
    <i class="bi bi-speedometer2 me-2"></i>Panel principal
  </h1>
@endsection

@section('header-actions')
  <button type="button" class="btn btn-outline-secondary btn-sm" onclick="location.reload()">
    <i class="bi bi-arrow-clockwise me-1"></i> Actualizar
  </button>
@endsection

@section('content')
<div class="dashboard py-2">

  {{-- Tarjetas de resumen (demo) --}}
  <div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small text-uppercase">Dispositivos</div>
            <div class="h4 mb-0">42</div>
            <div class="small text-muted">Inventario TI</div>
          </div>
          <i class="bi bi-laptop fs-2 text-primary opacity-75"></i>
        </div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small text-uppercase">Licencias</div>
            <div class="h4 mb-0">15</div>
            <div class="small text-muted">2 por expirar</div>
          </div>
          <i class="bi bi-key fs-2 text-warning opacity-75"></i>
        </div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small text-uppercase">Colaboradores</div>
            <div class="h4 mb-0">30</div>
            <div class="small text-muted">Registro HR</div>
          </div>
          <i class="bi bi-people fs-2 text-success opacity-75"></i>
        </div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small text-uppercase">Alertas</div>
            <div class="h4 mb-0">0</div>
            <div class="small text-muted">Todo en orden</div>
          </div>
          <i class="bi bi-bell fs-2 text-danger opacity-75"></i>
        </div>
      </div>
    </div>
  </div>

  {{-- Accesos rápidos --}}
  <div class="row g-3 mb-3">
    <div class="col-12 col-lg-8">
      <div class="card shadow-sm h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span class="fw-semibold"><i class="bi bi-lightning-charge me-1"></i>Accesos rápidos</span>
        </div>
        <div class="card-body">
          <div class="row g-2">
            <div class="col-6 col-md-4">
              <a href="{{ route('inventario-dispositivos.index') }}" class="btn w-100 btn-outline-primary">
                <i class="bi bi-laptop me-1"></i> Inventario disp.
              </a>
            </div>
            <div class="col-6 col-md-4">
              <a href="{{ route('licencias.index') }}" class="btn w-100 btn-outline-primary">
                <i class="bi bi-key me-1"></i> Licencias
              </a>
            </div>
            <div class="col-6 col-md-4">
              <a href="{{ route('monitoreo_red.index') }}" class="btn w-100 btn-outline-primary">
                <i class="bi bi-diagram-3 me-1"></i> Monitoreo red
              </a>
            </div>
            <div class="col-6 col-md-4">
              <a href="{{ route('bitacora-respaldo.index') }}" class="btn w-100 btn-outline-primary">
                <i class="bi bi-journal-text me-1"></i> Bitácora respaldo
              </a>
            </div>
            <div class="col-6 col-md-4">
              <a href="{{ route('articulos.index') }}" class="btn w-100 btn-outline-primary">
                <i class="bi bi-box-seam me-1"></i> Artículos
              </a>
            </div>
            <div class="col-6 col-md-4">
              <a href="{{ route('usuarios-ti.index') }}" class="btn w-100 btn-outline-primary">
                <i class="bi bi-people me-1"></i> Usuarios TI
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Tarjeta de bienvenida --}}
    <div class="col-12 col-lg-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="fw-bold mb-2">Bienvenido 👋</h5>
          <p class="text-muted mb-3">
            Este panel es un resumen rápido de los módulos principales del Sistema de Gestión TI.
          </p>
          <ul class="list-unstyled small mb-3">
            <li class="mb-1"><i class="bi bi-check2-circle text-success me-1"></i> Revisa el inventario de dispositivos.</li>
            <li class="mb-1"><i class="bi bi-check2-circle text-success me-1"></i> Valida licencias y fechas de expiración.</li>
            <li class="mb-1"><i class="bi bi-check2-circle text-success me-1"></i> Monitorea el estado de la red y respaldos.</li>
          </ul>
          <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-person me-1"></i> Ir a mi perfil
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Tabla sencilla de “actividad reciente” (demo) --}}
  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span class="fw-semibold"><i class="bi bi-clock-history me-1"></i>Actividad reciente (demo)</span>
      <span class="small text-muted">Ejemplo estático, luego se conecta a BD</span>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Módulo</th>
              <th>Acción</th>
              <th>Usuario</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Inventario dispositivos</td>
              <td>Alta de equipo “Laptop Dell”</td>
              <td class="text-muted">rtzab</td>
              <td class="text-muted">2025-11-08 10:30</td>
            </tr>
            <tr>
              <td>Licencias</td>
              <td>Actualización de licencia Office 365</td>
              <td class="text-muted">mlopez</td>
              <td class="text-muted">2025-11-08 09:15</td>
            </tr>
            <tr>
              <td>Bitácora respaldos</td>
              <td>Respaldo NAS Synology completado</td>
              <td class="text-muted">rtzab</td>
              <td class="text-muted">2025-11-07 18:42</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
