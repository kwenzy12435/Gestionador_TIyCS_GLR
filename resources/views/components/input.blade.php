@props(['id', 'label' => null, 'type' => 'text', 'name' => null, 'value' => null, 'required' => false, 'placeholder' => ''])
<div class="mb-3">
  @if($label)<label for="{{ $id }}" class="form-label">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>@endif
  <input
    id="{{ $id }}"
    name="{{ $name ?? $id }}"
    type="{{ $type }}"
    value="{{ old($name ?? $id, $value) }}"
    placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }}
    {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name ?? $id)]) }}
  >
  @error($name ?? $id)<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
