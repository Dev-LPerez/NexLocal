<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AccountSuspendedController extends Controller
{
    /**
     * Mostrar la página de cuenta suspendida.
     */
    public function index(): View|RedirectResponse
    {
        // Si el usuario no está autenticado o no está suspendido, redirigir al dashboard
        if (!auth()->check() || !auth()->user()->isSuspended()) {
            return redirect()->route('dashboard');
        }

        $user = auth()->user();

        return view('account.suspended', [
            'user' => $user,
            'reason' => $user->suspension_reason ?? 'No se proporcionó razón',
            'suspended_at' => $user->suspended_at,
        ]);
    }
}

