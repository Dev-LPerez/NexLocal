<?php
// Archivo: app/Http/Middleware/IsAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificamos si el usuario está autenticado y si su rol es 'admin'
        // IMPORTANTE: Asegúrate de que tu base de datos permita el valor 'admin' en la columna 'role'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Si no es administrador, denegamos el acceso con un error 403
        abort(403, 'ACCESO DENEGADO: No tienes permisos de administrador para ver esta página.');
    }
}
