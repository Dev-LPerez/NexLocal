<x-app-layout>
    <x-slot name="header">
        <a href="{{ url()->previous() ?? route('home') }}" class="text-sm text-primary dark:text-secondary hover:underline">&larr; Volver</a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                {{-- Imagen Principal --}}
                <img class="w-full h-64 md:h-96 object-cover"
                     src="{{ $experience->image_path ? Storage::url($experience->image_path) : 'https://placehold.co/1200x400/e9d5ff/8b5cf6?text=Experiencia' }}"
                     alt="{{ $experience->title }}"
                     onerror="this.onerror=null; this.src='https://placehold.co/1200x400/e9d5ff/8b5cf6?text=Imagen+No+Disponible';">

                <div class="p-6 lg:p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Columna Principal (Información) --}}
                    <div class="md:col-span-2 space-y-6">
                        {{-- Título y Categoría --}}
                        <div>
                            @if($experience->category)
                                <span class="inline-block bg-secondary dark:bg-primary/50 text-primary-dark dark:text-secondary px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider mb-2">
                                    {{ $experience->category }}
                                </span>
                            @endif
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $experience->title }}</h1>
                        </div>

                        {{-- Detalles Rápidos (Iconos) --}}
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                <span>{{ $experience->location }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                <span>{{ $experience->duration }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                <span class="font-semibold">${{ number_format($experience->price, 0, ',', '.') }} COP</span> <span class="text-sm">por persona</span>
                            </div>
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Acerca de esta experiencia</h2>
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $experience->description }}</p>
                        </div>

                        {{-- Sección Qué Incluye --}}
                        @if(!empty($experience->includes))
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">¿Qué incluye?</h2>
                            <ul class="space-y-2">
                                @foreach($experience->includes as $item)
                                <li class="flex items-start">
                                    <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $item }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Sección Qué NO Incluye --}}
                        @if(!empty($experience->not_includes))
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">¿Qué NO incluye?</h2>
                            <ul class="space-y-2">
                                @foreach($experience->not_includes as $item)
                                <li class="flex items-start">
                                    <svg class="flex-shrink-0 w-5 h-5 text-red-500 dark:text-red-400 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $item }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Información del Guía --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Conoce a tu guía</h2>
                            <div class="flex items-center gap-4">
                                {{-- <img src="{{ $experience->user->profile_photo_url ?? 'https://placehold.co/80x80' }}" alt="{{ $experience->user->name }}" class="h-16 w-16 rounded-full object-cover"> --}}
                                <div>
                                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white">{{ $experience->user->name ?? 'Guía NexLocal' }}</h3>
                                    @if($experience->user->identity_verified_at)
                                        <span class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-blue-600 dark:text-blue-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                                            Identidad Verificada
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Columna Lateral (Reserva) --}}
                    <div class="md:col-span-1">
                        <div class="sticky top-24 bg-gray-50 dark:bg-gray-800/50 p-6 rounded-lg shadow-md border dark:border-gray-700">
                            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">${{ number_format($experience->price, 0, ',', '.') }} <span class="text-base font-normal text-gray-600 dark:text-gray-400">COP / persona</span></h2>
                            <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="experience_id" value="{{ $experience->id }}">
                                {{-- Selección de Horario (si hay slots) --}}
                                <div>
                                    <x-input-label value="Selecciona un horario disponible *" class="mb-2"/>
                                    @if($experience->availabilitySlots->isNotEmpty())
                                        <div class="max-h-60 overflow-y-auto space-y-3 pr-2">
                                            @foreach($experience->availabilitySlots->groupBy(fn($slot) => $slot->start_time->format('Y-m-d')) as $date => $slotsForDate)
                                                <div class="mb-2">
                                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        {{ \Carbon\Carbon::parse($date)->isoFormat('dddd D [de] MMMM') }}
                                                    </p>
                                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                                        @foreach($slotsForDate as $slot)
                                                            @php
                                                                $isAvailable = $slot->is_available;
                                                                $remaining = $slot->remaining_participants;
                                                            @endphp
                                                            <label for="slot_{{ $slot->id }}"
                                                                class="block p-3 border rounded-md cursor-pointer transition-colors
                                                                    {{ $isAvailable ? 'border-gray-300 dark:border-gray-600 hover:border-primary dark:hover:border-secondary hover:bg-violet-50 dark:hover:bg-violet-900/30 has-[:checked]:border-primary dark:has-[:checked]:border-secondary has-[:checked]:ring-1 has-[:checked]:ring-primary dark:has-[:checked]:ring-secondary' : 'border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 cursor-not-allowed opacity-60' }}">
                                                                <input type="radio" name="availability_slot_id" id="slot_{{ $slot->id }}" value="{{ $slot->id }}" class="sr-only" required @disabled(!$isAvailable)>
                                                                <span class="block text-sm font-semibold text-center text-gray-800 dark:text-gray-200">{{ $slot->formatted_time }}</span>
                                                                <span class="block text-xs text-center mt-1 {{ $isAvailable ? 'text-gray-500 dark:text-gray-400' : 'text-red-500 dark:text-red-400 font-medium' }}">
                                                                    {{ $isAvailable ? ($remaining . ' cupo'.($remaining > 1 ? 's' : '').' disp.') : 'Agotado' }}
                                                                </span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No hay horarios disponibles próximamente.</p>
                                    @endif
                                    <x-input-error :messages="$errors->get('availability_slot_id')" class="mt-2"/>
                                </div>
                                @if(session('error'))
                                <div class="mt-4 text-sm text-red-600 dark:text-red-400">
                                    {{ session('error') }}
                                </div>
                                @endif
                                <x-primary-button class="w-full justify-center !py-3 !text-base" :disabled="$experience->availabilitySlots->isEmpty()">
                                    {{ $experience->availabilitySlots->isEmpty() ? 'No Disponible' : 'Reservar Ahora (Sin Pago)' }}
                                </x-primary-button>
                            </form>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 text-center">Todavía no se te cobrará nada.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
