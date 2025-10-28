@extends('layouts.app')

@section('title', 'Ver Usuario TI - Sistema de Gestión TI')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link active" href="{{ route('usuarios-ti.index') }}">Usuarios TI</a>
            </nav>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Detalle Usuario</h1>
                <div>
                    <a href="{{ route('usuarios-ti.edit', $usuario) }}" class="btn btn-warning"><i class="fas fa-edit me-2"></i>Editar</a>
                    <a href="{{ route('usuarios-ti.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Volver</a>
                </div>
            </div>

            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ $usuario->nombres }} {{ $usuario->apellidos }}</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Usuario</dt>
                        <dd class="col-sm-9">{{ $usuario->usuario }}</dd>

                        <dt class="col-sm-3">Rol</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-{{ $usuario->rol == 'ADMIN' ? 'danger' : ($usuario->rol == 'AUXILIAR-TI' ? 'warning' : 'info') }}">{{ $usuario->rol }}</span>
                        </dd>

                        <dt class="col-sm-3">Puesto</dt>
                        <dd class="col-sm-9">{{ $usuario->puesto ?? 'No especificado' }}</dd>

                        <dt class="col-sm-3">Teléfono</dt>
                        <dd class="col-sm-9">{{ $usuario->telefono ?? 'No especificado' }}</dd>

                        <dt class="col-sm-3">Creado</dt>
                        <dd class="col-sm-9">{{ $usuario->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-3">Última actualización</dt>
                        <dd class="col-sm-9">{{ $usuario->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
