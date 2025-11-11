@extends('layouts.app')
@section('title', 'Detalles del Usuario TI')

@section('page-header')
  <h1 class="h3 mb-0 fw-bold"><i class="bi bi-person-vcard me-2"></i>Detalle del Usuario</h1>
@endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-white">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h5 class="mb-0">Información del Usuario TI</h5>
      </div>
      <div class="col-md-6 text-end">
        @php($key = $usuario->getRouteKey())
        <div class="btn-group">
          <a href="{{ route('usuarios-ti.edit', ['usuarioTi' => $key]) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil me-1"></i>Editar
          </a>
          @if($usuario->id !== auth()->id())
            <form action="{{ route('usuarios-ti.destroy', ['usuarioTi' => $key]) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                <i class="bi bi-trash me-1"></i>Eliminar
              </button>
            </form>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="card-body">
    <div class="row g-4">
      <div class="col-md-6">
        <div class="border-bottom pb-3 mb-3">
          <h6 class="fw-semibold text-primary mb-2"><i class="bi bi-person-badge me-2"></i>Información Personal</h6>
          <p><strong>Usuario:</strong> <code>{{ $usuario->usuario }}</code></p>
          <p><strong>Nombre completo:</strong> {{ $usuario->nombres }} {{ $usuario->apellidos }}</p>
          <p><strong>Puesto:</strong> {{ $usuario->puesto ?: '—' }}</p>
        </div>
        <div>
          <h6 class="fw-semibold text-primary mb-2"><i class="bi bi-telephone me-2"></i>Contacto</h6>
          <p><strong>Teléfono:</strong> {{ $usuario->telefono ?: '—' }}</p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="border-bottom pb-3 mb-3">
          <h6 class="fw-semibold text-primary mb-2"><i class="bi bi-shield-check me-2"></i>Información del Sistema</h6>
          <p>
            <strong>Rol:</strong>
            <span class="badge {{ $usuario->rol === 'ADMIN' ? 'bg-danger' : ($usuario->rol === 'AUXILIAR-TI' ? 'bg-info' : 'bg-secondary') }}">{{ $usuario->rol }}</span>
          </p>
          <p><strong>ID:</strong> <code>#{{ $usuario->id }}</code></p>
        </div>
        <div>
          <h6 class="fw-semibold text-primary mb-2"><i class="bi bi-clock me-2"></i>Auditoría</h6>
          <p><strong>Registro creado:</strong> {{ $usuario->created_at->format('d/m/Y H:i') }}</p>
          <p><strong>Última actualización:</strong> {{ $usuario->updated_at->format('d/m/Y H:i') }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="card-footer bg-white text-end">
    <a href="{{ route('usuarios-ti.index') }}" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i>Volver al Listado
    </a>
  </div>
</div>
@endsection
