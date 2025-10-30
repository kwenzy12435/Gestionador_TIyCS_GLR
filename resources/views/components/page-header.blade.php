<div {{ $attributes->merge(['class'=>'d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2']) }}>
  <div>
    <h1 class="h3 mb-0">{{ $title ?? 'Título' }}</h1>
    @isset($subtitle)<p class="text-muted mb-0">{{ $subtitle }}</p>@endisset
  </div>
  <div class="d-flex gap-2">
    {{ $actions ?? '' }}
  </div>
</div>
