<!doctype html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Iniciar Sesión')</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('Front/favicon.png') }}">

    {{-- CSS y JS EXCLUSIVOS DEL LOGIN --}}
    @vite(['resources/scss/auth/login.scss', 'resources/js/auth/login.js'])

    {{-- Iconos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="auth-page">
    {{-- FONDO CON VIDEO1 --}}
    <div class="auth-bg">
        <video class="auth-video" autoplay muted loop playsinline > 
            <source src="{{ asset('Front/VIDEO1.mp4') }}" type="video/mp4">
        </video>
        <div class="auth-overlay"></div>
    </div>

    {{-- CONTENIDO DEL LOGIN --}}
    <main class="auth-main">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
