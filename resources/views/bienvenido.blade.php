<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">Bienvenido, {{ Auth::user()->Nombre }}</h2>
                        <p>Rol: {{ Auth::user()->Rol }}</p>
                        <p>Nombre: {{ Auth::user()->Nombre }}</p>
                        <p>Nombre de Usuario: {{ Auth::user()->NombreUsuario }}</p>
                        <p>Email: {{ Auth::user()->Email }}</p>
                        <form action="{{ route('cerrar.sesion') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>