@extends('layouts.app')

@section('title', 'Usuarios TI - Sistema de Gestión TI')

@section('content')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Usuarios TI</h1>
                @if(auth()->user()->rol === 'ADMIN')
                    <a href="{{ route('usuarios-ti.create') }}" class="btn btn-primary">Nuevo Usuario</a>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Puesto</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->usuario }}</td>
                                <td>{{ $usuario->nombres }}</td>
                                <td>{{ $usuario->apellidos }}</td>
                                <td>{{ $usuario->puesto ?? 'No especificado' }}</td>
                                <td>
                                    <span class="badge bg-{{ $usuario->rol == 'ADMIN' ? 'danger' : ($usuario->rol == 'AUXILIAR-TI' ? 'warning' : 'info') }}">
                                        {{ $usuario->rol }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('usuarios-ti.edit', $usuario) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(auth()->user()->rol === 'ADMIN' && $usuario->id !== auth()->id())
                                        <form action="{{ route('usuarios-ti.destroy', $usuario) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
