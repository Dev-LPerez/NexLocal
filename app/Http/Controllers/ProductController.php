<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the products for the authenticated user's business.
     */
    public function index()
    {
        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un emprendimiento registrado.'
            ], 404);
        }

        $products = $business->products;

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'Debes registrar un emprendimiento antes de crear productos.'
            ], 403);
        }

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product = $business->products()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Producto creado exitosamente.',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un emprendimiento registrado.'
            ], 404);
        }

        $product = $business->products()->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un emprendimiento registrado.'
            ], 404);
        }

        $product = $business->products()->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.'
            ], 404);
        }

        $data = $request->validated();
        if ($request->hasFile('image')) {
            // Opcional: Eliminar la imagen anterior usando Storage::disk('public')->delete($product->image_path)
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado exitosamente.',
            'data' => $product
        ]);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un emprendimiento registrado.'
            ], 404);
        }

        $product = $business->products()->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado exitosamente.'
        ]);
    }
}
