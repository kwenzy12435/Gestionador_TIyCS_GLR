<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error @yield('codigo')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

   <style>
    body, html {
        height: 100%;
        margin: 0;
        overflow: hidden;
        font-family: 'Montserrat', sans-serif;
    }

    /* 🔥 Ajustado: se verá oscuro pero NO negro */
    .bg-video {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        object-fit: cover;
        z-index: -1;
        filter: brightness(50%) contrast(110%); /* ⭐ PERFECTO PARA VIDEOS */
    }

    /* 🔥 Ajustamos la tarjeta */
    .error-card {
        background: rgba(0, 0, 0, 0.65); /* ANTES 0.85 — mucho mejor ahora */
        border-radius: 20px;
        padding: 60px 50px;
        text-align: center;
        max-width: 750px;
        backdrop-filter: blur(10px); /* Suaviza sin oscurecer */
    }

   .error-code {
    font-size: 200px;
    font-weight: 900;
    color: #e6e6e6;
}

    .btn-brand {
        background: #e86f64;
        border: none;
        padding: 15px 35px;
        font-weight: bold;
        border-radius: 12px;
        font-size: 18px;
    }

    /* 🔥 Logo: lo dejo EXACTAMENTE como antes lo tenías */
    .auth-logo {
        max-width: 260px;
        width: 100%;
        margin-bottom: 15px; /* SOLO BAJAMOS ESTE VALOR */
    }
</style>

</head>
<body>

    
   <video autoplay muted loop class="bg-video">
    <source src="{{ asset('Front/Video1.mp4') }}" type="video/mp4">
</video>


    <div class="d-flex align-items-center justify-content-center" style="height:100vh;">
        <div class="error-card shadow-lg">

           
            <img src="{{ asset('Front/IMAGEN1.png') }}" alt="Grupo López-Rosa" class="auth-logo">

            <h2 class="fw-bold mb-2">ERROR</h2>

            <div class="error-code">@yield('codigo')</div>

            <p class="fs-4 mb-1">@yield('mensaje')</p>
            <p class="text-muted">@yield('detalle')</p>

            <a href="{{ route('dashboard') }}" class="btn btn-brand mt-4">
    Volver al inicio
</a>
        </div>
    </div>

</body>
</html>
