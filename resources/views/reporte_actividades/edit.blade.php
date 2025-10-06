@extends('layouts.app')

@section('title', 'Editar Reporte - Sistema de Gestión TI')

@section('content')


<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <!-- Mismo sidebar que index -->
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Editar Reporte de Actividad</h1>
                <div>
                    <a href="{{ route('reporte_actividades.show', $reporte->id) }}" class="btn btn-info me-2">
                        <i class="fas fa-eye me-2"></i>Ver
                    </a>
                    <a href="{{ route('reporte_actividades.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('reporte_actividades.update', $reporte->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha" class="form-label">Fecha *</label>
                                <input type="date" class="form-control @error('fecha') is-invalid @enderror" 
                                       id="fecha" name="fecha" value="{{ old('fecha', $reporte->fecha->format('Y-m-d')) }}" required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="colaborador_id" class="form-label">Colaborador</label>
                                <select class="form-select @error('colaborador_id') is-invalid @enderror" id="colaborador_id" name="colaborador_id">
                                    <option value="">Seleccionar colaborador</option>
                                    @foreach($colaboradores as $colaborador)
                                        <option value="{{ $colaborador->id }}" {{ old('colaborador_id', $reporte->colaborador_id) == $colaborador->id ? 'selected' : '' }}>
                                            {{ $colaborador->nombre }} {{ $colaborador->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('colaborador_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="actividad" class="form-label">Actividad *</label>
                                <input type="text" class="form-control @error('actividad') is-invalid @enderror" 
                                       id="actividad" name="actividad" value="{{ old('actividad', $reporte->actividad) }}" required>
                                @error('actividad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="canal_id" class="form-label">Canal</label>
                                <select class="form-select @error('canal_id') is-invalid @enderror" id="canal_id" name="canal_id">
                                    <option value="">Seleccionar canal</option>
                                    @foreach($canales as $canal)
                                        <option value="{{ $canal->id }}" {{ old('canal_id', $reporte->canal_id) == $canal->id ? 'selected' : '' }}>
                                            {{ $canal->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('canal_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="naturaleza_id" class="form-label">Naturaleza</label>
                                <select class="form-select @error('naturaleza_id') is-invalid @enderror" id="naturaleza_id" name="naturaleza_id">
                                    <option value="">Seleccionar naturaleza</option>
                                    @foreach($naturalezas as $naturaleza)
                                        <option value="{{ $naturaleza->id }}" {{ old('naturaleza_id', $reporte->naturaleza_id) == $naturaleza->id ? 'selected' : '' }}>
                                            {{ $naturaleza->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('naturaleza_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="usuario_ti_id" class="form-label">Técnico TI</label>
                            <select class="form-select @error('usuario_ti_id') is-invalid @enderror" id="usuario_ti_id" name="usuario_ti_id">
                                <option value="">Seleccionar técnico</option>
                                @foreach($usuariosTi as $usuarioTi)
                                <option value="{{ $usuarioTi->id }}" {{ old('usuario_ti_id', $reporte->usuario_ti_id) == $usuarioTi->id ? 'selected' : '' }}>
                                {{ $usuarioTi->nombres }} {{ $usuarioTi->apellidos }}
                                </option>
                                 @endforeach
                            </select>
                                @error('usuario_ti_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                          id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion', $reporte->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Reporte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection