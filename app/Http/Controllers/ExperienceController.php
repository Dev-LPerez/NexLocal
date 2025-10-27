<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class ExperienceController extends Controller
{
    /**
     * Muestra una lista de experiencias.
     */
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Experience::with('user')->latest();
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('location', 'like', '%' . $searchTerm . '%');
            });
        }
        $experiences = $query->get();
        $restaurants = [
            [
                'name' => 'El Pescador del Sinú',
                'location' => 'Montería',
                'rating' => 4.8,
                'reviews' => 284,
                'category' => 'Comida de Mar',
                'price_range' => '$$',
                'hours' => '11:00 AM - 10:00 PM',
                'specialties' => ['Bocachico Frito', 'Viuda de Pescado', 'Mote de Queso'],
                'image' => 'https://images.unsplash.com/photo-1552566626-52f8b828add9?q=80&w=800'
            ],
            [
                'name' => 'La Parrilla Cordobesa',
                'location' => 'Montería',
                'rating' => 4.9,
                'reviews' => 412,
                'category' => 'Carnes',
                'price_range' => '$$$',
                'hours' => '12:00 PM - 11:00 PM',
                'specialties' => ['Punta de Anca', 'Churrasco', 'Costillas BBQ'],
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=800'
            ],
            [
                'name' => 'Doña Emilia Fritanga',
                'location' => 'Cereté',
                'rating' => 4.7,
                'reviews' => 356,
                'category' => 'Frituras',
                'price_range' => '$',
                'hours' => '07:00 AM - 8:00 PM',
                'specialties' => ['Arepa de Huevo', 'Carimañola', 'Empanada'],
                'image' => 'https://images.unsplash.com/photo-1625938139282-0125d093b169?q=80&w=800'
            ],
        ];
        return view('welcome', compact('experiences', 'restaurants', 'searchTerm'));
    }

    /**
     * Muestra el formulario para crear una nueva experiencia.
     */
    public function create()
    {
        if (!Auth::check() || Auth::user()->role !== 'guide') {
            abort(403, 'Solo los guías pueden crear experiencias.');
        }
        $categories = ['Cultural', 'Gastronómica', 'Naturaleza', 'Aventura', 'Histórica', 'Rural'];
        return view('experiences.create', compact('categories'));
    }

    /**
     * Almacena una nueva experiencia.
     */
    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'guide') {
            abort(403, 'Solo los guías pueden crear experiencias.');
        }
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $validatedData['user_id'] = Auth::id();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('experiences', 'public');
            $validatedData['image_path'] = $path;
        }
        Experience::create($validatedData);
        return redirect()->route('dashboard')->with('success', '¡Experiencia creada con éxito!');
    }

    /**
     * Muestra el detalle de una experiencia específica.
     */
    public function show(Experience $experience)
    {
        $experience->load(['user', 'availabilitySlots']);
        return view('experiences.show', compact('experience'));
    }

    /**
     * Muestra el formulario para editar una experiencia existente.
     */
    public function edit(Experience $experience)
    {
        if (!Auth::check() || Auth::user()->role !== 'guide' || Auth::id() !== $experience->user_id) {
            abort(403, 'No tienes permiso para editar esta experiencia.');
        }

        $categories = ['Cultural', 'Gastronómica', 'Naturaleza', 'Aventura', 'Histórica', 'Rural'];
        return view('experiences.edit', compact('experience', 'categories'));
    }

    /**
     * Actualiza una experiencia existente.
     */
    public function update(Request $request, Experience $experience)
    {
        if (!Auth::check() || Auth::user()->role !== 'guide' || Auth::id() !== $experience->user_id) {
            abort(403, 'No tienes permiso para actualizar esta experiencia.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($experience->image_path) {
                Storage::disk('public')->delete($experience->image_path);
            }
            $path = $request->file('image')->store('experiences', 'public');
            $validatedData['image_path'] = $path;
        }

        $experience->update($validatedData);
        return redirect()->route('dashboard')->with('success', '¡Experiencia actualizada con éxito!');
    }

    /**
     * Elimina una experiencia.
     */
    public function destroy(Experience $experience)
    {
        if (!Auth::check() || Auth::user()->role !== 'guide' || Auth::id() !== $experience->user_id) {
            abort(403, 'No tienes permiso para eliminar esta experiencia.');
        }

        // Eliminar imagen si existe
        if ($experience->image_path) {
            Storage::disk('public')->delete($experience->image_path);
        }

        $experience->delete();
        return redirect()->route('dashboard')->with('success', 'Experiencia eliminada correctamente.');
    }
}
