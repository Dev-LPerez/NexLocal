<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LocalBusiness;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LocalBusinessController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'business_type' => 'required|string',
            'category' => 'required|string',
            'price_range' => 'nullable|integer',
            'capacity' => 'nullable|integer',
            'services' => 'nullable|array'
        ]);

        $user = Auth::user();
        $business = $user->localBusiness;

        $data = $request->only(['name', 'description', 'business_type', 'category', 'price_range', 'capacity', 'services']);

        if ($business) {
            $business->update($data);
        } else {
            $data['user_id'] = $user->id;
            $data['status'] = 'active';
            $business = LocalBusiness::create($data);
        }

        return back()->with('success', 'Información general guardada exitosamente.');
    }

    public function updateImages(Request $request)
    {
        $request->validate([
            'cover_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'gallery_images.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,svg|max:10240'
        ]);

        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return back()->with('error', 'Primero debes registrar la información general de tu negocio.');
        }

        if ($request->hasFile('cover_image')) {
            if ($business->cover_image_path) {
                Storage::disk('public')->delete($business->cover_image_path);
            }
            $business->cover_image_path = $request->file('cover_image')->store('businesses', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $gallery = $business->gallery_images ?? [];
            foreach ($request->file('gallery_images') as $image) {
                if (count($gallery) < 10) {
                    $gallery[] = $image->store('businesses/gallery', 'public');
                }
            }
            $business->gallery_images = $gallery;
        }

        $business->save();

        return back()->with('success', 'Imágenes guardadas exitosamente.');
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'phone' => 'nullable|string',
            'email' => 'nullable|email'
        ]);

        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return back()->with('error', 'Primero debes registrar la información general de tu negocio.');
        }

        $business->update([
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Ubicación y contacto guardados exitosamente.');
    }

    public function deleteGalleryImage(Request $request)
    {
        $request->validate([
            'image' => 'required|string'
        ]);

        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business || !is_array($business->gallery_images)) {
            return back()->with('error', 'No se encontró la imagen.');
        }

        $gallery = $business->gallery_images;
        
        if (($key = array_search($request->image, $gallery)) !== false) {
            unset($gallery[$key]);
            Storage::disk('public')->delete($request->image);
            $business->gallery_images = array_values($gallery);
            $business->save();
            return back()->with('success', 'Imagen eliminada de la galería.');
        }

        return back()->with('error', 'La imagen no existe en tu galería.');
    }
    public function customize(Request $request)
    {
        $request->validate([
            'banner_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'theme_colors' => 'nullable|array',
            'social_links' => 'nullable|array',
            'operating_hours' => 'nullable|array',
            'payment_methods' => 'nullable|array',
            'welcome_message' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return back()->with('error', 'Primero debes registrar la información general de tu negocio.');
        }

        $data = $request->only(['theme_colors', 'social_links', 'operating_hours', 'payment_methods', 'welcome_message']);

        if ($request->hasFile('banner_image')) {
            if ($business->banner_image_path) {
                Storage::disk('public')->delete($business->banner_image_path);
            }
            $data['banner_image_path'] = $request->file('banner_image')->store('businesses/banners', 'public');
        }

        // Clean up checkboxes array for operating_hours to ensure unchecked days are still processed if needed
        // but simple assignment works because un-submitted days will just be absent in the input
        
        $business->update($data);

        return back()->with('success', 'Personalización de tienda guardada exitosamente.');
    }
}
