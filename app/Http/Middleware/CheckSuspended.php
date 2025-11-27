<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspended
{
    /**
     * Handle an incoming request.
     *
     * Verifica si el usuario autenticado está suspendido y bloquea acciones críticas.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isSuspended()) {
            // Permitir solo rutas de lectura y logout
            $allowedRoutes = [
                'dashboard',
                'profile.edit',
                'profile.update',
                'logout',
                'account.suspended',
            ];

            // Si la ruta actual no está en las permitidas, redirigir a página de suspensión
            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('account.suspended');
            }
        }

        return $next($request);
    }
}

