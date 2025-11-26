<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureGuideIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Si no es guía, dejar pasar (otros middlewares manejan la autorización)
        if (!$user || $user->role !== 'guide') {
            return $next($request);
        }

        // Si es guía pero no está verificado, redirigir a verificación
        if (!$user->isVerifiedGuide()) {
            return redirect()->route('verification.create')
                ->with('error', 'Debes verificar tu identidad antes de crear experiencias.');
        }

        return $next($request);
    }
}

