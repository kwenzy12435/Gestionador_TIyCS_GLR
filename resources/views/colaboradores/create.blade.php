@extends('layouts.app')
@section('title', 'Nuevo Colaborador')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-person-plus me-2"></i>Registrar Colaborador</h1>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-4 shadow-sm">
    <form method="POST" action="{{ route('colaboradores.store') }}" novalidate>
        @csrf
        
        <div class="row g-3">
            <!-- Usuario -->
            <div class="col-md-4">
                <label for="usuario" class="form-label fw-semibold">Usuario *</label>
                <input type="text" name="usuario" id="usuario" 
                       class="form-control @error('usuario') is-invalid @enderror" 
                       value="{{ old('usuario') }}" 
                       required maxlength="100" 
                       placeholder="Nombre de usuario">
                @error('usuario')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nombre -->
            <div class="col-md-4">
                <label for="nombre" class="form-label fw-semibold">Nombre *</label>
                <input type="text" name="nombre" id="nombre" 
                       class="form-control @error('nombre') is-invalid @enderror" 
                       value="{{ old('nombre') }}" 
                       required maxlength="100" 
                       placeholder="Primer nombre">
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Apellidos -->
            <div class="col-md-4">
                <label for="apellidos" class="form-label fw-semibold">Apellidos</label>
                <input type="text" name="apellidos" id="apellidos" 
                       class="form-control @error('apellidos') is-invalid @enderror" 
                       value="{{ old('apellidos') }}" 
                       maxlength="100" 
                       placeholder="Apellidos completos">
                @error('apellidos')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Puesto -->
            <div class="col-md-4">
                <label for="puesto" class="form-label fw-semibold">Puesto</label>
                <input type="text" name="puesto" id="puesto" 
                       class="form-control @error('puesto') is-invalid @enderror" 
                       value="{{ old('puesto') }}" 
                       maxlength="100" 
                       placeholder="p. ej., Desarrollador, Analista">
                @error('puesto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Departamento -->
            <div class="col-md-4">
                <label for="departamento_id" class="form-label fw-semibold">Departamento</label>
                <select name="departamento_id" id="departamento_id" 
                        class="form-select @error('departamento_id') is-invalid @enderror">
                    <option value="">— Sin asignar —</option>
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->id }}" 
                            {{ old('departamento_id') == $departamento->id ? 'selected' : '' }}>
                            {{ $departamento->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('departamento_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- AnyDesk ID -->
            <div class="col-md-4">
                <label for="anydesk_id" class="form-label fw-semibold">AnyDesk ID</label>
                <input type="text" name="anydesk_id" id="anydesk_id" 
                       class="form-control @error('anydesk_id') is-invalid @enderror" 
                       value="{{ old('anydesk_id') }}" 
                       maxlength="50" 
                       placeholder="Ej. 123 456 789">
                @error('anydesk_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Botones -->
        <div class="text-end mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-brand">
                <i class="bi bi-check2 me-1"></i>Guardar Colaborador
            </button>
            <a href="{{ route('colaboradores.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg me-1"></i>Cancelar
            </a>
        </div>
    </form>
</div>
@endsection