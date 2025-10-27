<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mis Reservas
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    @if($bookings->isEmpty())
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7v3m0 0v3m0-3h3m-3 0H9m12-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No tienes reservas</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Aún no has reservado ninguna experiencia.</p>
                            <div class="mt-6">
                                <x-primary-button as="a" :href="route('home')">
                                    Descubrir Experiencias
                                </x-primary-button>
                            </div>
                        </div>
                    @else
                        {{-- Listado de Reservas --}}
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($bookings as $booking)
                                <div class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ $booking->experience->image_path ? Storage::url($booking->experience->image_path) : 'https://placehold.co/150x100/e9d5ff/8b5cf6?text=Nex' }}"
                                             alt="{{ $booking->experience->title }}"
                                             class="w-32 h-20 object-cover rounded-lg">
                                        <div>
                                            <a href="{{ route('experiences.show', $booking->experience) }}" class="text-lg font-semibold text-primary dark:text-secondary hover:underline">
                                                {{ $booking->experience->title }}
                                            </a>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Fecha: <span class="font-medium">
                                                    @if($booking->booking_date)
                                                        {{ $booking->booking_date->translatedFormat('d \d\e F \d\e Y, H:i') }}
                                                    @else
                                                        Sin fecha asignada
                                                    @endif
                                                </span>
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Guía: <span class="font-medium">{{ $booking->experience->user->name ?? 'N/A' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:items-end space-y-2">
                                        {{-- Estado --}}
                                        <div>
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                style="
                                                    @if($booking->status == 'confirmed') background-color: #bbf7d0; color: #166534; @endif
                                                    @if($booking->status == 'pending') background-color: #fef9c3; color: #854d0e; @endif
                                                    @if($booking->status == 'cancelled') background-color: #fecaca; color: #991b1b; @endif
                                                    @if($booking->status == 'completed') background-color: #dbeafe; color: #1e40af; @endif
                                                ">
                                                {{-- Mapeo de estados a español --}}
                                                @if($booking->status == 'confirmed') Confirmada
                                                @elseif($booking->status == 'pending') Pendiente
                                                @elseif($booking->status == 'cancelled') Cancelada
                                                @elseif($booking->status == 'completed') Completada
                                                @else {{ ucfirst($booking->status) }}
                                                @endif
                                            </span>
                                        </div>
                                        {{-- Botón de Cancelar --}}
                                        @if($booking->status == 'confirmed' || $booking->status == 'pending')
                                            <form action="{{ route('bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?');">
                                                @csrf
                                                @method('PATCH')
                                                <x-danger-button type="submit" class="!text-xs !px-3 !py-1.5">
                                                    Cancelar Reserva
                                                </x-danger-button>
                                            </form>
                                        @endif
                                        {{-- Futuro: Botón de Escribir Reseña --}}
                                        {{-- @if($booking->status == 'completed')
                                            <x-secondary-button as="a" href="#" class="!text-xs !px-3 !py-1.5">
                                                Escribir Reseña
                                            </x-secondary-button>
                                        @endif --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- Paginación --}}
                        <div class="mt-4">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

