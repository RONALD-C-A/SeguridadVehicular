¡Hola {{ $usuario->Nombre }}!

Para activar tu cuenta, haz clic en el siguiente enlace:

{{ route('activar.cuenta', $token) }}

Este enlace expira en 1 hora.