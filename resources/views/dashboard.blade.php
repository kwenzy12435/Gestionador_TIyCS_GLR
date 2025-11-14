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
  {{-- KPIs --}}
  <div class="row g-3 mb-3">
    <div class="col-6 col-xl-2">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small text-uppercase">Dispositivos</div>
            <div class="h4 mb-0">{{ $totalDispositivos ?? 0 }}</div>
            <div class="small text-muted">{{ $dispositivosActivos ?? 0 }} activos</div>
          </div>
          <i class="bi bi-laptop fs-2 text-primary opacity-75"></i>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-2">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small text-uppercase">Licencias</div>
            <div class="h4 mb-0">{{ ($licenciasPorEstado['activas'] ?? 0) + ($licenciasPorEstado['por_expiar'] ?? 0) }}</div>
            <div class="small text-muted">{{ $licenciasPorExpiar ?? ($licenciasPorEstado['por_expiar'] ?? 0) }} por expirar</div>
          </div>
          <i class="bi bi-key fs-2 text-warning opacity-75"></i>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-2">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small text-uppercase">Reportes 7d</div>
            <div class="h4 mb-0">{{ $reportesRecientes ?? 0 }}</div>
            <div class="small text-muted">Actividad</div>
          </div>
          <i class="bi bi-clipboard-check fs-2 text-success opacity-75"></i>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-2">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small text-uppercase">Colaboradores</div>
            <div class="h4 mb-0">{{ $totalColaboradores ?? 0 }}</div>
            <div class="small text-muted">Registro HR</div>
          </div>
          <i class="bi bi-people fs-2 text-info opacity-75"></i>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-2">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small text-uppercase">Usuarios TI</div>
            <div class="h4 mb-0">{{ $totalUsuariosTI ?? 0 }}</div>
            <div class="small text-muted">Equipo</div>
          </div>
          <i class="bi bi-person-gear fs-2 text-secondary opacity-75"></i>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-2">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small text-uppercase">Artículos</div>
            <div class="h4 mb-0">{{ $totalArticulos ?? 0 }}</div>
            <div class="small text-muted">Almacén</div>
          </div>
          <i class="bi bi-box-seam fs-2 text-danger opacity-75"></i>
        </div>
      </div>
    </div>
  </div>

  {{-- Gráficas y accesos --}}
  <div class="row g-3 mb-3">
    <div class="col-12 col-xxl-7">
      <div class="card shadow-sm h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span class="fw-semibold"><i class="bi bi-activity me-1"></i> Actividad (últimos 7 días)</span>
        </div>
        <div class="card-body">
          <div id="chart-actividad" style="height: 280px;"></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-xxl-5">
      <div class="row g-3 h-100">
        <div class="col-12">
          <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span class="fw-semibold"><i class="bi bi-pie-chart me-1"></i> Licencias</span>
            </div>
            <div class="card-body">
              <div id="chart-licencias" style="height: 220px;"></div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span class="fw-semibold"><i class="bi bi-bar-chart me-1"></i> Dispositivos por estado</span>
            </div>
            <div class="card-body">
              <div id="chart-dispositivos" style="height: 220px;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Listas: Dispositivos y Actividad reciente --}}
  <div class="row g-3">
    <div class="col-12 col-xxl-6">
      <div class="card shadow-sm h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span class="fw-semibold"><i class="bi bi-hdd-network me-1"></i> Dispositivos recientes</span>
          <a href="{{ route('inventario-dispositivos.index') }}" class="btn btn-sm btn-outline-primary">
            Ver todos
          </a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>Modelo</th>
                  <th>Colaborador</th>
                  <th>Estado</th>
                  <th>Alta</th>
                </tr>
              </thead>
              <tbody>
                @forelse($dispositivosRecientes as $d)
                  <tr>
                    <td>{{ data_get($d, 'modelo', '—') }}</td>
                    <td>
                      @php
                        $n = trim((string) data_get($d, 'colaborador.nombres', ''));
                        $a = trim((string) data_get($d, 'colaborador.apellidos', ''));
                      @endphp
                      {{ $n || $a ? trim($n.' '.$a) : '—' }}
                    </td>
                    <td><span class="badge bg-light text-dark">{{ data_get($d, 'estado', '—') }}</span></td>
                    <td class="text-muted">
                      @php $dt = data_get($d, 'created_at'); @endphp
                      {{ $dt ? \Carbon\Carbon::parse($dt)->format('d/m/Y H:i') : '—' }}
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-muted py-3">Sin registros.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xxl-6">
      <div class="card shadow-sm h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span class="fw-semibold"><i class="bi bi-clock-history me-1"></i> Actividad reciente</span>
          <a href="{{ route('reporte_actividades.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
        </div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            @forelse($actividadReciente as $item)
              <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-3">
                  <div class="fw-semibold">{{ data_get($item, 'actividad', '—') }}</div>
                  <small class="text-muted">
                    {{ \Illuminate\Support\Str::limit((string) data_get($item, 'descripcion', ''), 80) }}
                  </small>
                  <div class="mt-1 small">
                    <span class="badge bg-secondary me-1">
                      {{ data_get($item, 'naturaleza.nombre', '—') }}
                    </span>
                    @php
                      $cn = trim((string) data_get($item, 'colaborador.nombres', ''));
                      $ca = trim((string) data_get($item, 'colaborador.apellidos', ''));
                    @endphp
                    <span class="text-muted">{{ $cn || $ca ? trim($cn.' '.$ca) : '—' }}</span>
                  </div>
                </div>
                <small class="text-muted">
                  @php $rc = data_get($item, 'created_at'); @endphp
                  {{ $rc ? \Carbon\Carbon::parse($rc)->diffForHumans() : '—' }}
                </small>
              </li>
            @empty
              <li class="list-group-item text-muted">Sin actividad reciente.</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  {{-- ApexCharts CDN (simple y rápido) --}}
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    (function () {
      // Datos desde el controlador (con fallback seguro)
      const actividadLabels = @json(array_keys($actividadPorDia ?? []));
      const actividadData   = @json(array_values($actividadPorDia ?? []));
      const licenciasData   = @json([
        $licenciasPorEstado['activas']    ?? 0,
        $licenciasPorEstado['por_expiar'] ?? 0,
        $licenciasPorEstado['expiradas']  ?? 0
      ]);
      const dispositivosEstado = @json($dispositivosPorEstado ?? []);
      const dispLabels = Object.keys(dispositivosEstado);
      const dispData   = Object.values(dispositivosEstado);

      // Línea: Actividad por día
      new ApexCharts(document.querySelector("#chart-actividad"), {
        chart: { type: 'line', height: 280, toolbar: { show: false } },
        series: [{ name: 'Reportes', data: actividadData }],
        xaxis: { categories: actividadLabels },
        stroke: { width: 3, curve: 'smooth' },
        markers: { size: 3 },
        dataLabels: { enabled: false }
      }).render();

      // Dona: Licencias
      new ApexCharts(document.querySelector("#chart-licencias"), {
        chart: { type: 'donut', height: 220 },
        labels: ['Activas', 'Por expirar', 'Expiradas'],
        series: licenciasData,
        legend: { position: 'bottom' }
      }).render();

      // Barras: Dispositivos por estado
      new ApexCharts(document.querySelector("#chart-dispositivos"), {
        chart: { type: 'bar', height: 220, toolbar: { show: false } },
        series: [{ name: 'Dispositivos', data: dispData }],
        xaxis: { categories: dispLabels },
        plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
        dataLabels: { enabled: false }
      }).render();
    })();
  </script>
@endpush
