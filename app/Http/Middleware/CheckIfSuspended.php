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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isSuspended()) {
            $reason = Auth::user()->suspension_reason ?? 'No se proporcionó una razón';
            Auth::logout();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Tu cuenta ha sido suspendida. Razón: ' . $reason
                ]);
        }

        return $next($request);
    }
}

