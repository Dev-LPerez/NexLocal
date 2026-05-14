<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\NotificationHelper;

class VerificationController extends Controller
{
    /**
     * Muestra el formulario para subir el documento de identidad.
     */
    public function create()
    {
        $user = Auth::user();

        // Redirigir si no es guía ni dueño de negocio
        if (!in_array($user->role, ['guide', 'owner'])) {
            return redirect()->route('home')->with('error', 'Solo los guías y emprendedores necesitan verificar su identidad.');
        }

        return view('auth.verify-identity');
    }

    /**
     * Guarda el documento de identidad subido.
     */
    public function store(Request $request)
    {
        // 1. Validar la petición
        $request->validate([
            'identity_document_front' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB
            'identity_document_back' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB
        ], [
            'identity_document_front.required' => 'El documento frontal es obligatorio.',
            'identity_document_front.mimes' => 'El documento frontal debe ser un archivo PDF, JPG, JPEG o PNG.',
            'identity_document_front.max' => 'El documento frontal no debe pesar más de 5MB.',
            'identity_document_back.required' => 'El documento trasero es obligatorio.',
            'identity_document_back.mimes' => 'El documento trasero debe ser un archivo PDF, JPG, JPEG o PNG.',
            'identity_document_back.max' => 'El documento trasero no debe pesar más de 5MB.',
        ]);

        // 2. Obtener el usuario autenticado
        $user = Auth::user();

        // 3. Guardar el archivo frontal
        $frontPath = $request->file('identity_document_front')->storeAs(
            'identity-documents',
            $user->id . '_front.' . $request->file('identity_document_front')->extension(),
            'private'
        );

        // 4. Guardar el archivo trasero
        $backPath = $request->file('identity_document_back')->storeAs(
            'identity-documents',
            $user->id . '_back.' . $request->file('identity_document_back')->extension(),
            'private'
        );

        // 5. Actualizar al usuario con las rutas y estado
        $user->identity_document_path = $frontPath;
        $user->identity_document_back_path = $backPath;
        $user->verification_status = 'pending';
        $user->rejection_reason = null; // Limpiar razón de rechazo si existía
        $user->save();

        // 6. Notificar a todos los administradores
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationHelper::create(
                $admin->id,
                '📋 Nueva Solicitud de Verificación',
                "El usuario {$user->name} ({$user->role}) ha enviado sus documentos de identidad para verificación.",
                'admin_verification',
                route('admin.verification')
            );
        }

        // 7. Redirigir con mensaje de éxito
        return redirect()->route('verification.create')->with('status', '¡Documentos enviados con éxito! Un administrador revisará tu solicitud pronto.');
    }
}

