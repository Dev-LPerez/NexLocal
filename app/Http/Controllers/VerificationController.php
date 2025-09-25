<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Muestra el formulario para subir el documento de identidad.
     */
    public function create()
    {
        return view('auth.verify-identity');
    }

    /**
     * Guarda el documento de identidad subido.
     */
    public function store(Request $request)
    {
        // 1. Validar la petición
        $request->validate([
            'identity_document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        // 2. Obtener el usuario autenticado
        $user = Auth::user();

        // 3. Guardar el archivo en una carpeta privada
        // El nombre del archivo será el ID del usuario para evitar colisiones
        $path = $request->file('identity_document')->storeAs(
            'identity-documents', // Carpeta
            $user->id . '.' . $request->file('identity_document')->extension(), // Nombre del archivo
            'private' // Disco de almacenamiento (configurado en filesystems.php)
        );

        // 4. Actualizar al usuario con la ruta del archivo
        $user->identity_document_path = $path;
        $user->save();

        // 5. Redirigir de vuelta con un mensaje de éxito
        return back()->with('status', '¡Documento subido con éxito! Lo revisaremos pronto.');
    }
}