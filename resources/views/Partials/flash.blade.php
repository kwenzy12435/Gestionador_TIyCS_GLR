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
@if (session('status'))
  <div class="alert alert-info mb-3">{{ session('status') }}</div>
@endif

@if ($errors->any())
  <div class="alert alert-danger mb-3">
    <ul class="mb-0">
      @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
@endif