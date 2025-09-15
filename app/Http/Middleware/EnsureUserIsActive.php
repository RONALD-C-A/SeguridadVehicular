<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Usuario; // Agregar esta línea

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Usuario|null $usuario */
        $usuario = Auth::user();
        
        if (Auth::check() && !$usuario->estaActivo()) {
            Auth::logout();
            return redirect()->route('mostrar.login')
                           ->with('error', 'Su cuenta ha sido desactivada. Contacte al administrador.');
        }

        return $next($request);
    }
}