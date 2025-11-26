<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard - Panel de Guía') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">

                {{-- Alerta de Verificación --}}
                @if(!Auth::user()->isVerifiedGuide())
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border-l-4 border-yellow-500 p-6 rounded-lg shadow-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                @if(Auth::user()->verification_status === 'pending')
                                    <h3 class="text-lg font-bold text-yellow-800 dark:text-yellow-300 mb-2">
                                        ⏳ Verificación en Proceso
                                    </h3>
                                    <p class="text-sm text-yellow-700 dark:text-yellow-400 mb-3">
                                        Hemos recibido tus documentos de identidad y están siendo revisados por nuestro equipo.
                                        Te notificaremos cuando tu cuenta sea verificada (generalmente en 24-48 horas).
                                    </p>
                                    <p class="text-xs text-yellow-600 dark:text-yellow-500">
                                        Mientras tanto, puedes explorar la plataforma pero no podrás crear experiencias hasta completar la verificación.
                                    </p>
                                @elseif(Auth::user()->verification_status === 'rejected')
                                    <h3 class="text-lg font-bold text-red-800 dark:text-red-300 mb-2">
                                        ❌ Verificación Rechazada
                                    </h3>
                                    <p class="text-sm text-red-700 dark:text-red-400 mb-2">
                                        <strong>Razón:</strong> {{ Auth::user()->rejection_reason ?? 'No se proporcionó una razón específica.' }}
                                    </p>
                                    <p class="text-sm text-red-700 dark:text-red-400 mb-3">
                                        Por favor, revisa los documentos y envíalos nuevamente asegurándote de que sean claros y legibles.
                                    </p>
                                    <a href="{{ route('verification.create') }}"
                                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                                        📤 Enviar Nuevos Documentos
                                    </a>
                                @else
                                    <h3 class="text-lg font-bold text-yellow-800 dark:text-yellow-300 mb-2">
                                        📋 Verificación de Identidad Requerida
                                    </h3>
                                    <p class="text-sm text-yellow-700 dark:text-yellow-400 mb-3">
                                        Para crear experiencias turísticas, primero debes verificar tu identidad.
                                        Este proceso es rápido y garantiza la seguridad de todos nuestros usuarios.
                                    </p>
                                    <a href="{{ route('verification.create') }}"
                                       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Verificar Mi Identidad Ahora
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

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
                        @if(Auth::user()->isVerifiedGuide())
                            <a href="{{ route('experiences.create') }}" class="w-full">
                                <x-primary-button class="w-full text-center justify-center !py-3">
                                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                    </svg>
                                    Crear Nueva Experiencia
                                </x-primary-button>
                            </a>
                        @else
                            <button disabled class="w-full text-center justify-center !py-3 px-4 bg-gray-400 dark:bg-gray-600 text-white font-semibold rounded-md cursor-not-allowed opacity-60">
                                <svg class="h-5 w-5 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                Verificación Requerida
                            </button>
                            <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-2">
                                Completa la verificación para crear experiencias
                            </p>
                        @endif
                    </div>
                </div>

                {{-- 2. Tus Experiencias Publicadas --}}
                <div>
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Tus Experiencias</h3>
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border dark:border-gray-700">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($experiences as $experience)
                                <li class="p-4 flex flex-col md:flex-row md:items-center md:justify-between {{ $experience->status !== 'published' ? 'bg-yellow-50 dark:bg-yellow-900/10' : '' }}">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <a href="{{ route('experiences.show', $experience) }}" class="text-lg font-semibold text-indigo-600 dark:text-indigo-400 hover:underline truncate">{{ $experience->title }}</a>

                                            {{-- Badges de estado --}}
                                            @if($experience->is_featured)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                    ⭐ Destacada
                                                </span>
                                            @endif

                                            @if($experience->status === 'hidden')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400">
                                                    🔒 Oculta por Admin
                                                </span>
                                            @elseif($experience->status === 'rejected')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                    ❌ Rechazada
                                                </span>
                                            @elseif($experience->status === 'draft')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">
                                                    📝 Borrador
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $experience->location }} - ${{ number_format($experience->price, 0, ',', '.') }}
                                        </p>

                                        {{-- Mostrar nota de moderación si existe --}}
                                        @if($experience->moderation_note && $experience->status !== 'published')
                                            <p class="text-xs text-orange-600 dark:text-orange-400 mt-1 italic">
                                                📋 Nota del Admin: {{ $experience->moderation_note }}
                                            </p>
                                        @endif
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

                                            {{-- Botón para abrir chat --}}
                                            @if(in_array($booking->status, ['pending', 'confirmed', 'in_progress', 'completed']))
                                                <button type="button"
                                                        onclick="openChatFromBooking({{ $booking->id }}, '{{ $booking->user->name }}', '{{ $booking->experience->title }}', '{{ $booking->status }}')"
                                                        class="inline-flex items-center gap-1 text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-semibold">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                    Chatear con turista
                                                </button>
                                            @endif

                                            {{-- --- INICIO: NUEVO BOTÓN Y MODAL --- --}}
                                            <button type="button"
                                                    x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'booking-details-{{ $booking->id }}')"
                                                    class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                                                Ver Detalles
                                            </button>
                                            {{-- --- FIN: NUEVO BOTÓN Y MODAL --- --}}


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

                                    {{-- --- INICIO: DEFINICIÓN DEL MODAL --- --}}
                                    {{-- Este modal se crea por cada reserva en el bucle --}}
                                    <x-modal name="booking-details-{{ $booking->id }}" maxWidth="xl" focusable>
                                        <div class="p-6 dark:bg-gray-800">
                                            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                                Detalles de la Reserva
                                            </h2>

                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                Experiencia: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $booking->experience->title }}</span>
                                            </p>

                                            {{-- Información del Turista --}}
                                            <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Información del Turista</h3>
                                                <div class="mt-2 space-y-1 text-gray-700 dark:text-gray-300">
                                                    <p><span class="font-semibold">Nombre:</span> {{ $booking->user->name }}</p>
                                                    <p><span class="font-semibold">Email:</span> {{ $booking->user->email }}</p>
                                                </div>
                                            </div>

                                            {{-- Detalles del Evento (Según lo solicitado) --}}
                                            <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Detalles del Evento</h3>
                                                <div class="mt-2 space-y-2 text-gray-700 dark:text-gray-300">
                                                    <p>
                                                        <span class="font-semibold block">Fecha y Hora de Encuentro:</span>
                                                        @if ($booking->availabilitySlot)
                                                            <span class="text-lg">{{ $booking->availabilitySlot->start_time->locale('es')->translatedFormat('l, j \de F \de Y - h:i A') }}</span>
                                                        @else
                                                            <span class="text-red-500">Horario no disponible</span>
                                                        @endif
                                                    </p>

                                                    {{-- /// --- CORRECCIÓN: Usar 'quantity' (columna correcta de la BD) --- /// --}}
                                                    <p>
                                                        <span class="font-semibold block">Cantidad de Turistas:</span>
                                                        <span class="text-lg">{{ $booking->num_travelers }} {{ Str::plural('turista', $booking->num_travelers) }}</span>
                                                    </p>
                                                    {{-- /// --- FIN CORRECCIÓN --- /// --}}

                                                    <p>
                                                        <span class="font-semibold block">Precio Total de la Reserva:</span>
                                                        <span class="text-lg">${{ number_format($booking->total_amount, 0, ',', '.') }} COP</span>
                                                    </p>
                                                    <p>
                                                        <span class="font-semibold block">Fecha en que se Reservó:</span>
                                                        <span class="text-lg">{{ $booking->created_at->locale('es')->translatedFormat('l, j \de F \de Y, h:i A') }}</span>
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- Botón de Cierre --}}
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                    Cerrar
                                                </x-secondary-button>
                                            </div>
                                        </div>
                                    </x-modal>
                                    {{-- --- FIN: DEFINICIÓN DEL MODAL --- --}}

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

