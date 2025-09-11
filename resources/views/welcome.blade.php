<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4a2c2a, #1c2526);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .form-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 2rem;
            border-radius: 10px;
            color: #fff;
            width: 100%;
            max-width: 400px;
        }
        .welcome-text {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .form-label {
            color: #fff;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #d2691e;
            border: none;
            color: #fff;
            padding: 0.5rem 2rem;
            border-radius: 20px;
            width: 100%;
        }
        .btn-custom:hover {
            background-color: #a0522d;
        }
        .link-custom {
            color: #d2691e;
            text-decoration: none;
        }
        .link-custom:hover {
            color: #a0522d;
        }
        .triangle {
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 0 100vh 100vw;
            border-color: transparent transparent #d2691e transparent;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="triangle"></div>
    <div class="form-container">
        <h2 class="welcome-text">¡BIENVENIDO!</h2>
        <p>Estamos felices de tenerte. Si necesitas cualquier cosa, ¡estamos aquí para ayudar!</p>
        <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" class="form-control bg-dark text-white" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control bg-dark text-white" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-custom">Iniciar Sesión</button>
            <p class="mt-3 text-center"><a href="{{ route('register') }}" class="link-custom">¿No tienes una cuenta? Regístrate</a></p>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#loginForm").validate({
                rules: {
                    username: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    username: {
                        required: "Por favor ingresa tu usuario"
                    },
                    password: {
                        required: "Por favor ingresa tu contraseña",
                        minlength: "La contraseña debe tener al menos 6 caracteres"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>