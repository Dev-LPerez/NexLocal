<x-app-layout>
    <div class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8 px-4">

            {{-- Columna Principal: Detalles de la Experiencia --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Imagen Principal --}}
                <div class="relative overflow-hidden rounded-lg shadow-lg" style="padding-bottom: 56.25%;"> {{-- Aspect Ratio 16:9 --}}
                    <img src="{{ $experience->image_path ? Storage::url($experience->image_path) : 'https://placehold.co/1200x675/e2e8f0/94a3b8?text=NexLocal' }}"
                         alt="{{ $experience->title }}"
                         class="absolute top-0 left-0 w-full h-full object-cover">

                    {{-- Badge de Categoría --}}
                    <span class="absolute top-4 left-4 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-md">
                        {{ $experience->category }}
                    </span>
                </div>

                {{-- Título y Guía --}}
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100">{{ $experience->title }}</h1>
                    <div class="mt-2 flex items-center space-x-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-lg text-gray-700 dark:text-gray-300">Ofrecido por <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $experience->user->name }}</span></span>
                    </div>
                </div>

                {{-- --- INICIO: SECCIÓN DE CALIFICACIÓN PROMEDIO --- --}}
                <div class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                    @if ($reviewCount > 0)
                        <div class="flex items-center space-x-1">
                            <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.445a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.539 1.118l-3.367-2.445a1 1 0 00-1.175 0l-3.367 2.445c-.784.57-1.838-.197-1.539-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.07 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"></path>
                            </svg>
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ number_format($averageRating, 1) }}</span>
                        </div>
                        <span class="text-lg">·</span>
                        <span class="text-lg">{{ $reviewCount }} reseña(s)</span>
                    @else
                        <span class="text-lg">Aún no hay reseñas</span>
                    @endif
                </div>
                {{-- --- FIN: SECCIÓN DE CALIFICACIÓN PROMEDIO --- --}}

                {{-- Detalles Rápidos (Ubicación y Duración) --}}
                <div class="flex items-center space-x-6 text-gray-600 dark:text-gray-400 border-t border-b dark:border-gray-700 py-4">
                    <div class="flex items-center space-x-2">
                        <svg class="h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span class="text-lg">{{ $experience->location }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-lg">{{ $experience->duration }}</span>
                    </div>
                </div>

                {{-- Descripción --}}
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Sobre esta experiencia</h2>
                    <p class="text-lg text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $experience->description }}</p>
                </div>

                {{-- Qué Incluye --}}
                @if(!empty($experience->includes))
                    <div class="border-t dark:border-gray-700 pt-6">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">¿Qué incluye?</h2>
                        <ul class="space-y-3">
                            @foreach ($experience->includes as $item)
                                <li class="flex items-center">
                                    <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-lg text-gray-700 dark:text-gray-300">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Qué NO Incluye --}}
                @if(!empty($experience->not_includes))
                    <div class="border-t dark:border-gray-700 pt-6">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">¿Qué NO incluye?</h2>
                        <ul class="space-y-3">
                            @foreach ($experience->not_includes as $item)
                                <li class="flex items-center">
                                    <svg class="h-6 w-6 text-red-500 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-lg text-gray-700 dark:text-gray-300">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- --- INICIO: NUEVA SECCIÓN DE RESEÑAS --- --}}
                <div class="border-t dark:border-gray-700 pt-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Reseñas de Turistas</h2>

                    @forelse ($experience->reviews as $review)
                        <div class="py-4 border-b dark:border-gray-700 last:border-b-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    {{-- Placeholder para foto de perfil --}}
                                    <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $review->user->name ?? 'Turista' }}</span>
                                        <span class="block text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->locale('es')->diffForHumans() }}</span>
                                    </div>
                                </div>

                                {{-- Estrellas de esta reseña --}}
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="h-5 w-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.445a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.539 1.118l-3.367-2.445a1 1 0 00-1.175 0l-3.367 2.445c-.784.57-1.838-.197-1.539-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.07 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="text-lg text-gray-700 dark:text-gray-300 mt-3">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">Sé el primero en dejar una reseña para esta experiencia.</p>
                        </div>
                    @endforelse
                </div>
                {{-- --- FIN: NUEVA SECCIÓN DE RESEÑAS --- --}}

            </div>

            {{-- Columna Lateral: Reserva --}}
            <div class="lg:col-span-1">
                <div class="sticky top-28 bg-white dark:bg-gray-800 shadow-xl rounded-lg border dark:border-gray-700 p-6 space-y-5">

                    {{-- Precio --}}
                    <div class="flex items-baseline text-gray-900 dark:text-gray-100">
                        <span class="text-4xl font-bold">${{ number_format($experience->price, 0, ',', '.') }}</span>
                        <span class="ml-1 text-lg text-gray-600 dark:text-gray-400">/ persona</span>
                    </div>

                    {{-- Formulario de Reserva --}}
                    @auth
                        <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
                            @csrf
                            {{-- --- AÑADIR INPUT OCULTO PARA EXPERIENCE_ID (Aunque ya lo obtenemos del slot, es buena práctica) --- --}}
                            <input type="hidden" name="experience_id" value="{{ $experience->id }}">

                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 border-t dark:border-gray-700 pt-4">Selecciona un horario</h3>

                            {{-- Lista de Horarios Disponibles --}}
                            <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                                @forelse ($groupedSlots as $date => $slots)
                                    <div class="space-y-2">
                                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($date)->locale('es')->translatedFormat('l, j \de F') }}</p>

                                        @foreach ($slots as $slot)
                                            <label for="slot_{{ $slot->id }}"
                                                   class="flex items-center justify-between p-4 border dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500 has-[:checked]:dark:bg-gray-900 has-[:checked]:dark:border-indigo-600 transition-all">
                                                <div class="flex items-center">
                                                    <input type="radio" name="availability_slot_id" id="slot_{{ $slot->id }}" value="{{ $slot->id }}" class="text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-6button dark:bg-gray-700 dark:border-gray-600" required>

                                                    {{-- --- CORRECCIÓN VISUAL 1 --- --}}
                                                    <span class="ml-3 font-medium text-gray-900 dark:text-gray-100">
                                                    Hora: {{ $slot->start_time->format('h:i A') }}
                                                </span>
                                                </div>
                                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                {{-- --- CORRECCIÓN VISUAL 2 --- --}}
                                                    {{-- Usamos 'available_spots' que es el contador real --}}
                                                    {{ $slot->available_spots }} cupos disp.
                                            </span>
                                            </label>
                                        @endforeach
                                    </div>
                                @empty
                                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No hay horarios disponibles para esta experiencia por el momento.</p>
                                @endforelse
                            </div>

                            <x-input-error :messages="$errors->get('availability_slot_id')" class="mt-1"/>

                            {{-- Mostrar errores generales (ej: "sin cupos", "ya reservado") --}}
                            @if (session('error'))
                                <div class="mt-1 text-sm text-red-600 dark:text-red-400 font-medium">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if ($groupedSlots->count() > 0)
                                <x-primary-button class="w-full text-center justify-center !py-3">
                                    Reservar Ahora
                                </x-primary-button>
                            @else
                                <x-secondary-button class="w-full text-center justify-center !py-3" disabled>
                                    No disponible
                                </x-secondary-button>
                            @endif

                        </form>
                    @else
                        <div class="border-t dark:border-gray-700 pt-4 text-center">
                            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                Inicia sesión
                            </a>
                            <span class="text-gray-700 dark:text-gray-300"> para poder reservar.</span>
                        </div>
                    @endautH
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

