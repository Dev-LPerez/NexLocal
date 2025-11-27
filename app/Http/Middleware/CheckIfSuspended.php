<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckIfSuspended
{
    /**
     * Handle an incoming request.
     *
     * Verifica si el usuario está suspendido y bloquea acciones críticas
     * permitiéndole ver información pero no realizar acciones.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isSuspended()) {
            // Rutas permitidas incluso si está suspendido
            $allowedRoutes = [
                'account.suspended',
                'logout',
                'profile.edit',
                'profile.show',
            ];

            // Rutas permitidas que empiezan con ciertos prefijos
            $currentRoute = $request->route()->getName();

            // Si no es una ruta permitida, redirigir a página de suspensión
            if (!in_array($currentRoute, $allowedRoutes) &&
                !str_starts_with($currentRoute, 'password.') &&
                $currentRoute !== null) {

                return redirect()->route('account.suspended')
                    ->with('warning', 'Tu cuenta está suspendida. Contacta con soporte para más información.');
            }
        }

        return $next($request);
    }
}

