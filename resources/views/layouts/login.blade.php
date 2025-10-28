<!doctype html>
<html lang="es">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>

  {{-- Bootstrap y estilos --}}
  @vite(['resources/scss/login.scss','resources/js/app.js'])

  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  @stack('head')
</head>
<body>
  @yield('content')
  @stack('scripts')
</body>
</html>
