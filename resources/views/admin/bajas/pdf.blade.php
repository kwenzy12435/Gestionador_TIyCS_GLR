<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Bajas - {{ now()->format('d-m-Y') }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, Arial, sans-serif; 
            font-size: 12px; 
            color: #333; 
            margin: 0;
            padding: 15px;
        }
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #dc3545;
        }
        h1 { 
            font-size: 20px; 
            margin: 0; 
            color: #dc3545;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin: 5px 0 0 0;
        }
        .info-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        th { 
            background: #dc3545; 
            color: white; 
            text-transform: uppercase; 
            font-size: 10px;
            padding: 8px 6px;
            border: 1px solid #dc3545;
        }
        td { 
            padding: 6px; 
            border: 1px solid #ddd; 
            font-size: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-nowrap { white-space: nowrap; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .bg-primary { background: #007bff; color: white; }
        .bg-success { background: #28a745; color: white; }
        .bg-warning { background: #ffc107; color: black; }
        .bg-info { background: #17a2b8; color: white; }
        .bg-secondary { background: #6c757d; color: white; }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <div>
            <h1>REPORTE DE BAJAS DE INVENTARIO</h1>
            <p class="subtitle">Sistema de Gestión TI - {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div style="text-align: right;">
            <strong>Total de registros:</strong> {{ $bajas->count() }}<br>
            <small>Página 1 de 1</small>
        </div>
    </div>

    <!-- Información de Filtros -->
    @if(!empty(array_filter($filtrosAplicados)))
    <div class="info-box">
        <strong>Filtros aplicados:</strong>
        @if($filtrosAplicados['search'])
            <span class="badge bg-primary">Búsqueda: "{{ $filtrosAplicados['search'] }}"</span>
        @endif
        @if($filtrosAplicados['tipo'])
            <span class="badge bg-success">Tipo: {{ $filtrosAplicados['tipo'] }}</span>
        @endif
        @if($filtrosAplicados['fecha_desde'])
            <span class="badge bg-warning">Desde: {{ \Carbon\Carbon::parse($filtrosAplicados['fecha_desde'])->format('d/m/Y') }}</span>
        @endif
        @if($filtrosAplicados['fecha_hasta'])
            <span class="badge bg-info">Hasta: {{ \Carbon\Carbon::parse($filtrosAplicados['fecha_hasta'])->format('d/m/Y') }}</span>
        @endif
    </div>
    @endif

    <!-- Tabla de Bajas -->
    <table>
        <thead>
            <tr>
                <th width="80">Fecha</th>
                <th width="70">Tipo</th>
                <th width="80">Marca</th>
                <th>Modelo</th>
                <th width="100">N.º Serie</th>
                <th>Usuario</th>
                <th width="120">MAC</th>
                <th>TI Responsable</th>
                <th width="80">Razón</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bajas as $baja)
                <tr>
                    <td class="text-nowrap">
                        {{ $baja->fecha ? \Carbon\Carbon::parse($baja->fecha)->format('d/m/Y') : '—' }}
                    </td>
                    <td>{{ $baja->tipo ?? '—' }}</td>
                    <td>{{ $baja->marca_nombre ?? '—' }}</td>
                    <td>{{ $baja->modelo ?? '—' }}</td>
                    <td class="text-nowrap">{{ $baja->numero_serie ?? '—' }}</td>
                    <td>{{ $baja->usuario_nombre ?? '—' }}</td>
                    <td class="text-nowrap">{{ $baja->mac_address ?? '—' }}</td>
                    <td>
                        {{ $baja->ti_nombres ? $baja->ti_nombres . ' ' . $baja->ti_apellidos : ($baja->ti_usuario ?? '—') }}
                    </td>
                    <td>{{ $baja->razon_baja ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px;">
                        No se encontraron registros de bajas con los filtros aplicados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pie de página -->
    <div class="footer">
        <strong>Sistema de Gestión TI</strong> | 
        Generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }} | 
        Página 1 de 1
    </div>
</body>
</html>