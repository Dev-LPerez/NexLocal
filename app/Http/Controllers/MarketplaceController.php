<?php

namespace App\Http\Controllers;

use App\Models\LocalBusiness;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Devuelve una lista paginada de todos los negocios.
     * Ideal para el feed "Descubre tu ciudad".
     */
    public function index()
    {
        // Se puede filtrar por 'status' => 'active' si es necesario
        $businesses = LocalBusiness::paginate(15);

        return response()->json([
            'success' => true,
            'data' => $businesses
        ]);
    }

    /**
     * Devuelve los detalles de un negocio en específico,
     * incluyendo sus productos disponibles.
     */
    public function show($id)
    {
        // Cargar el negocio junto con sus productos
        $business = LocalBusiness::with('products')->find($id);

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'Emprendimiento no encontrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $business
        ]);
    }
}
