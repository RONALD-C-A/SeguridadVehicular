¡Hola {{ $usuario->Nombre }}!

Para restablecer tu contraseña, haz clic en el siguiente enlace:

{{ route('restablecer.contrasena', $token) }}

Este enlace expira en 1 hora.