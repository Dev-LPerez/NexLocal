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
    public function index(Request $request)
    {
        $query = LocalBusiness::with(['products' => function ($q) {
            $q->where('is_available', true);
        }])->withCount(['products' => function ($q) {
            $q->where('is_available', true);
        }]);

        // Filtro por categoría
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Búsqueda por nombre
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $businesses = $query->paginate(15);

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
