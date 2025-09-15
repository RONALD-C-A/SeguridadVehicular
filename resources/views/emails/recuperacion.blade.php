<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña - Sistema de Seguridad Vehicular</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f5f0e6; color: #333; padding: 20px;">
    <div
        style="max-width: 650px; margin: auto; background-color: #fff8f0; border: 1px solid #e0d8c3; padding: 25px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">

        <!-- Encabezado principal -->
        <h1 style="color: #5b4636; text-align: center; margin-bottom: 10px;">Restablecer tu contraseña</h1>
        <h2 style="color: #7b5e57; text-align: center; font-weight: normal; margin-top: 0;">Hola {{ $usuario->Nombre }},</h2>
        <p style="text-align: center; font-size: 13px; line-height: 1.5;">
            Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en el Sistema de Seguridad Vehicular. 
            Para continuar, haz clic en el botón a continuación.
        </p>

        <!-- Instrucciones -->
        <ol style="margin-left: 20px;">
            <li>Haz clic en el botón <strong>Restablecer contraseña</strong> a continuación.</li>
            <li>Si el botón no funciona, copia y pega el enlace en tu navegador.</li>
            <li>Este enlace expirará en <strong>1 hora</strong>.</li>
        </ol>

        <!-- Botón -->
        <p style="text-align: center; margin: 25px 0;">
            <a href="{{ $enlace }}"
                style="display: inline-block; padding: 12px 25px; background-color: #7b5e57; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold;">Restablecer
                contraseña</a>
        </p>

        <!-- Enlace visible -->
        <p style="font-size: 14px; word-break: break-all;">
            Enlace directo: <a href="{{ $enlace }}" style="color: #d49292;">{{ $enlace }}</a>
        </p>

        <!-- Nota de seguridad -->
        <p style="font-size: 14px; color: #555;">
            Si no solicitaste este cambio, por favor ignora este correo. Tu contraseña permanecerá sin cambios.
        </p>

        <!-- Privacidad y políticas -->
        <hr style="border-color: #e0d8c3;">
        <p style="font-size: 12px; color: #888;">
            Al usar nuestro servicio, aceptas nuestras <a href="#" style="color: #7b5e57;">Políticas de Privacidad</a> y
            <a href="#" style="color: #7b5e57;">Términos de Uso</a>.
            Todos los datos son manejados de manera segura y confidencial.
        </p>

        <!-- Footer corporativo -->
        <footer style="font-size: 12px; color: #888; text-align: center; margin-top: 15px;">
            Sistema de Seguridad Vehicular &copy; {{ date('Y') }}<br>
            Seguridad y control de vehículos en todo momento
        </footer>
    </div>
</body>

</html>
