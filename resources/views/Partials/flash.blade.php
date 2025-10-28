@foreach (['success','info','warning','danger','status'] as $type)
  @if(session($type))
    <x-alert :type="$type">{{ session($type) }}</x-alert>
  @endif
@endforeach

@if ($errors->any())
  <x-alert type="danger">
    <strong>Se encontraron errores:</strong>
    <ul class="mb-0">
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </x-alert>
@endif
