{{-- resources/views/partials/flash.blade.php --}}
@php
  // Mapa: clave de sesión => clase Bootstrap
  $flashMap = [
    'success' => 'success',
    'status'  => 'success',
    'info'    => 'info',
    'warning' => 'warning',
    'error'   => 'danger',
  ];

  // Icono por tipo
  $icon = fn($type) => match($type) {
    'success' => 'check-circle',
    'info'    => 'info-circle',
    'warning' => 'exclamation-triangle',
    'danger'  => 'exclamation-octagon',
    default   => 'info-circle',
  };
@endphp

@foreach($flashMap as $key => $bs)
  @if(session()->has($key))
    @php
      $payload  = session($key);
      $messages = is_array($payload) ? $payload : [$payload];
    @endphp

    @foreach($messages as $message)
      @if(filled($message))
        <div class="alert alert-{{ $bs }} alert-dismissible fade show shadow-sm" role="alert">
          <i class="bi bi-{{ $icon($bs) }} me-2"></i>
          {!! $message !!}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
      @endif
    @endforeach
  @endif
@endforeach

{{-- Importante: NO renderizamos $errors aquí para no duplicar mensajes de validación.
     Los mensajes de validación deben ir junto a cada campo con:
     @error('campo') <div class="invalid-feedback">{{ $message }}</div> @enderror --}}
