{{-- resources/views/admin/bajas/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Log de Bajas — Exportación</title>
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; }
    h1 { font-size: 18px; margin: 0 0 10px 0; }
    .muted { color: #555; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 6px 8px; }
    th { background: #e9786a; color: #fff; text-transform: uppercase; font-size: 11px; }
    .right { text-align: right; }
    .nowrap { white-space: nowrap; }
    .small { font-size: 11px; }
    .header { display:flex; justify-content: space-between; align-items:center; margin-bottom: 8px; }
  </style>
</head>
<body>
  <div class="header">
    <h1>Log de Bajas</h1>
    <div class="small muted">
      Generado: {{ now()->format('d/m/Y H:i') }}<br>
      Filtros:
      @if(request('search')) “{{ request('search') }}” @else ninguno @endif
      @if(request('fecha_desde')) · desde {{ \Carbon\Carbon::parse(request('fecha_desde'))->format('d/m/Y') }} @endif
      @if(request('fecha_hasta')) · hasta {{ \Carbon\Carbon::parse(request('fecha_hasta'))->format('d/m/Y') }} @endif
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th class="nowrap">Fecha</th>
        <th>Tipo</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>N.º Serie</th>
        <th>Usuario</th>
        <th>MAC</th>
        <th>TI Responsable</th>
      </tr>
    </thead>
    <tbody>
      @forelse($bajas as $b)
        <tr>
          <td class="nowrap">{{ \Carbon\Carbon::parse($b->fecha)->format('d/m/Y') }}</td>
          <td>{{ $b->tipo }}</td>
          <td>{{ $b->marca_nombre ?? '—' }}</td>
          <td>{{ $b->modelo }}</td>
          <td class="nowrap">{{ $b->numero_serie }}</td>
          <td>{{ $b->usuario_nombre }}</td>
          <td class="nowrap">{{ $b->mac_address ?? '—' }}</td>
          <td>
            {{ $b->ti_nombres ? $b->ti_nombres.' '.$b->ti_apellidos : '—' }}
          </td>
        </tr>
      @empty
        <tr><td colspan="8" class="right muted">Sin resultados.</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
