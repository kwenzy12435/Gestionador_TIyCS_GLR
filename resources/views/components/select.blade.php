@props(['id','label'=>null,'name'=>null,'options'=>[],'value'=>null,'required'=>false])
<div class="mb-3">
  @if($label)<label for="{{ $id }}" class="form-label">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>@endif
  <select id="{{ $id }}" name="{{ $name ?? $id }}" {{ $required ? 'required' : '' }}
          {{ $attributes->class(['form-select','is-invalid'=>$errors->has($name ?? $id)]) }}>
    <option value="">— Selecciona —</option>
    @foreach($options as $k => $text)
      <option value="{{ $k }}" @selected(old($name ?? $id, $value)==$k)>{{ $text }}</option>
    @endforeach
  </select>
  @error($name ?? $id)<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
