@extends('layouts.app')
@section('title', 'Dashboard')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold">👋 Hola Mundo</h1>
@endsection

@section('header-actions')
  <a href="#" class="btn btn-brand"><i class="bi bi-plus-lg me-1"></i> Acción</a>
@endsection

@section('content')
<div class="row g-3">

  {{-- Card de presentación --}}
  <div class="col-12">
    <div class="card p-4 text-center shadow-sm">
      <h4 class="fw-bold mb-2 text-brand">Sistema de Gestión TI</h4>
      <p class="text-muted mb-0">
        Este es tu dashboard de prueba con diseño responsive y modo claro/oscuro.<br>
        Aquí se mostrarán las estadísticas y módulos del sistema.
      </p>
    </div>
  </div>

  {{-- Tabla de ejemplo --}}
  <div class="col-12">
    <div class="card p-4 shadow-sm">
      <h5 class="fw-bold mb-3"><i class="bi bi-laptop me-2"></i>Dispositivos Registrados</h5>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Usuario</th>
              <th>Estado</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($dispositivos as $d)
              <tr>
                <td>{{ $d['id'] }}</td>
                <td>{{ $d['nombre'] }}</td>
                <td>{{ $d['usuario'] }}</td>
                <td>
                  @if($d['estado'] === 'Activo')
                    <span class="badge bg-success">Activo</span>
                  @elseif($d['estado'] === 'En revisión')
                    <span class="badge bg-warning text-dark">En revisión</span>
                  @else
                    <span class="badge bg-secondary">Inactivo</span>
                  @endif
                </td>
                <td class="text-end">
                  <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                  <a href="#" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                  <a href="#" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
