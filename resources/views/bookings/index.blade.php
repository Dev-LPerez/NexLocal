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

                    <h3 class="text-2xl font-semibold mb-6">Tus Próximas Experiencias</h3>

                    @forelse ($bookings as $booking)
                        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 p-4 border-b dark:border-gray-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 ease-in-out">
                            <!-- Imagen de la Experiencia -->
                            <img class="h-24 w-24 sm:h-20 sm:w-20 object-cover rounded-lg flex-shrink-0"
                                 src="{{ $booking->experience?->image_path ? Storage::url($booking->experience->image_path) : 'https://placehold.co/100x100/e2e8f0/94a3b8?text=NexLocal' }}"
                                 alt="{{ $booking->experience?->title ?? 'Experiencia no encontrada' }}">

                            <!-- Detalles de la Reserva -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                    <a href="{{ $booking->experience ? route('experiences.show', $booking->experience) : '#' }}" class="hover:underline hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $booking->experience?->title ?? 'Experiencia Eliminada' }}
                                    </a>
                                </h4>

                                {{-- Fecha y Hora del Evento --}}
                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                    @if ($booking->availabilitySlot)
                                        <span class="font-medium">Fecha del Evento:</span> {{ $booking->availabilitySlot->start_time->locale('es')->translatedFormat('l, j \de F \de Y - h:i A') }}
                                    @else
                                        <span class="font-medium text-red-500">Horario no especificado</span>
                                    @endif
                                </p>

                                {{-- Precio Guardado --}}
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">Precio:</span> ${{ number_format($booking->total_amount, 0, ',', '.') }}
                                </p>

                                {{-- Fecha de Creación de la Reserva --}}
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{-- Esta es la fecha en que se hizo clic en "Reservar" --}}
                                    <span class="font-medium">Reservado el:</span> {{ $booking->created_at->locale('es')->translatedFormat('j \de F \de Y, h:i A') }}
                                </p>

                                {{-- Cantidad de Turistas --}}
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">Cantidad de Turistas:</span>
                                    <span>{{ $booking->num_travelers ?? 1 }} {{ Str::plural('turista', $booking->num_travelers ?? 1) }}</span>
                                </p>
                            </div>

                            <!-- Estado y Acciones -->
                            <div class="flex-shrink-0 text-right space-y-2 w-full sm:w-auto">
                                <div>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                            'in_progress' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                        ];
                                        $statusText = [
                                            'pending' => 'Pendiente',
                                            'confirmed' => 'Confirmada',
                                            'cancelled' => 'Cancelada',
                                            'completed' => 'Completada',
                                            'in_progress' => 'En Progreso',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusText[$booking->status] ?? ucfirst($booking->status) }}
                                    </span>
                                </div>

                                {{-- --- INICIO: LÓGICA DE RESEÑAS --- --}}
                                @if($booking->status === 'completed')
                                    @if($booking->review)
                                        {{-- El usuario ya dejó una reseña --}}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Reseña enviada</span>
                                    @else
                                        {{-- Botón para dejar reseña --}}
                                        <a href="{{ route('reviews.create', ['booking_id' => $booking->id]) }}" class="inline-block text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">
                                            Dejar Reseña
                                        </a>
                                    @endif
                                @endif
                                {{-- --- FIN: LÓGICA DE RESEÑAS --- --}}

                                {{-- Solo mostrar "Cancelar" si la reserva está confirmada O pendiente Y la fecha del evento aún no ha pasado --}}
                                @if(in_array($booking->status, ['pending', 'confirmed']) && $booking->availabilitySlot && $booking->availabilitySlot->start_time > now())
                                    <form action="{{ route('bookings.status', $booking) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta reserva?');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">
                                            Cancelar Reserva
                                        </button>
                                    </form>
                                @endif

                                {{-- Mostrar botón para marcar como completada si está en progreso y falta la confirmación del turista --}}
                                @if($booking->status === 'in_progress' && !$booking->tourist_confirmed_completed)
                                    <form action="{{ route('bookings.markAsCompleted', $booking) }}" method="POST" onsubmit="return confirm('¿Deseas marcar esta experiencia como completada?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                                            Marcar como Completada
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">
                            Aún no tienes ninguna reserva. ¡Anímate a explorar!
                        </p>
                    @endforelse

                    <!-- Paginación -->
                    <div class="mt-8">
                        {{ $bookings->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
