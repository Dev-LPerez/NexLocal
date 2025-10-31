<?php
// la ruta del el archivo del codigo: app/Http/Controllers/ExperienceController.php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\AvailabilitySlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB

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

        $experiences = $query->paginate(9)->withQueryString();

        // Datos de restaurantes (simulados)
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
            // ... (otros restaurantes)
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
            'slots' => 'nullable|array',
            'slots.*.start_time' => 'required|date|after_or_equal:now',
            'slots.*.max_slots' => 'required|integer|min:1',

            // --- VALIDACIÓN AÑADIDA ---
            'meeting_point_name' => 'nullable|string|max:255',
            'meeting_point_lat' => 'nullable|numeric|between:-90,90',
            'meeting_point_lng' => 'nullable|numeric|between:-180,180',
            // --- FIN DE VALIDACIÓN AÑADIDA ---
        ]);

        $validatedData['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('experiences', 'public');
            $validatedData['image_path'] = $path;
        }

        $validatedData['includes'] = $this->convertTextareaToArray($validatedData['includes'] ?? null);
        $validatedData['not_includes'] = $this->convertTextareaToArray($validatedData['not_includes'] ?? null);

        $slotsData = Arr::pull($validatedData, 'slots');

        // Usar transacción para asegurar consistencia
        DB::transaction(function () use ($validatedData, $slotsData) {
            $experience = Experience::create($validatedData); // $validatedData ya incluye los campos del mapa

            if (!empty($slotsData)) {
                foreach ($slotsData as $slotData) {
                    $experience->availabilitySlots()->create([
                        'start_time' => Carbon::parse($slotData['start_time']),
                        'max_slots' => $slotData['max_slots'],
                        'available_spots' => $slotData['max_slots'],
                    ]);
                }
            }
        });

        return redirect()->route('dashboard')->with('success', '¡Experiencia creada con éxito!');
    }

    /**
     * Muestra el detalle de una experiencia específica.
     */
    public function show(Experience $experience)
    {
        $experience->load([
            'user',
            'availabilitySlots' => function ($query) {
                $query->where('start_time', '>', now())
                    ->where('available_spots', '>', 0);
            },
            'reviews' => function ($query) {
                $query->with('user')->latest();
            }
        ]);

        $averageRating = $experience->reviews->avg('rating');
        $reviewCount = $experience->reviews->count();

        $groupedSlots = $experience->availabilitySlots
            ->groupBy(function ($slot) {
                return $slot->start_time->format('Y-m-d');
            });

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
            'slots' => 'nullable|array',
            'slots.*.id' => 'nullable|exists:availability_slots,id',
            'slots.*.start_time' => 'required|date',
            'slots.*.max_slots' => 'required|integer|min:1',

            // --- VALIDACIÓN AÑADIDA ---
            'meeting_point_name' => 'nullable|string|max:255',
            'meeting_point_lat' => 'nullable|numeric|between:-90,90',
            'meeting_point_lng' => 'nullable|numeric|between:-180,180',
            // --- FIN DE VALIDACIÓN AÑADIDA ---
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

        $slotsData = Arr::pull($validatedData, 'slots');

        // Usar transacción para asegurar consistencia
        DB::transaction(function () use ($experience, $validatedData, $slotsData) {
            $experience->update($validatedData); // $validatedData ya incluye los campos del mapa

            $existingSlotIds = $experience->availabilitySlots->pluck('id')->all();
            $incomingSlotIds = [];
            $slotsWithBookings = [];

            if (!empty($slotsData)) {
                foreach ($slotsData as $slotData) {
                    $startTime = Carbon::parse($slotData['start_time']);
                    $maxSlots = $slotData['max_slots'];

                    if (!empty($slotData['id'])) {
                        $slotId = $slotData['id'];
                        $incomingSlotIds[] = $slotId;

                        $slot = AvailabilitySlot::where('id', $slotId)
                            ->where('experience_id', $experience->id)
                            ->first();

                        if ($slot) {
                            $bookedCount = $slot->bookings()->whereIn('status', ['pending', 'confirmed', 'in_progress'])->count();
                            $newAvailable = $maxSlots - $bookedCount;

                            $slot->update([
                                'start_time' => $startTime,
                                'max_slots' => $maxSlots,
                                'available_spots' => $newAvailable > 0 ? $newAvailable : 0,
                            ]);
                        }
                    } else {
                        $newSlot = $experience->availabilitySlots()->create([
                            'start_time' => $startTime,
                            'max_slots' => $maxSlots,
                            'available_spots' => $maxSlots,
                        ]);
                        $incomingSlotIds[] = $newSlot->id;
                    }
                }
            }

            $slotsToDelete = array_diff($existingSlotIds, $incomingSlotIds);
            if (!empty($slotsToDelete)) {
                $slotsWithBookingsQuery = AvailabilitySlot::whereIn('id', $slotsToDelete)
                    ->whereHas('bookings', function ($q) {
                        $q->whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed']);
                    });

                $slotsWithBookings = $slotsWithBookingsQuery->pluck('id')->all();
                $slotsSafeToDelete = array_diff($slotsToDelete, $slotsWithBookings);

                if (!empty($slotsSafeToDelete)) {
                    AvailabilitySlot::destroy($slotsSafeToDelete);
                }
            }
        }); // Fin de la transacción

        $warningMessage = session('warning');
        if (!empty($slotsWithBookings)) {
            $warningMessage = 'Experiencia actualizada, pero no se eliminaron algunos horarios porque ya tienen reservas asociadas.';
        }

        return redirect()->route('dashboard')->with('success', '¡Experiencia actualizada con éxito!')->with('warning', $warningMessage);
    }

    /**
     * Elimina una experiencia.
     */
    public function destroy(Experience $experience)
    {
        if (!Auth::check() || Auth::user()->role !== 'guide' || Auth::id() !== $experience->user_id) {
            abort(403, 'No tienes permiso para eliminar esta experiencia.');
        }

        $hasActiveBookings = $experience->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->exists();

        if ($hasActiveBookings) {
            return redirect()->route('dashboard')->with('error', 'No puedes eliminar una experiencia que tiene reservas activas.');
        }

        // Si no hay reservas activas, podemos proceder (las reservas pasadas o canceladas no importan)
        if ($experience->image_path) {
            Storage::disk('public')->delete($experience->image_path);
        }

        $experience->delete(); // Esto eliminará en cascada los slots y bookings asociados

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
