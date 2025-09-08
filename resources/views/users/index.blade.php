    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios TI</title>
    <link rel="stylesheet" href="/css/dashboard.css">
    <style>
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .btn { padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; }
        .btn-primary { background: #4a6cf7; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .btn-info { background: #17a2b8; color: white; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        .alert { padding: 1rem; margin-bottom: 1rem; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body class="dashboard-body">
    <div class="dashboard-header">
        <h1 class="dashboard-welcome">Gestión de Usuarios TI</h1>
        <div class="dashboard-user-info">
            <a href="{{ route('dashboard') }}" class="dashboard-logout-btn">← Volver</a>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="header">
            <h2>Lista de Usuarios</h2>
            <a href="{{ route('users.create') }}" class="btn btn-primary">+ Nuevo Usuario</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Nombres</th>
                    <th>Rol</th>
                    <th>Puesto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->usuario }}</td>
                    <td>{{ $user->nombres }} {{ $user->apellidos }}</td>
                    <td>{{ $user->rol }}</td>
                    <td>{{ $user->puesto }}</td>
                    <td>
                     
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>