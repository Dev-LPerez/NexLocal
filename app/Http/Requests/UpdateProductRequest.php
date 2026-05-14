<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProductRequest extends FormRequest
{
    /**
     * Solo el dueño del negocio puede actualizar sus propios productos.
     */
    public function authorize(): bool
    {
        $product = $this->route('product');
        $business = Auth::user()->localBusiness;

        if (!$business || !$product) {
            return false;
        }

        return $product->local_business_id === $business->id;
    }

    /**
     * Reglas de validación para actualizar un producto.
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'sometimes|required|numeric|min:0',
            'prod_images' => 'nullable|array|max:10',
            'prod_images.*' => 'file|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'is_available' => 'sometimes|boolean',
            'stock' => 'nullable|integer|min:0',
            'product_category' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio no puede ser negativo.',
            'prod_images.*.mimes' => 'Solo se permiten imágenes (jpeg, png, jpg, gif, webp, svg).',
            'prod_images.*.max' => 'Cada imagen no puede superar los 10 MB.',
            'prod_images.max' => 'Puedes subir un máximo de 10 imágenes.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
        ];
    }
}