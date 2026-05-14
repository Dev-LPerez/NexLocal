<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * (Propietario) Crear un nuevo producto.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'product_category' => 'nullable|string|max:100',
            'prod_images' => 'nullable|array|max:10',
            'prod_images.*' => 'file|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
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
            'stock' => $request->stock,               // null = ilimitado
            'product_category' => $request->product_category,
            'is_available' => true,
        ]);

        if ($request->hasFile('prod_images')) {
            $files = $request->file('prod_images');
            if (count($files) > 0) {
                $product->image_path = $files[0]->store('products', 'public');
                $product->save();
            }
        }

        return back()->with('success', 'Producto agregado exitosamente.');
    }

    /**
     * (Propietario) Actualizar un producto existente.
     *
     * La autorización (ownership) ya la verifica UpdateProductRequest::authorize().
     * Maneja: datos básicos, reemplazo de imagen y gestión de stock.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        // --- 1. Campos de texto y precio ---
        $dataToUpdate = [];

        if (isset($validated['name']))
            $dataToUpdate['name'] = $validated['name'];
        if (isset($validated['description']))
            $dataToUpdate['description'] = $validated['description'];
        if (isset($validated['price']))
            $dataToUpdate['price'] = $validated['price'];
        if (isset($validated['product_category']))
            $dataToUpdate['product_category'] = $validated['product_category'];

        // --- 2. Stock ---
        // Si viene 'stock' en el request (incluso si es null) lo actualizamos.
        // null significa "sin límite de stock".
        if (array_key_exists('stock', $validated)) {
            $newStock = $validated['stock'];
            $dataToUpdate['stock'] = $newStock;

            // Si el stock llega a 0 y el producto estaba disponible, lo marcamos no disponible.
            if ($newStock !== null && $newStock <= 0) {
                $dataToUpdate['is_available'] = false;
            }

            // Si se repone stock (> 0) y el producto estaba fuera de stock, lo reactivamos.
            if ($newStock === null || $newStock > 0) {
                // Solo forzamos is_available a true si venía desactivado exclusivamente por stock 0.
                // Si el propietario lo desactivó manualmente, respetamos su decisión (ver paso 3).
                if (!isset($dataToUpdate['is_available'])) {
                    $dataToUpdate['is_available'] = true;
                }
            }
        }

        // --- 3. Disponibilidad manual (toggle explícito del propietario) ---
        // Tiene precedencia sobre la lógica automática de stock de arriba.
        if (isset($validated['is_available'])) {
            $dataToUpdate['is_available'] = $validated['is_available'];
        }

        // --- 4. Imagen ---
        if ($request->hasFile('prod_images')) {
            $files = $request->file('prod_images');

            if (count($files) > 0) {
                // Borrar la imagen anterior para no dejar huérfanos en storage
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }

                // Guardar la primera imagen enviada como portada
                $dataToUpdate['image_path'] = $files[0]->store('products', 'public');
            }
        }

        $product->update($dataToUpdate);

        return back()->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * (Propietario) Alternar disponibilidad sin tocar el stock.
     */
    public function toggleAvailability(Product $product)
    {
        if ($product->local_business_id !== Auth::user()->localBusiness->id) {
            return back()->with('error', 'Acceso denegado.');
        }

        $product->is_available = !$product->is_available;
        $product->save();

        return back()->with('success', 'Disponibilidad del producto actualizada.');
    }

    /**
     * (Propietario) Eliminar un producto.
     */
    public function destroy(Product $product)
    {
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