<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard - Panel de Guía') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">

                {{-- Alerta de Verificación (Sin cambios) --}}
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
                                {{-- NUEVO ICONO DE EXPERIENCIAS (MAPA) --}}
                                <svg class="h-6 w-6 text-green-600 dark:text-green-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
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
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $guideBookings->total() }}</p>
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

                    {{-- FILTROS DE BÚSQUEDA CENTRADOS --}}
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border dark:border-gray-700 mb-4">
                        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col sm:flex-row justify-center items-center gap-4">

                            {{-- Input de Búsqueda --}}
                            <div class="w-full sm:w-auto">
                                <x-text-input
                                    type="text"
                                    name="search"
                                    placeholder="Buscar turista o experiencia..."
                                    value="{{ request('search') }}"
                                    class="w-full sm:w-64"
                                />
                            </div>

                            {{-- Select de Estado --}}
                            <div class="w-full sm:w-auto">
                                <select name="status" class="w-full sm:w-48 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Todos los estados</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En Curso</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completada</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                            </div>

                            {{-- Botones de Acción --}}
                            <div class="flex gap-2 w-full sm:w-auto justify-center">
                                <x-primary-button type="submit">
                                    Filtrar
                                </x-primary-button>

                                @if(request()->has('search') || request()->has('status'))
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition ease-in-out duration-150">
                                        Limpiar
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

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
                                        {{-- COLUMNA DE ACCIONES MEJORADA --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-col gap-2 min-w-[140px]">
                                                {{-- Botón para Ver Detalles --}}
                                                <button type="button"
                                                        x-data=""
                                                        x-on:click.prevent="$dispatch('open-modal', 'booking-details-{{ $booking->id }}')"
                                                        class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-xs font-medium">
                                                    Ver Detalles
                                                </button>

                                                {{-- Botón para abrir chat --}}
                                                @if(in_array($booking->status, ['pending', 'confirmed', 'in_progress', 'completed']))
                                                    <button type="button"
                                                            onclick="openChatFromBooking({{ $booking->id }}, '{{ $booking->user->name }}', '{{ $booking->experience->title }}', '{{ $booking->status }}')"
                                                            class="w-full inline-flex justify-center items-center gap-1.5 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition-colors text-xs font-semibold">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                        </svg>
                                                        Chatear
                                                    </button>
                                                @endif

                                                {{-- 1. Acciones para 'pending' --}}
                                                @if($booking->status === 'pending')
                                                    <form action="{{ route('bookings.status', $booking) }}" method="POST" class="w-full">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md shadow-sm transition-colors text-xs font-bold">
                                                            Confirmar Reserva
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('bookings.status', $booking) }}" method="POST" onsubmit="return confirm('¿Rechazar esta reserva? El cupo será devuelto.');" class="w-full">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1.5 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-xs font-medium">
                                                            Rechazar
                                                        </button>
                                                    </form>

                                                    {{-- 2. Acciones para 'confirmed' --}}
                                                @elseif($booking->status === 'confirmed' && $booking->availabilitySlot && $booking->availabilitySlot->start_time > now())
                                                    <form action="{{ route('bookings.status', $booking) }}" method="POST" class="w-full">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-md shadow-sm transition-colors text-xs font-bold">
                                                            Iniciar Experiencia
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('bookings.status', $booking) }}" method="POST" onsubmit="return confirm('¿Cancelar esta reserva? El cupo será devuelto.');" class="w-full">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1.5 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-xs font-medium">
                                                            Cancelar
                                                        </button>
                                                    </form>

                                                    {{-- 3. Acciones para 'in_progress' --}}
                                                @elseif($booking->status === 'in_progress')
                                                    @if($booking->guide_confirmed_completed)
                                                        <span class="w-full text-center text-xs text-gray-500 dark:text-gray-400 italic">Esperando confirmación del turista...</span>
                                                    @else
                                                        <form action="{{ route('bookings.markAsCompleted', $booking) }}" method="POST" onsubmit="return confirm('¿Confirmas que la experiencia ha finalizado?');" class="w-full">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm transition-colors text-xs font-bold">
                                                                Marcar Completada
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- 4. Estados Finales --}}
                                                @elseif($booking->status === 'completed')
                                                    <span class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 rounded-md text-xs">Finalizada</span>
                                                @elseif($booking->status === 'cancelled')
                                                    <span class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 rounded-md text-xs">Cancelada</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- --- MODAL DE DETALLES MEJORADO (DISEÑO PROFESIONAL) --- --}}
                                    <x-modal name="booking-details-{{ $booking->id }}" maxWidth="2xl" focusable>
                                        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">

                                            <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center">
                                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                                    <svg class="h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                                                    </svg>
                                                    Reserva #{{ $booking->id }}
                                                </h2>

                                                {{-- Badge Estado --}}
                                                <span class="px-3 py-1 text-sm font-bold rounded-full border {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                                    {{ $statusText[$booking->status] ?? ucfirst($booking->status) }}
                                                </span>
                                            </div>

                                            <div class="p-6 space-y-6">

                                                <div class="flex items-start gap-4 p-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-800/30">
                                                    <img class="w-20 h-20 rounded-lg object-cover flex-shrink-0"
                                                         src="{{ $booking->experience?->image_path ? asset('storage/' . $booking->experience->image_path) : 'https://placehold.co/100x100/e2e8f0/94a3b8?text=NexLocal' }}"
                                                         alt="Experiencia">
                                                    <div>
                                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 leading-tight mb-1">{{ $booking->experience->title }}</h3>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                            {{ $booking->experience->location }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                                    <div class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border dark:border-gray-700">
                                                        <h4 class="text-xs font-bold uppercase text-gray-400 dark:text-gray-500 mb-4 tracking-wider border-b dark:border-gray-700 pb-2">Turista</h4>

                                                        <div class="flex items-center gap-3 mb-4">
                                                            @if($booking->user->profile_photo_path)
                                                                <img src="{{ asset('storage/' . $booking->user->profile_photo_path) }}" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                                                            @else
                                                                <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg">
                                                                    {{ substr($booking->user->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <p class="font-bold text-gray-900 dark:text-gray-100">{{ $booking->user->name }}</p>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400 break-all">{{ $booking->user->email }}</p>
                                                            </div>
                                                        </div>

                                                        @if(in_array($booking->status, ['pending', 'confirmed', 'in_progress', 'completed']))
                                                            <button type="button"
                                                                    onclick="openChatFromBooking({{ $booking->id }}, '{{ $booking->user->name }}', '{{ $booking->experience->title }}', '{{ $booking->status }}'); $dispatch('close');"
                                                                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                                                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                                                Enviar Mensaje
                                                            </button>
                                                        @endif
                                                    </div>

                                                    <div class="space-y-4">
                                                        <h4 class="text-xs font-bold uppercase text-gray-400 dark:text-gray-500 mb-2 tracking-wider border-b dark:border-gray-700 pb-2">Detalles del Evento</h4>

                                                        <div class="flex items-start gap-3">
                                                            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg text-indigo-600 flex-shrink-0">
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                            </div>
                                                            <div>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400">Fecha y Hora</p>
                                                                @if ($booking->availabilitySlot)
                                                                    <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm">
                                                                        {{ $booking->availabilitySlot->start_time->locale('es')->translatedFormat('l, j \de F') }}
                                                                    </p>
                                                                    <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                                                        {{ $booking->availabilitySlot->start_time->format('h:i A') }}
                                                                    </p>
                                                                @else
                                                                    <span class="text-red-500 text-sm font-medium">Horario no disponible</span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-3">
                                                            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 flex-shrink-0">
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                                            </div>
                                                            <div>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400">Asistentes</p>
                                                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $booking->num_travelers }} Personas</p>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-3">
                                                            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg text-emerald-600 flex-shrink-0">
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                            </div>
                                                            <div>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400">Total Recibido</p>
                                                                <p class="font-bold text-emerald-600 dark:text-emerald-400 text-lg">$ {{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="text-xs text-center text-gray-400 pt-2">
                                                    Reserva realizada el {{ $booking->created_at->locale('es')->translatedFormat('d M Y, h:i A') }}
                                                </div>

                                            </div>

                                            <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                    Cerrar
                                                </x-secondary-button>
                                            </div>
                                        </div>
                                    </x-modal>
                                    {{-- --- FIN MODAL MEJORADO --- --}}

                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            No se encontraron reservas con los filtros seleccionados.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
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
                const conversation = {
                    id: bookingId,
                    type: 'booking',
                    booking_id: 'booking_' + bookingId,
                    other_user: {
                        name: userName
                    },
                    experience_title: experienceTitle,
                    booking_status: bookingStatus
                };
                window.dispatchEvent(new CustomEvent('open-chat-window', {
                    detail: conversation
                }));
            }
        </script>
    @endpush
</x-app-layout>
