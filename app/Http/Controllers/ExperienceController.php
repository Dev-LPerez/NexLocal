<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Obtiene las experiencias de la base de datos
        $experiences = Experience::latest()->get();

        // 2. Prepara los datos de ejemplo para los restaurantes
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
            // Puedes agregar los otros 3 restaurantes aquí si lo deseas
        ];

        // 3. Devuelve la vista 'welcome' y le pasa ambas variables
        return view('welcome', compact('experiences', 'restaurants'));
    }

    // ... El resto de los métodos (create, store, etc.) permanecen igual
    public function create()
    {
        return view('experiences.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:100',
        ]);
        $validatedData['user_id'] = Auth::id();
        Experience::create($validatedData);
        return redirect('/')->with('success', '¡Experiencia creada con éxito!');
    }

    public function show(string $id){}
    public function edit(string $id){}
    public function update(Request $request, string $id){}
    public function destroy(string $id){}
}