<?php // Filepath: app/Http/Controllers/ExperienceController.php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\AvailabilitySlot; // <-- Asegúrate de importar esto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr; // <-- Asegúrate de importar esto
use Illuminate\Support\Carbon; // <-- Asegúrate de importar esto
use Illuminate\Support\Facades\DB; // <-- AÑADIR

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

        // CORRECCIÓN: Usar paginate() para que funcione con la búsqueda
        $experiences = $query->paginate(9)->withQueryString(); // 9 por página y mantiene el ?search=...

        // Datos de restaurantes (sin cambios)
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

        $experience = new Experience();

        return view('experiences.create', compact('categories', 'experience'));
    }

    /**
     * Almacena una nueva experiencia.
     */
    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'guide') {
            abort(403, 'Solo los guías pueden crear experiencias.');
        }

        // --- Validación Actualizada ---
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'includes' => 'nullable|string',
            'not_includes' => 'nullable|string',

            // --- INICIO: LÓGICA FALTANTE ---
            // Validación para los slots
            'slots' => 'nullable|array',
            'slots.*.start_time' => 'required|date|after_or_equal:now',
            'slots.*.max_slots' => 'required|integer|min:1',
            // --- FIN: LÓGICA FALTANTE ---
        ]);

        $validatedData['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('experiences', 'public');
            $validatedData['image_path'] = $path;
        }

        $validatedData['includes'] = $this->convertTextareaToArray($validatedData['includes'] ?? null);
        $validatedData['not_includes'] = $this->convertTextareaToArray($validatedData['not_includes'] ?? null);

        // --- INICIO: LÓGICA FALTANTE ---
        // Extrae los slots del array validado antes de crear la experiencia
        $slotsData = Arr::pull($validatedData, 'slots');

        $experience = Experience::create($validatedData);

        // Guardar los slots de disponibilidad si existen
        if (!empty($slotsData)) {
            foreach ($slotsData as $slotData) {
                $experience->availabilitySlots()->create([
                    'start_time' => Carbon::parse($slotData['start_time']),
                    'max_slots' => $slotData['max_slots'],
                    'available_spots' => $slotData['max_slots'], // <-- Importante: inicializar cupos disponibles
                ]);
            }
        }
        // --- FIN: LÓGICA FALTANTE ---

        return redirect()->route('dashboard')->with('success', '¡Experiencia creada con éxito!');
    }

    /**
     * Muestra el detalle de una experiencia específica.
     */
    public function show(Experience $experience)
    {
        // --- INICIO: MODIFICACIÓN PARA RESEÑAS ---
        $experience->load([
            'user',
            'availabilitySlots' => function ($query) {
                $query->where('start_time', '>', now())
                      ->where('available_spots', '>', 0); // Solo slots futuros Y con cupos
            },
            // Cargar reseñas y el usuario que la escribió
            'reviews' => function ($query) {
                $query->with('user')->latest();
            }
        ]);

        // Calcular promedio de reseñas
        // Usamos avg() sobre la colección cargada o con una query para eficiencia
        $averageRating = $experience->reviews->avg('rating');
        $reviewCount = $experience->reviews->count();
        // --- FIN: MODIFICACIÓN PARA RESEÑAS ---

        // Lógica de agrupación de slots (sin cambios)
        $groupedSlots = $experience->availabilitySlots
            ->groupBy(function ($slot) {
                return $slot->start_time->format('Y-m-d');
            });

        // Pasamos los nuevos datos a la vista
        return view('experiences.show', compact('experience', 'groupedSlots', 'averageRating', 'reviewCount'));
    }


    /**
     * Muestra el formulario para editar una experiencia existente.
     */
    public function edit(Experience $experience)
    {
        if (!Auth::check() || Auth::user()->role !== 'guide' || Auth::id() !== $experience->user_id) {
            abort(403, 'No tienes permiso para editar esta experiencia.');
        }

        $experience->load('availabilitySlots');

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

        // --- Validación Actualizada ---
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'includes' => 'nullable|string',
            'not_includes' => 'nullable|string',

            // --- INICIO: LÓGICA FALTANTE ---
            'slots' => 'nullable|array',
            'slots.*.id' => 'nullable|exists:availability_slots,id',
            'slots.*.start_time' => 'required|date',
            'slots.*.max_slots' => 'required|integer|min:1',
            // --- FIN: LÓGICA FALTANTE ---
        ]);

        if ($request->hasFile('image')) {
            if ($experience->image_path) {
                Storage::disk('public')->delete($experience->image_path);
            }
            $path = $request->file('image')->store('experiences', 'public');
            $validatedData['image_path'] = $path;
        }

        $validatedData['includes'] = $this->convertTextareaToArray($validatedData['includes'] ?? null);
        $validatedData['not_includes'] = $this->convertTextareaToArray($validatedData['not_includes'] ?? null);

        // --- INICIO: LÓGICA FALTANTE ---
        $slotsData = Arr::pull($validatedData, 'slots');

        $experience->update($validatedData);

        // Sincronizar los slots de disponibilidad
        if (!empty($slotsData)) {
            $existingSlotIds = $experience->availabilitySlots->pluck('id')->all();
            $incomingSlotIds = [];

            foreach ($slotsData as $slotData) {
                $startTime = Carbon::parse($slotData['start_time']);
                $maxSlots = $slotData['max_slots'];

                // Si el slot tiene un ID, es una actualización
                if (!empty($slotData['id'])) {
                    $slotId = $slotData['id'];
                    $incomingSlotIds[] = $slotId;

                    $slot = AvailabilitySlot::where('id', $slotId)
                        ->where('experience_id', $experience->id)
                        ->first();
                    if ($slot) {
                        // Recalcular cupos disponibles si max_slots cambió
                        $bookedCount = $slot->bookings()->whereIn('status', ['pending', 'confirmed'])->count();
                        $newAvailable = $maxSlots - $bookedCount;

                        $slot->update([
                            'start_time' => $startTime,
                            'max_slots' => $maxSlots,
                            'available_spots' => $newAvailable > 0 ? $newAvailable : 0,
                        ]);
                    }
                }
                // Si no tiene ID, es un nuevo slot
                else {
                    $newSlot = $experience->availabilitySlots()->create([
                        'start_time' => $startTime,
                        'max_slots' => $maxSlots,
                        'available_spots' => $maxSlots, // Nuevos slots tienen todos los cupos
                    ]);
                    $incomingSlotIds[] = $newSlot->id;
                }
            }

            // Eliminar slots que ya no están en la lista
            $slotsToDelete = array_diff($existingSlotIds, $incomingSlotIds);
            if (!empty($slotsToDelete)) {
                // Solo eliminar slots que no tengan reservas
                $slotsWithBookings = AvailabilitySlot::whereIn('id', $slotsToDelete)
                    ->has('bookings')
                    ->pluck('id')->all();

                $slotsSafeToDelete = array_diff($slotsToDelete, $slotsWithBookings);
                AvailabilitySlot::destroy($slotsSafeToDelete);

                if (count($slotsWithBookings) > 0) {
                    return redirect()->route('dashboard')->with('warning', 'Experiencia actualizada, pero no se eliminaron algunos horarios porque ya tienen reservas.');
                }
            }

        }
        // Si el array de slots está vacío, eliminamos todos los slots (que no tengan reservas)
        else {
            $slotsWithBookings = $experience->availabilitySlots()->has('bookings')->exists();
            if ($slotsWithBookings) {
                return redirect()->route('dashboard')->with('warning', 'Experiencia actualizada, pero no se eliminaron los horarios porque ya tienen reservas.');
            }
            $experience->availabilitySlots()->delete();
        }
        // --- FIN: LÓGICA FALTANTE ---

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

        $hasFutureBookings = $experience->availabilitySlots()
            ->where('start_time', '>', now())
            ->whereHas('bookings', function ($query) {
                $query->whereIn('status', ['pending', 'confirmed']);
            })
            ->exists();

        if ($hasFutureBookings) {
            return redirect()->route('dashboard')->with('error', 'No puedes eliminar una experiencia que tiene reservas futuras activas.');
        }

        if ($experience->image_path) {
            Storage::disk('public')->delete($experience->image_path);
        }

        $experience->delete();

        return redirect()->route('dashboard')->with('success', 'Experiencia eliminada correctamente.');
    }

    /**
     * Función helper para convertir texto de textarea a array.
     */
    private function convertTextareaToArray($text)
    {
        if (empty($text)) {
            return [];
        }
        return array_filter(array_map('trim', explode("\n", $text)));
    }
}

