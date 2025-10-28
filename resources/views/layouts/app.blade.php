<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', config('app.name'))</title>
  @vite(['resources/scss/app.scss','resources/js/app.js'])
  @stack('head')
</head>
<body class="bg-light">
  @include('partials.nav')

  <main class="container py-4">
      @include('partials.flash')
      @yield('breadcrumbs')
      @yield('content')
  </main>

  @include('partials.footer')
  @stack('scripts')
</body>
</html>
