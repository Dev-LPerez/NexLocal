<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard - Panel de Guía') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">

                {{-- 1. Resumen y Acción Rápida --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 bg-white dark:bg-gray-800 shadow-lg rounded-2xl border dark:border-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.536M11.121 12.818.879 11.464M12 6H5.25M12 6h6.75M12 6v3.75m0 6V21m-3-2.818.879.536M12 18.182.879 16.828M21 12h-3.75m.75 3h3.75M21 12v-3.75m0 6V21m-3-2.818.879.536M12 18.182.879 16.828" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Experiencias</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $experiences->count() }}</p>
                </div>
            </div>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 shadow-lg rounded-2xl border dark:border-gray-700">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Reservas Totales</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $guideBookings->count() }}</p>
                </div>
            </div>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 shadow-lg rounded-2xl border dark:border-gray-700 flex flex-col justify-center">
            <a href="{{ route('experiences.create') }}" class="w-full">
                <x-primary-button class="w-full text-center justify-center !py-3">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                    </svg>
                    Crear Nueva Experiencia
                </x-primary-button>
            </a>
        </div>
    </div>

    {{-- 2. Tus Experiencias Publicadas --}}
    <div>
        <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Tus Experiencias</h3>
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border dark:border-gray-700">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($experiences as $experience)
                    <li class="p-4 flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('experiences.show', $experience) }}" class="text-lg font-semibold text-indigo-600 dark:text-indigo-400 hover:underline truncate">{{ $experience->title }}</a>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $experience->location }} - ${{ number_format($experience->price, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="flex-shrink-0 mt-4 md:mt-0 md:ml-4 flex space-x-3">
                            <a href="{{ route('experiences.edit', $experience) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">Editar</a>
                            <form action="{{ route('experiences.destroy', $experience) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta experiencia? Esto no se puede deshacer.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:underline">Eliminar</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="p-6 text-center text-gray-500 dark:text-gray-400">
                        Aún no has creado ninguna experiencia.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- 3. Próximas Reservas (Vista del Guía) --}}
    <div>
        <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Gestión de Reservas</h3>
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Experiencia</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Turista</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha y Hora</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($guideBookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Str::limit($booking->experience->title, 30) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $booking->user->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $booking->availabilitySlot?->start_time->locale('es')->translatedFormat('j M Y, h:i A') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    // --- INICIO: CAMBIO DE LÓGICA DE ESTADOS ---
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'in_progress' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300',
                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                    ];
                                    $statusText = [
                                        'pending' => 'Esperando Confirmación',
                                        'confirmed' => 'Confirmada',
                                        'in_progress' => 'En Curso',
                                        'cancelled' => 'Cancelada',
                                        'completed' => 'Completada',
                                    ];
                                    // --- FIN: CAMBIO DE LÓGICA DE ESTADOS ---
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusText[$booking->status] ?? ucfirst($booking->status) }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-y-2 flex flex-col items-start">

                                {{-- --- INICIO: LÓGICA DE BOTONES (GUÍA) --- --}}

                                {{-- 1. Acciones para 'pending' --}}
                                @if($booking->status === 'pending')
                                    <form action="{{ route('bookings.status', $booking) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="text-xs text-green-600 dark:text-green-400 hover:underline font-semibold">Confirmar Reserva</button>
                                    </form>
                                    <form action="{{ route('bookings.status', $booking) }}" method="POST" onsubmit="return confirm('¿Rechazar esta reserva? El cupo será devuelto.');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">Rechazar</button>
                                    </form>

                                    {{-- 2. Acciones para 'confirmed' (y futura) --}}
                                @elseif($booking->status === 'confirmed' && $booking->availabilitySlot && $booking->availabilitySlot->start_time > now())
                                    <form action="{{ route('bookings.status', $booking) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="in_progress">
                                        <button type="submit" class="text-xs text-cyan-600 dark:text-cyan-400 hover:underline font-semibold">Iniciar Experiencia (Manual)</button>
                                    </form>
                                    <form action="{{ route('bookings.status', $booking) }}" method="POST" onsubmit="return confirm('¿Cancelar esta reserva? El cupo será devuelto.');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">Cancelar</button>
                                    </form>

                                    {{-- 3. Acciones para 'in_progress' --}}
                                @elseif($booking->status === 'in_progress')
                                    @if($booking->guide_confirmed_completed)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Esperando al turista...</span>
                                    @else
                                        <form action="{{ route('bookings.markAsCompleted', $booking) }}" method="POST" onsubmit="return confirm('¿Confirmas que la experiencia ha finalizado?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                                                Marcar como Completada
                                            </button>
                                        </form>
                                    @endif

                                    {{-- 4. Sin acciones para 'completed' o 'cancelled' --}}
                                @elseif($booking->status === 'completed')
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Finalizada</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Cancelada</span>
                                @endif
                                {{-- --- FIN: LÓGICA DE BOTONES (GUÍA) --- --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                Aún no has recibido ninguna reserva para tus experiencias.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Paginación para las reservas del guía -->
            <div class="p-4 border-t dark:border-gray-700">
                {{ $guideBookings->links() }}
            </div>
        </div>
    </div>

            </div>
        </div>
    </div>
</x-app-layout>
