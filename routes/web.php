<?php

use App\Http\Controllers\ControladorAutenticacion;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Rutas Web
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas web para tu aplicación. Estas
| rutas son cargadas por RouteServiceProvider dentro de un grupo que
| contiene el middleware web. ¡Ahora crea algo grandioso!
|
*/

// Ruta principal - redirige al login
Route::get('/', function () {
    return redirect()->route('mostrar.login');
});

// Grupo de rutas para invitados (no autenticados)
Route::middleware('guest')->group(function () {
    // Rutas para mostrar formularios
    Route::get('/iniciar-sesion', [ControladorAutenticacion::class, 'mostrarLogin'])
         ->name('mostrar.login');
    
    Route::get('/registro', [ControladorAutenticacion::class, 'mostrarRegistro'])
         ->name('mostrar.registro');
    
    Route::get('/recuperar-contrasena', [ControladorAutenticacion::class, 'mostrarRecuperacion'])
         ->name('mostrar.recuperacion');
    
    Route::get('/restablecer-contrasena/{token}', [ControladorAutenticacion::class, 'mostrarRestablecimiento'])
         ->name('mostrar.restablecimiento');

    // Rutas para procesar formularios
    Route::post('/iniciar-sesion', [ControladorAutenticacion::class, 'iniciarSesion'])
         ->name('iniciar.sesion');
    
    Route::post('/registro', [ControladorAutenticacion::class, 'registrar'])
         ->name('registrar');
    
    Route::post('/recuperar-contrasena', [ControladorAutenticacion::class, 'enviarRecuperacion'])
         ->name('enviar.recuperacion');
    
    Route::post('/restablecer-contrasena', [ControladorAutenticacion::class, 'restablecerContrasena'])
         ->name('restablecer.contrasena');

    // Ruta para activar cuenta
    Route::get('/activar-cuenta/{token}', [ControladorAutenticacion::class, 'activarCuenta'])
         ->name('activar.cuenta');
});

// Grupo de rutas para usuarios autenticados
Route::middleware('auth')->group(function () {
    // Escritorio principal
    Route::get('/escritorio', [ControladorAutenticacion::class, 'escritorio'])
         ->name('escritorio');
    
    // Cerrar sesión
    Route::post('/cerrar-sesion', [ControladorAutenticacion::class, 'cerrarSesion'])
         ->name('cerrar.sesion');
    
    // Ruta GET alternativa para cerrar sesión (para enlaces directos)
    Route::get('/cerrar-sesion', [ControladorAutenticacion::class, 'cerrarSesion'])
         ->name('cerrar.sesion.get');
     
});

// Rutas adicionales para manejo de errores de autenticación
Route::fallback(function () {
    if (Auth::check()) {
        return redirect()->route('escritorio');
    }
    return redirect()->route('mostrar.login')->with('error', 'Página no encontrada');
});