<?php

namespace App\Http\Controllers;

use App\Models\LocalBusiness;
use App\Http\Requests\StoreLocalBusinessRequest;
use App\Http\Requests\UpdateLocalBusinessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocalBusinessController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocalBusinessRequest $request)
    {
        $user = Auth::user();

        // Verificar si el usuario ya tiene un negocio
        if ($user->localBusiness) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario ya tiene un emprendimiento registrado.'
            ], 400);
        }

        $data = $request->validated();
        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $request->file('cover_image')->store('businesses', 'public');
        }

        // Crear el negocio
        $business = $user->localBusiness()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Emprendimiento creado exitosamente.',
            'data' => $business
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un emprendimiento registrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $business
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocalBusinessRequest $request)
    {
        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un emprendimiento registrado.'
            ], 404);
        }

        $data = $request->validated();
        if ($request->hasFile('cover_image')) {
            // Opcional: Eliminar la imagen anterior usando Storage::disk('public')->delete($business->cover_image_path)
            $data['cover_image_path'] = $request->file('cover_image')->store('businesses', 'public');
        }

        $business->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Emprendimiento actualizado exitosamente.',
            'data' => $business
        ]);
    }
}
