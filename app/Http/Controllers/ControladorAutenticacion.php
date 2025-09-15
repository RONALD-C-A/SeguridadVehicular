<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ControladorAutenticacion extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except(['cerrarSesion', 'escritorio']);
    }

    // Mostrar formulario de login
    public function mostrarLogin()
    {
        return view('auth.iniciarSesion');
    }

    // Procesar login
    public function iniciarSesion(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'usuario' => 'required|string',
            'password' => 'required|string',
        ], [
            'usuario.required' => 'El usuario o correo es obligatorio',
            'password.required' => 'La contraseña es obligatoria'
        ]);

        if ($validador->fails()) {
            return response()->json([
                'exito' => false,
                'errores' => $validador->errors()
            ]);
        }

        $credencial = $request->input('usuario');

        // Buscar usuario por email o nombre de usuario
        $usuario = Usuario::where(function ($query) use ($credencial) {
            $query->where('Email', $credencial)
                ->orWhere('NombreUsuario', $credencial);
        })->first();

        if (!$usuario) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'El usuario no existe'
            ]);
        }

        if (!$usuario->estaActivo()) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'Su cuenta aún no ha sido activada. Revise su correo electrónico.'
            ]);
        }

        if (!Hash::check($request->password, $usuario->Password)) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'Contraseña incorrecta'
            ]);
        }

        Auth::login($usuario);

        return response()->json([
            'exito' => true,
            'mensaje' => '¡Bienvenido! Iniciando sesión...',
            'redireccion' => route('escritorio')
        ]);
    }

    // Mostrar formulario de registro
    public function mostrarRegistro()
    {
        return view('auth.registro');
    }

    // Procesar registro
    public function registrar(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'nombre' => 'required|string|max:45',
            'nombreUsuario' => 'required|string|max:45',
            'email' => 'required|email|max:45',
            'password' => 'required|string|min:6',
            'confirmarPassword' => 'required|same:password'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombreUsuario.required' => 'El nombre de usuario es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Ingrese un correo electrónico válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'confirmarPassword.required' => 'Debe confirmar la contraseña',
            'confirmarPassword.same' => 'Las contraseñas no coinciden'
        ]);

        if ($validador->fails()) {
            return response()->json([
                'exito' => false,
                'errores' => $validador->errors()
            ]);
        }

        try {
            // Verificar si ya existe un usuario con ese correo o usuario
            $usuario = Usuario::where('Email', $request->email)
                ->orWhere('NombreUsuario', $request->nombreUsuario)
                ->first();

            if ($usuario) {
                if ($usuario->estaActivo()) {
                    return response()->json([
                        'exito' => false,
                        'errores' => [
                            'email' => ['Este correo ya está registrado'],
                            'nombreUsuario' => ['Este nombre de usuario ya está registrado']
                        ]
                    ]);
                } else {
                    // Usuario inactivo: regenerar token y actualizar datos
                    $usuario->Nombre = $request->nombre;
                    $usuario->NombreUsuario = $request->nombreUsuario;
                    $usuario->Password = $request->password;
                    $token = $usuario->generarTokenRestablecimiento();
                    $usuario->save();

                    $this->enviarCorreoActivacion($usuario, $token);

                    return response()->json([
                        'exito' => true,
                        'mensaje' => 'Su cuenta estaba pendiente de activación. Se ha reenviado el correo de activación.'
                    ]);
                }
            } else {
                // Crear usuario nuevo
                $usuario = new Usuario();
                $usuario->Rol = 'CLIENTE';
                $usuario->Nombre = $request->nombre;
                $usuario->NombreUsuario = $request->nombreUsuario;
                $usuario->Email = $request->email;
                $usuario->Password = $request->password;
                $usuario->Estado = 0; // Inactivo
                $usuario->FechaRegistro = now();

                $token = $usuario->generarTokenRestablecimiento();
                $usuario->save();

                $this->enviarCorreoActivacion($usuario, $token);

                return response()->json([
                    'exito' => true,
                    'mensaje' => 'Registro exitoso. Revise su correo para activar su cuenta.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'Error al registrar usuario: ' . $e->getMessage()
            ]);
        }
    }


    // Mostrar formulario de recuperación de contraseña
    public function mostrarRecuperacion()
    {
        return view('auth.recuperarContrasena');
    }

    // Procesar recuperación de contraseña
    public function enviarRecuperacion(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'email' => 'required|email'
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Ingrese un correo electrónico válido'
        ]);

        if ($validador->fails()) {
            return response()->json([
                'exito' => false,
                'errores' => $validador->errors()
            ]);
        }

        $usuario = Usuario::where('Email', $request->email)->first();

        if (!$usuario) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'No existe una cuenta registrada con este correo electrónico'
            ]);
        }
        // Verificar si la cuenta está activa
        if (!$usuario->estaActivo()) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'La cuenta aún no ha sido activada. Por favor revise su correo de activación.'
            ]);
        }

        try {
            $token = $usuario->generarTokenRestablecimiento();
            $this->enviarCorreoRecuperacion($usuario, $token);

            return response()->json([
                'exito' => true,
                'mensaje' => 'Se ha enviado un enlace de recuperación a su correo electrónico'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'Error al enviar correo: ' . $e->getMessage()
            ]);
        }
    }

    // Mostrar formulario de restablecimiento
    public function mostrarRestablecimiento($token)
    {
        $usuario = Usuario::where('ResetToken', $token)
            ->where('ResetTokenExpires', '>', now())
            ->first();

        if (!$usuario) {
            return redirect()->route('mostrar.login')
                ->with('error', 'Token inválido o expirado');
        }

        return view('auth.restablecerContrasena', compact('token'));
    }

    // Procesar restablecimiento de contraseña
    public function restablecerContrasena(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|string|min:6',
            'confirmarPassword' => 'required|same:password'
        ], [
            'password.required' => 'La nueva contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'confirmarPassword.required' => 'Debe confirmar la nueva contraseña',
            'confirmarPassword.same' => 'Las contraseñas no coinciden'
        ]);

        if ($validador->fails()) {
            return response()->json([
                'exito' => false,
                'errores' => $validador->errors()
            ]);
        }

        $usuario = Usuario::where('ResetToken', $request->token)
            ->where('ResetTokenExpires', '>', now())
            ->first();

        if (!$usuario) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'Token inválido o expirado'
            ]);
        }

        try {
            $usuario->Password = $request->password;
            $usuario->UltimaActualizacion = now();
            $usuario->limpiarToken();

            // Iniciar sesión automáticamente
            Auth::login($usuario);

            return response()->json([
                'exito' => true,
                'mensaje' => 'Contraseña restablecida exitosamente',
                'redireccion' => route('escritorio')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'Error al restablecer contraseña: ' . $e->getMessage()
            ]);
        }
    }

    // Activar cuenta
    public function activarCuenta($token)
    {
        $usuario = Usuario::where('ResetToken', $token)
            ->where('ResetTokenExpires', '>', now())
            ->first();

        if (!$usuario) {
            return redirect()->route('mostrar.login')
                ->with('error', 'Token de activación inválido o expirado');
        }

        $usuario->activarUsuario();

        return redirect()->route('mostrar.login')
            ->with('exito', 'Cuenta activada exitosamente. Ya puede iniciar sesión.');
    }

    // Cerrar sesión
    public function cerrarSesion()
    {
        Auth::logout();
        return redirect()->route('mostrar.login')
            ->with('exito', 'Sesión cerrada exitosamente');
    }

    // Escritorio principal
    public function escritorio()
    {
        return view('auth.escritorio');
    }

    // Métodos privados para envío de correos
    private function enviarCorreoActivacionV($usuario, $token)
    {
        $enlace = route('activar.cuenta', $token);

        Mail::raw("Hola {$usuario->Nombre},\n\nGracias por registrarte. Para activar tu cuenta, haz clic en el siguiente enlace:\n\n{$enlace}\n\nEste enlace expirará en 1 hora.\n\nSi no te registraste, ignora este correo.", function ($message) use ($usuario) {
            $message->to($usuario->Email, $usuario->Nombre)
                ->subject('Activar cuenta - Sistema de Autenticación');
        });
    }
    private function enviarCorreoActivacion($usuario, $token)
    {
        $enlace = route('activar.cuenta', $token);

        Mail::send('emails.activacion', ['usuario' => $usuario, 'enlace' => $enlace], function ($message) use ($usuario) {
            $message->to($usuario->Email, $usuario->Nombre)
                ->subject('Activar cuenta - Sistema de Seguridad Vehicular');
        });
    }
    private function enviarCorreoRecuperacion($usuario, $token)
    {
        $enlace = route('mostrar.restablecimiento', $token);

        Mail::send('emails.recuperacion', ['usuario' => $usuario, 'enlace' => $enlace], function ($message) use ($usuario) {
            $message->to($usuario->Email, $usuario->Nombre)
                ->subject('Recuperar contraseña - Sistema de Seguridad Vehicular');
        });
    }

    private function enviarCorreoRecuperacionV($usuario, $token)
    {
        $enlace = route('mostrar.restablecimiento', $token);

        Mail::raw("Hola {$usuario->Nombre},\n\nRecibimos una solicitud para restablecer tu contraseña. Haz clic en el siguiente enlace:\n\n{$enlace}\n\nEste enlace expirará en 1 hora.\n\nSi no solicitaste este cambio, ignora este correo.", function ($message) use ($usuario) {
            $message->to($usuario->Email, $usuario->Nombre)
                ->subject('Recuperar contraseña - Sistema de Autenticación');
        });
    }
}
