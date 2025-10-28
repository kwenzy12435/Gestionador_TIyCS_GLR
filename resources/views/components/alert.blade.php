@props(['type' => 'info'])
@php
$map = [
  'success' => 'alert-success',
  'info'    => 'alert-info',
  'warning' => 'alert-warning',
  'danger'  => 'alert-danger',
  'status'  => 'alert-primary',
];
@endphp

<div {{ $attributes->class(['alert', $map[$type] ?? 'alert-info'])->merge(['role'=>'alert']) }}>
  {{ $slot }}
</div>
