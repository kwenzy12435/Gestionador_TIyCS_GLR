@extends('layouts.app')

@section('title', 'Editar Dispositivo - Sistema de Gestión TI')

@section('content')


<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('usuarios-ti.index') }}">
                            <i class="fas fa-users me-2"></i>Usuarios TI
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('colaboradores.index') }}">
                            <i class="fas fa-user-friends me-2"></i>Colaboradores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('inventario-dispositivos.index') }}">
                            <i class="fas fa-laptop me-2"></i>Inventario Dispositivos
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Editar Dispositivo</h1>
                <div>
                    <a href="{{ route('inventario-dispositivos.show', $dispositivo->id) }}" class="btn btn-info me-2">
                        <i class="fas fa-eye me-2"></i>Ver
                    </a>
                    <a href="{{ route('inventario-dispositivos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('inventario-dispositivos.update', $dispositivo->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                    <option value="">Seleccionar estado</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado }}" {{ old('estado', $dispositivo->estado) == $estado ? 'selected' : '' }}>
                                            {{ ucfirst($estado) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tipo_id" class="form-label">Tipo de Dispositivo *</label>
                                <select class="form-select @error('tipo_id') is-invalid @enderror" id="tipo_id" name="tipo_id" required>
                                    <option value="">Seleccionar tipo</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->id }}" {{ old('tipo_id', $dispositivo->tipo_id) == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="marca_id" class="form-label">Marca *</label>
                                <select class="form-select @error('marca_id') is-invalid @enderror" id="marca_id" name="marca_id" required>
                                    <option value="">Seleccionar marca</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ old('marca_id', $dispositivo->marca_id) == $marca->id ? 'selected' : '' }}>
                                            {{ $marca->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('marca_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="modelo" class="form-label">Modelo *</label>
                                <input type="text" class="form-control @error('modelo') is-invalid @enderror" 
                                       id="modelo" name="modelo" value="{{ old('modelo', $dispositivo->modelo) }}" required>
                                @error('modelo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="numero_serie" class="form-label">Número de Serie *</label>
                                <input type="text" class="form-control @error('numero_serie') is-invalid @enderror" 
                                       id="numero_serie" name="numero_serie" value="{{ old('numero_serie', $dispositivo->numero_serie) }}" required>
                                @error('numero_serie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="serie" class="form-label">Serie</label>
                                <input type="text" class="form-control @error('serie') is-invalid @enderror" 
                                       id="serie" name="serie" value="{{ old('serie', $dispositivo->serie) }}">
                                @error('serie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="mac" class="form-label">Dirección MAC</label>
                                <input type="text" class="form-control @error('mac') is-invalid @enderror" 
                                       id="mac" name="mac" value="{{ old('mac', $dispositivo->mac) }}" placeholder="00:00:00:00:00:00">
                                @error('mac')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="procesador" class="form-label">Procesador</label>
                                <input type="text" class="form-control @error('procesador') is-invalid @enderror" 
                                       id="procesador" name="procesador" value="{{ old('procesador', $dispositivo->procesador) }}">
                                @error('procesador')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="memoria_ram" class="form-label">Memoria RAM</label>
                                <input type="text" class="form-control @error('memoria_ram') is-invalid @enderror" 
                                       id="memoria_ram" name="memoria_ram" value="{{ old('memoria_ram', $dispositivo->memoria_ram) }}" placeholder="Ej: 16GB">
                                @error('memoria_ram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ssd" class="form-label">SSD</label>
                                <input type="text" class="form-control @error('ssd') is-invalid @enderror" 
                                       id="ssd" name="ssd" value="{{ old('ssd', $dispositivo->ssd) }}" placeholder="Ej: 512GB">
                                @error('ssd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="hdd" class="form-label">HDD</label>
                                <input type="text" class="form-control @error('hdd') is-invalid @enderror" 
                                       id="hdd" name="hdd" value="{{ old('hdd', $dispositivo->hdd) }}" placeholder="Ej: 1TB">
                                @error('hdd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                       id="color" name="color" value="{{ old('color', $dispositivo->color) }}">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="costo" class="form-label">Costo</label>
                                <input type="number" step="0.01" class="form-control @error('costo') is-invalid @enderror" 
                                       id="costo" name="costo" value="{{ old('costo', $dispositivo->costo) }}" placeholder="0.00">
                                @error('costo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fecha_compra" class="form-label">Fecha de Compra</label>
                                <input type="date" class="form-control @error('fecha_compra') is-invalid @enderror" 
                                       id="fecha_compra" name="fecha_compra" value="{{ old('fecha_compra', $dispositivo->fecha_compra ? \Carbon\Carbon::parse($dispositivo->fecha_compra)->format('Y-m-d') : '') }}">
                                @error('fecha_compra')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="garantia_hasta" class="form-label">Garantía hasta</label>
                                <input type="date" class="form-control @error('garantia_hasta') is-invalid @enderror" 
                                       id="garantia_hasta" name="garantia_hasta" value="{{ old('garantia_hasta', $dispositivo->garantia_hasta ? \Carbon\Carbon::parse($dispositivo->garantia_hasta)->format('Y-m-d') : '') }}">
                                @error('garantia_hasta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="colaborador_id" class="form-label">Asignar a Colaborador</label>
                                <select class="form-select @error('colaborador_id') is-invalid @enderror" id="colaborador_id" name="colaborador_id">
                                    <option value="">Seleccionar colaborador</option>
                                    @foreach($colaboradores as $colaborador)
                                        <option value="{{ $colaborador->id }}" {{ old('colaborador_id', $dispositivo->colaborador_id) == $colaborador->id ? 'selected' : '' }}>
                                            {{ $colaborador->nombre }} {{ $colaborador->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('colaborador_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Dispositivo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection