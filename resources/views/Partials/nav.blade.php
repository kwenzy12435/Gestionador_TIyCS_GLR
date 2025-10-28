<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('home') }}">{{ config('app.name','App') }}</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('dispositivos.index') }}">Dispositivos</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('licencias.index') }}">Licencias</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('colaboradores.index') }}">Colaboradores</a></li>
      </ul>

      <ul class="navbar-nav ms-auto">
        @auth
          <li class="nav-item"><span class="navbar-text me-2">{{ auth()->user()->name }}</span></li>
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">@csrf
              <button class="btn btn-sm btn-outline-light">Salir</button>
            </form>
          </li>
        @else
          <li class="nav-item"><a class="btn btn-sm btn-outline-light" href="{{ route('login') }}">Ingresar</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
