@extends('layouts.app')
@section('title', 'Log de Bajas - Administración')

@section('page-header')
<h1 class="h3 mb-0 fw-bold"><i class="bi bi-archive me-2"></i>Log de Bajas</h1>
<small class="text-muted">Solo visible para administradores</small>
@endsection

@section('header-actions')
<!-- Exportar PDF preservando filtros actuales -->
<a href="{{ route('admin.bajas.export.pdf', request()->query()) }}" 
   class="btn btn-outline-danger" 
   target="_blank">
    <i class="bi bi-filetype-pdf me-1"></i> Exportar PDF
</a>
@endsection

@section('content')
@include('partials.flash')

<div class="card p-3 shadow-sm">
    <!-- Filtros Avanzados -->
    <form method="GET" action="{{ route('admin.bajas.index') }}" class="mb-4" id="filtroBajas">
        <div class="row g-2 align-items-end">
            <!-- Búsqueda general -->
            <div class="col-md-3">
                <label for="search" class="form-label fw-semibold">Búsqueda General</label>
                <input type="text" name="search" id="search" class="form-control"
                       placeholder="Modelo, serie, usuario, MAC, razón..."
                       value="{{ request('search', '') }}">
            </div>

            <!-- Filtro por tipo -->
            <div class="col-md-2">
                <label for="tipo" class="form-label fw-semibold">Tipo</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="">Todos los tipos</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>
                            {{ $tipo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Fecha desde -->
            <div class="col-md-2">
                <label for="fecha_desde" class="form-label fw-semibold">Desde</label>
                <input type="date" name="fecha_desde" id="fecha_desde" 
                       class="form-control" 
                       value="{{ request('fecha_desde') }}">
            </div>

            <!-- Fecha hasta -->
            <div class="col-md-2">
                <label for="fecha_hasta" class="form-label fw-semibold">Hasta</label>
                <input type="date" name="fecha_hasta" id="fecha_hasta" 
                       class="form-control" 
                       value="{{ request('fecha_hasta') }}">
            </div>

            <!-- Botones de acción -->
            <div class="col-md-3 d-grid gap-2">
                <button type="submit" class="btn btn-brand">
                    <i class="bi bi-funnel me-1"></i> Aplicar Filtros
                </button>
            </div>
        </div>

        <!-- Filtros rápidos -->
        <div class="mt-3">
            <small class="fw-semibold text-muted me-2">Filtros rápidos:</small>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-secondary" data-preset-fechas="hoy">
                    Hoy
                </button>
                <button type="button" class="btn btn-outline-secondary" data-preset-fechas="7d">
                    7 días
                </button>
                <button type="button" class="btn btn-outline-secondary" data-preset-fechas="30d">
                    30 días
                </button>
                <a href="{{ route('admin.bajas.index') }}" class="btn btn-outline-danger">
                    <i class="bi bi-x-lg me-1"></i> Limpiar
                </a>
            </div>
        </div>
    </form>

    <!-- Resumen de resultados -->
    @if(request()->anyFilled(['search', 'tipo', 'fecha_desde', 'fecha_hasta']))
    <div class="alert alert-info mb-3">
        <i class="bi bi-info-circle me-2"></i>
        Mostrando <strong>{{ $bajas->total() }}</strong> registros 
        @if(request('search')) con búsqueda "{{ request('search') }}" @endif
        @if(request('tipo')) del tipo "{{ request('tipo') }}" @endif
        @if(request('fecha_desde')) desde {{ \Carbon\Carbon::parse(request('fecha_desde'))->format('d/m/Y') }} @endif
        @if(request('fecha_hasta')) hasta {{ \Carbon\Carbon::parse(request('fecha_hasta'))->format('d/m/Y') }} @endif
    </div>
    @endif

    <!-- Tabla de resultados -->
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>N.º Serie</th>
                    <th>Usuario</th>
                    <th>MAC</th>
                    <th>TI Responsable</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bajas as $baja)
                    @php
                        $tipoBadge = match($baja->tipo) {
                            'Laptop', 'Computadora' => 'bg-primary',
                            'Tablet', 'Celular' => 'bg-info',
                            'Monitor', 'Impresora' => 'bg-warning text-dark',
                            'Router', 'Switch' => 'bg-success',
                            default => 'bg-secondary'
                        };
                    @endphp
                    <tr>
                        <td class="text-nowrap">
                            <strong>{{ $baja->fecha ? \Carbon\Carbon::parse($baja->fecha)->format('d/m/Y') : '—' }}</strong>
                        </td>
                        <td>
                            <span class="badge {{ $tipoBadge }}">
                                {{ $baja->tipo ?? '—' }}
                            </span>
                        </td>
                        <td>{{ $baja->marca_nombre ?? '—' }}</td>
                        <td>
                            <strong>{{ $baja->modelo ?? '—' }}</strong>
                        </td>
                        <td>
                            <code class="text-primary">{{ $baja->numero_serie ?? '—' }}</code>
                        </td>
                        <td>{{ $baja->usuario_nombre ?? '—' }}</td>
                        <td>
                            @if($baja->mac_address)
                                <code class="text-success">{{ $baja->mac_address }}</code>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($baja->ti_usuario)
                                <small>
                                    <strong>{{ $baja->ti_usuario }}</strong><br>
                                    {{ $baja->ti_nombres }} {{ $baja->ti_apellidos }}
                                </small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.bajas.show', $baja->id) }}" 
                               class="btn btn-sm btn-outline-primary" 
                               title="Ver detalles completos">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-archive display-4 d-block mb-2"></i>
                            No se encontraron registros de bajas
                            @if(request()->anyFilled(['search', 'tipo', 'fecha_desde', 'fecha_hasta']))
                                <br><small>Intenta con otros criterios de búsqueda</small>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación con filtros preservados -->
    @if($bajas->hasPages())
        <div class="card-footer">
            {{ $bajas->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtros rápidos de fechas
    document.querySelectorAll('[data-preset-fechas]').forEach(button => {
        button.addEventListener('click', function() {
            const preset = this.dataset.presetFechas;
            const hoy = new Date();
            let fechaDesde = new Date();
            let fechaHasta = new Date();

            switch(preset) {
                case 'hoy':
                    // Ya está configurado para hoy
                    break;
                case '7d':
                    fechaDesde.setDate(hoy.getDate() - 7);
                    break;
                case '30d':
                    fechaDesde.setDate(hoy.getDate() - 30);
                    break;
            }

            // Formatear fechas para inputs
            document.getElementById('fecha_desde').value = preset === 'hoy' ? 
                hoy.toISOString().split('T')[0] : 
                fechaDesde.toISOString().split('T')[0];
            
            document.getElementById('fecha_hasta').value = fechaHasta.toISOString().split('T')[0];

            // Enviar formulario automáticamente
            document.getElementById('filtroBajas').submit();
        });
    });

    // Validación de rango de fechas
    const fechaDesde = document.getElementById('fecha_desde');
    const fechaHasta = document.getElementById('fecha_hasta');

    if (fechaDesde && fechaHasta) {
        fechaDesde.addEventListener('change', function() {
            if (this.value && fechaHasta.value && this.value > fechaHasta.value) {
                fechaHasta.value = this.value;
            }
        });

        fechaHasta.addEventListener('change', function() {
            if (this.value && fechaDesde.value && this.value < fechaDesde.value) {
                fechaDesde.value = this.value;
            }
        });
    }
});
</script>
@endpush