<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'prod_images.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,svg|max:10240'
        ]);

        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return back()->with('error', 'Primero debes registrar tu negocio antes de añadir productos.');
        }

        $product = $business->products()->create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        if ($request->hasFile('prod_images')) {
            // Take the first image as cover
            $files = $request->file('prod_images');
            if (count($files) > 0) {
                $product->image_path = $files[0]->store('products', 'public');
                $product->save();
            }
        }

        return back()->with('success', 'Producto agregado exitosamente.');
    }

    public function update(Request $request, Product $product)
    {
        // Simple update stub
        $product->update($request->only(['name', 'description', 'price']));
        return back()->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        // Check owner
        if ($product->local_business_id !== Auth::user()->localBusiness->id) {
            return back()->with('error', 'Acceso denegado.');
        }

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return back()->with('success', 'Producto eliminado.');
    }
}
