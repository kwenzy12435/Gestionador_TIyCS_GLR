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
<div class="dashboard py-3">

    {{-- KPIs --}}
    <div class="row g-3 mb-3">

        <div class="col-6 col-xl-2">
            <div class="kpi-card kpi-blue fade-in">
                <div class="kpi-label">Dispositivos</div>
                <div class="kpi-value">{{ $totalDispositivos }}</div>
                <div class="kpi-sub">{{ $estadoDispositivos->asignados }} asignados</div>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="kpi-card kpi-green fade-in">
                <div class="kpi-label">Licencias</div>
                <div class="kpi-value">{{ $totalLicencias }}</div>
                <div class="kpi-sub">{{ $licenciasEstado->activas }} activas</div>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="kpi-card kpi-purple fade-in">
                <div class="kpi-label">Usuarios TI</div>
                <div class="kpi-value">{{ $totalUsuariosTI }}</div>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="kpi-card kpi-orange fade-in">
                <div class="kpi-label">Colaboradores</div>
                <div class="kpi-value">{{ $totalColaboradores }}</div>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="kpi-card kpi-red fade-in">
                <div class="kpi-label">Artículos</div>
                <div class="kpi-value">{{ $totalArticulos }}</div>
            </div>
        </div>

    </div>

    {{-- Gráficas --}}
    <div class="row g-3 mb-3">

        {{-- Actividad --}}
        <div class="col-12 col-xxl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-semibold">
                    <i class="bi bi-activity me-1"></i> Actividad (últimos 7 días)
                </div>
                <div class="card-body">
                    <div id="chart-actividad" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        {{-- Usuarios con más tickets --}}
        <div class="col-12 col-xxl-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-semibold">
                    <i class="bi bi-ticket-perforated me-1"></i> Usuarios con más tickets (TI)
                </div>
                <div class="card-body">
                    <div id="chart-usuarios" style="height: 300px;"></div>
                </div>
            </div>
        </div>

    </div>

    {{-- Dispositivos y licencias --}}
    <div class="row g-3 mb-3">

        <div class="col-12 col-xl-5">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-semibold">
                    <i class="bi bi-hdd-network me-1"></i> Dispositivos por estado
                </div>
                <div class="card-body">
                    <div id="chart-dispositivos" style="height: 260px;"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-semibold">
                    <i class="bi bi-pie-chart me-1"></i> Licencias
                </div>
                <div class="card-body">
                    <div id="chart-licencias" style="height: 260px;"></div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ======================================================
    // ACTIVIDAD
    // ======================================================
    let actividadLabels = @json($actividadDias->pluck('fecha'));
    let actividadValores = @json($actividadDias->pluck('cantidad'));

    if (actividadLabels.length === 0) {
        actividadLabels = ["Sin datos"];
        actividadValores = [0];
    }

    new ApexCharts(document.querySelector("#chart-actividad"), {
        chart: { type: 'line', height: 300 },
        series: [{ name: 'Reportes', data: actividadValores }],
        xaxis: { categories: actividadLabels },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#3b82f6']
    }).render();


    // ======================================================
    // USUARIOS TI CON MÁS TICKETS
    // ======================================================
    let usuariosLabels = @json($usuariosMasTickets->pluck('nombre'));
    let usuariosData   = @json($usuariosMasTickets->pluck('total_tickets'));

    if (usuariosLabels.length === 0) {
        usuariosLabels = ["Sin datos"];
        usuariosData = [0];
    }

    new ApexCharts(document.querySelector("#chart-usuarios"), {
        chart: { type: 'bar', height: 300 },
        series: [{ name: 'Tickets', data: usuariosData }],
        xaxis: { categories: usuariosLabels },
        colors: ['#8b5cf6']
    }).render();


    // ======================================================
    // DISPOSITIVOS POR ESTADO
    // ======================================================
    new ApexCharts(document.querySelector("#chart-dispositivos"), {
        chart: { type: 'bar', height: 260 },
        series: [{
            name: "Cantidad",
            data: [
                {{ $estadoDispositivos->nuevos }},
                {{ $estadoDispositivos->asignados }},
                {{ $estadoDispositivos->reparacion }},
                {{ $estadoDispositivos->baja }},
            ]
        }],
        xaxis: { categories: ['Nuevo', 'Asignado', 'Reparación', 'Baja'] },
        colors: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444']
    }).render();


    // ======================================================
    // LICENCIAS
    // ======================================================
    new ApexCharts(document.querySelector("#chart-licencias"), {
        chart: { type: 'donut', height: 260 },
        series: [
            {{ $licenciasEstado->activas }},
            {{ $licenciasEstado->expiradas }},
        ],
        labels: ['Activas', 'Expiradas'],
        colors: ['#10b981', '#ef4444']
    }).render();

});
</script>
@endpush
