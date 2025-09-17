@extends('layouts.app')

@section('title', 'Crear Artículo - Sistema de Gestión TI')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-server me-2"></i>Sistema Gestión TI
        </a>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('articulos.index') }}">
                            <i class="fas fa-box me-2"></i>
                            Artículos
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Crear Nuevo Artículo</h1>
                <a href="{{ route('articulos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('articulos.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="categoria_id" class="form-label">Categoría *</label>
                                <select class="form-select @error('categoria_id') is-invalid @enderror" 
                                        id="categoria_id" name="categoria_id" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="subcategoria_id" class="form-label">Subcategoría</label>
                                <select class="form-select @error('subcategoria_id') is-invalid @enderror" 
                                        id="subcategoria_id" name="subcategoria_id">
                                    <option value="">Seleccionar subcategoría</option>
                                    @foreach($subcategorias as $subcategoria)
                                        <option value="{{ $subcategoria->id }}" {{ old('subcategoria_id') == $subcategoria->id ? 'selected' : '' }}>
                                            {{ $subcategoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subcategoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="cantidad" class="form-label">Cantidad *</label>
                                <input type="number" class="form-control @error('cantidad') is-invalid @enderror" 
                                       id="cantidad" name="cantidad" value="{{ old('cantidad') }}" min="0" required>
                                @error('cantidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="unidades" class="form-label">Unidades *</label>
                                <select class="form-select @error('unidades') is-invalid @enderror" 
                                        id="unidades" name="unidades" required>
                                    <option value="">Seleccionar unidades</option>
                                    <option value="piezas" {{ old('unidades') == 'piezas' ? 'selected' : '' }}>Piezas</option>
                                    <option value="cajas" {{ old('unidades') == 'cajas' ? 'selected' : '' }}>Cajas</option>
                                    <option value="paquetes" {{ old('unidades') == 'paquetes' ? 'selected' : '' }}>Paquetes</option>
                                </select>
                                @error('unidades')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="ubicacion" class="form-label">Ubicación *</label>
                                <select class="form-select @error('ubicacion') is-invalid @enderror" 
                                        id="ubicacion" name="ubicacion" required>
                                    <option value="">Seleccionar ubicación</option>
                                    <option value="cajon1" {{ old('ubicacion') == 'cajon1' ? 'selected' : '' }}>Cajón 1</option>
                                    <option value="rafa" {{ old('ubicacion') == 'rafa' ? 'selected' : '' }}>Rafa</option>
                                    <option value="cajon4" {{ old('ubicacion') == 'cajon4' ? 'selected' : '' }}>Cajón 4</option>
                                    <option value="almacen" {{ old('ubicacion') == 'almacen' ? 'selected' : '' }}>Almacén</option>
                                    <option value="oficina" {{ old('ubicacion') == 'oficina' ? 'selected' : '' }}>Oficina</option>
                                </select>
                                @error('ubicacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso *</label>
                                <input type="date" class="form-control @error('fecha_ingreso') is-invalid @enderror" 
                                       id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" required>
                                @error('fecha_ingreso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select @error('estado') is-invalid @enderror" 
                                        id="estado" name="estado" required>
                                    <option value="">Seleccionar estado</option>
                                    <option value="Disponible" {{ old('estado') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                    <option value="no disponible" {{ old('estado') == 'no disponible' ? 'selected' : '' }}>No Disponible</option>
                                    <option value="pocas piezas" {{ old('estado') == 'pocas piezas' ? 'selected' : '' }}>Pocas Piezas</option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                          id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Artículo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection