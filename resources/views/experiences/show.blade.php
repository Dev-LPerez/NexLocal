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
                        @if($experience->user->profile_photo_path)
                            <img src="{{ asset('storage/' . $experience->user->profile_photo_path) }}" alt="Foto de perfil del guía" class="w-10 h-10 rounded-full object-cover border-2 border-indigo-500">
                        @endif
                        <span class="text-lg text-gray-700 dark:text-gray-300">
                            Ofrecido por
                            <button type="button" onclick="openGuideModal()" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline focus:outline-none bg-transparent border-0 p-0 m-0 align-baseline cursor-pointer">{{ $experience->user->name }}</button>
                        </span>
                    </div>
                    @if($experience->user->bio)
                        <div class="mt-2 text-gray-600 dark:text-gray-300 text-base line-clamp-2">
                            {{ $experience->user->bio }}
                        </div>
                    @endif
                </div>

                {{-- Modal Perfil del Guía --}}
                <div id="guide-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-md h-[520px] sm:h-[520px] md:h-[540px] lg:h-[560px] xl:h-[600px] p-0 relative mx-2 sm:mx-0 overflow-hidden flex flex-col">
                        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 h-32 w-full flex items-end justify-center relative shrink-0">
                            @if($experience->user->profile_photo_path)
                                <img src="{{ asset('storage/' . $experience->user->profile_photo_path) }}" alt="Foto de perfil del guía" class="absolute -bottom-12 left-1/2 transform -translate-x-1/2 w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-900 shadow-lg">
                            @endif
                        </div>
                        <div class="pt-16 pb-8 px-6 flex-1 flex flex-col items-center overflow-y-auto scrollbar-thin scrollbar-thumb-indigo-200 dark:scrollbar-thumb-indigo-800 scrollbar-track-transparent">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-1 text-center break-words">{{ $experience->user->name }}</h2>
                            <span class="text-sm text-gray-500 dark:text-gray-400 mb-2">Guía de experiencias</span>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="inline-block bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200 text-xs px-3 py-1 rounded-full font-semibold">Verificado</span>
                                <span class="inline-block bg-pink-100 text-pink-700 dark:bg-pink-900 dark:text-pink-200 text-xs px-3 py-1 rounded-full font-semibold">Local</span>
                            </div>
                            @if($experience->user->bio)
                                <p class="text-gray-700 dark:text-gray-300 text-center whitespace-pre-line break-words max-w-full mb-2">{{ $experience->user->bio }}</p>
                            @endif
                            <div class="w-full max-w-xs mx-auto text-center space-y-2 mb-4">
                                @if($experience->user->age)
                                    <div class="flex items-center justify-center gap-2 text-gray-700 dark:text-gray-300 text-sm">
                                        <span class="font-semibold">Edad:</span> <span>{{ $experience->user->age }} años</span>
                                    </div>
                                @endif
                                @if($experience->user->hobbies)
                                    <div class="flex flex-col items-center text-gray-700 dark:text-gray-300 text-sm">
                                        <span class="font-semibold">Hobbies:</span>
                                        <span>{{ $experience->user->hobbies }}</span>
                                    </div>
                                @endif
                                @if($experience->user->occupation)
                                    <div class="flex flex-col items-center text-gray-700 dark:text-gray-300 text-sm">
                                        <span class="font-semibold">¿A qué te dedicas?</span>
                                        <span>{{ $experience->user->occupation }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col items-center gap-2 w-full">
                                <div class="flex gap-4 justify-center">
                                    <button class="flex flex-col items-center group" disabled>
                                        <span class="bg-indigo-100 dark:bg-indigo-900 p-2 rounded-full">
                                            <svg class="w-6 h-6 text-indigo-500 group-hover:text-indigo-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $experience->user->role === 'guide' ? 'Guía' : 'Turista' }}</span>
                                    </button>
                                    <button class="flex flex-col items-center group" disabled>
                                        <span class="bg-pink-100 dark:bg-pink-900 p-2 rounded-full">
                                            <svg class="w-6 h-6 text-pink-500 group-hover:text-pink-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Confiable</span>
                                    </button>
                                </div>
                                <div class="flex gap-2 mt-4">
                                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-full shadow transition">Seguir</button>
                                    <button class="bg-gray-200 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-semibold px-4 py-2 rounded-full shadow transition" onclick="closeGuideModal()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                        <button onclick="closeGuideModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 bg-white dark:bg-gray-900 rounded-full p-1 shadow-md z-10">
                            <svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg>
                        </button>
                    </div>
                </div>
                <script>
                    function openGuideModal() {
                        document.getElementById('guide-modal').classList.remove('hidden');
                    }
                    function closeGuideModal() {
                        document.getElementById('guide-modal').classList.add('hidden');
                    }
                </script>

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

                @if($experience->meeting_point_lat && $experience->meeting_point_lng)
                    <div class="border-t dark:border-gray-700 pt-6">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Punto de Encuentro</h2>

                        @if($experience->meeting_point_name)
                            <p class="text-xl font-medium text-gray-800 dark:text-gray-200 mb-3">
                                {{ $experience->meeting_point_name }}
                            </p>
                        @endif

                        <div id="show-map" class="w-full h-80 rounded-lg border dark:border-gray-700 overflow-hidden"></div>

                        <a href="https://www.google.com/maps/search/?api=1&query={{ $experience->meeting_point_lat }},{{ $experience->meeting_point_lng }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-block mt-3 text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                            Ver en Google Maps
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                @endif
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
                                    @if($review->user && $review->user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $review->user->profile_photo_path) }}" alt="Foto de perfil del turista" class="h-10 w-10 rounded-full object-cover border-2 border-indigo-400">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endif
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

                            {{-- Campo para número de viajeros --}}
                            <div>
                                <label for="num_travelers" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad de viajeros</label>
                                <input type="number" name="num_travelers" id="num_travelers" min="1" value="{{ old('num_travelers', 1) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                <x-input-error :messages="$errors->get('num_travelers')" class="mt-1"/>
                            </div>

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

    @push('scripts')
        @if($experience->meeting_point_lat && $experience->meeting_point_lng)
            <script>
                function initShowMap() {
                    const location = {
                        lat: {{ (float)$experience->meeting_point_lat }},
                        lng: {{ (float)$experience->meeting_point_lng }}
                    };

                    const map = new google.maps.Map(document.getElementById('show-map'), {
                        center: location,
                        zoom: 16,
                        disableDefaultUI: true, // Oculta controles para un look más limpio
                        gestureHandling: 'none', // No permite zoom ni paneo
                        clickableIcons: false
                    });

                    new google.maps.Marker({
                        position: location,
                        map: map,
                        title: '{{ addslashes($experience->meeting_point_name ?? "Punto de Encuentro") }}'
                    });
                }
            </script>

            <script async defer
                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMpMyjTMPg7JWsAT4s9UpAPjT6cjvxBjk&callback=initShowMap">
            </script>
        @endif
    @endpush
</x-app-layout>
