<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Reservas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Mensajes Flash --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-900 dark:border-green-600 dark:text-green-300" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-900 dark:border-red-600 dark:text-red-300" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative dark:bg-yellow-900 dark:border-yellow-600 dark:text-yellow-300" role="alert">
                            <span class="block sm:inline">{{ session('warning') }}</span>
                        </div>
                    @endif

                    <h3 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Tus Próximas Experiencias
                    </h3>

                    <div class="space-y-4">
                        @forelse ($bookings as $booking)
                            <div class="group flex flex-col lg:flex-row items-start lg:items-center p-5 border border-gray-200 dark:border-gray-700 rounded-xl hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 bg-white dark:bg-gray-800/50 transition-all duration-200">

                                {{-- 1. Imagen --}}
                                <div class="w-full lg:w-auto flex-shrink-0 mb-4 lg:mb-0 lg:mr-6">
                                    <div class="relative w-full lg:w-32 h-48 lg:h-32 rounded-lg overflow-hidden shadow-sm">
                                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                             src="{{ $booking->experience?->image_path ? asset('storage/' . $booking->experience->image_path) : 'https://placehold.co/400x300/e2e8f0/94a3b8?text=NexLocal' }}"
                                             alt="{{ $booking->experience?->title ?? 'Experiencia' }}">

                                        {{-- Badge de Precio sobre la imagen en móvil --}}
                                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-2 text-white lg:hidden">
                                            <span class="font-bold text-sm">${{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. Detalles --}}
                                <div class="flex-1 min-w-0 space-y-2 w-full">
                                    <div class="flex justify-between items-start">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 leading-tight">
                                            <a href="{{ $booking->experience ? route('experiences.show', $booking->experience) : '#' }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                {{ $booking->experience?->title ?? 'Experiencia Eliminada' }}
                                            </a>
                                        </h4>
                                        {{-- Precio en escritorio --}}
                                        <span class="hidden lg:inline-block font-bold text-lg text-gray-700 dark:text-gray-300">
                                            ${{ number_format($booking->total_amount, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-1 gap-x-4 text-sm text-gray-600 dark:text-gray-400">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @if ($booking->availabilitySlot)
                                                <span>{{ $booking->availabilitySlot->start_time->locale('es')->translatedFormat('l, j \de F - h:i A') }}</span>
                                            @else
                                                <span class="text-red-500 italic">Fecha no disponible</span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                            <span>{{ $booking->num_travelers ?? 1 }} {{ Str::plural('viajero', $booking->num_travelers ?? 1) }}</span>
                                        </div>

                                        <div class="flex items-center gap-2 sm:col-span-2 mt-1 pt-2 border-t border-dashed border-gray-200 dark:border-gray-700">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span class="text-xs">Reservado el {{ $booking->created_at->locale('es')->translatedFormat('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- 3. Estado y Acciones (DISEÑO MEJORADO) --}}
                                <div class="w-full lg:w-56 mt-4 lg:mt-0 lg:ml-6 flex flex-col gap-3">

                                    {{-- Badge de Estado --}}
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800',
                                            'confirmed' => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800',
                                            'cancelled' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                                            'completed' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                                            'in_progress' => 'bg-purple-100 text-purple-800 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800',
                                        ];
                                        $statusText = [
                                            'pending' => 'Pendiente',
                                            'confirmed' => 'Confirmada',
                                            'cancelled' => 'Cancelada',
                                            'completed' => 'Completada',
                                            'in_progress' => 'En Curso',
                                        ];
                                    @endphp
                                    <div class="w-full text-center py-1.5 px-3 rounded-full text-xs font-bold border {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusText[$booking->status] ?? ucfirst($booking->status) }}
                                    </div>

                                    {{-- Botones de Acción Apilados --}}
                                    <div class="flex flex-col gap-2">

                                        {{-- Botón Chat --}}
                                        @if(in_array($booking->status, ['pending', 'confirmed', 'in_progress', 'completed']))
                                            <button type="button"
                                                    onclick="openChatFromBooking({{ $booking->id }}, '{{ $booking->experience->user->name }}', '{{ $booking->experience->title }}', '{{ $booking->status }}')"
                                                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition-colors text-xs font-bold border border-indigo-100 dark:border-indigo-800">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                </svg>
                                                Chat con el Guía
                                            </button>
                                        @endif

                                        {{-- Botón Reseña --}}
                                        @if($booking->status === 'completed')
                                            @if($booking->review)
                                                <div class="w-full text-center py-2 text-xs text-yellow-600 dark:text-yellow-400 font-medium bg-yellow-50 dark:bg-yellow-900/10 rounded-lg border border-yellow-100 dark:border-yellow-800">
                                                    ★ Reseña Enviada
                                                </div>
                                            @else
                                                <a href="{{ route('reviews.create', ['booking_id' => $booking->id]) }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg shadow-sm transition-colors text-xs font-bold">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                                                    Calificar Experiencia
                                                </a>
                                            @endif
                                        @endif

                                        {{-- Botón Cancelar --}}
                                        @if(in_array($booking->status, ['pending', 'confirmed']) && $booking->availabilitySlot && $booking->availabilitySlot->start_time > now())
                                            <form action="{{ route('bookings.status', $booking) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta reserva?');" class="w-full">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-xs font-semibold">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    Cancelar Reserva
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Botón Completar (Confirmación Turista) --}}
                                        @if($booking->status === 'in_progress' && !$booking->tourist_confirmed_completed)
                                            <form action="{{ route('bookings.markAsCompleted', $booking) }}" method="POST" onsubmit="return confirm('¿Deseas marcar esta experiencia como completada?');" class="w-full">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition-colors text-xs font-bold">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                    Confirmar Finalización
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 bg-gray-50 dark:bg-gray-800/50 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Sin reservas activas</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">¡Explora nuestras experiencias y reserva tu próxima aventura!</p>
                                <div class="mt-6">
                                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Explorar Experiencias
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $bookings->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openChatFromBooking(bookingId, userName, experienceTitle, bookingStatus) {
                // Crear objeto de conversación
                const conversation = {
                    booking_id: bookingId,
                    other_user: {
                        name: userName
                    },
                    experience_title: experienceTitle,
                    booking_status: bookingStatus
                };

                // Disparar evento para abrir la ventana de chat
                window.dispatchEvent(new CustomEvent('open-chat-window', {
                    detail: conversation
                }));
            }
        </script>
    @endpush
</x-app-layout>
