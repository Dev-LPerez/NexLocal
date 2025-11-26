<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Panel de Administración
            </h2>
            <span class="px-3 py-1 text-xs font-bold text-white bg-red-600 rounded-full">
                ADMINISTRADOR
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Grid de Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Tarjeta 1: Usuarios Totales -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-lg rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium opacity-90">Total Usuarios</div>
                            <div class="mt-2 text-4xl font-bold">{{ $stats['total_users'] }}</div>
                            <div class="text-xs opacity-75 mt-1">{{ $stats['total_guides'] }} guías activos</div>
                        </div>
                        <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Tarjeta 2: Verificaciones Pendientes -->
                <div class="bg-gradient-to-br from-yellow-500 to-orange-500 overflow-hidden shadow-lg rounded-xl p-6 text-white relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium opacity-90">Por Verificar</div>
                            <div class="mt-2 text-4xl font-bold">{{ $stats['pending_verifications'] }}</div>
                            @if($stats['pending_verifications'] > 0)
                                <a href="{{ route('admin.verification') }}" class="text-xs underline opacity-90 mt-1 block hover:opacity-100">
                                    Ver cola →
                                </a>
                            @else
                                <div class="text-xs opacity-75 mt-1">¡Todo al día!</div>
                            @endif
                        </div>
                        <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    @if($stats['pending_verifications'] > 0)
                        <span class="absolute top-4 right-4 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                        </span>
                    @endif
                </div>

                <!-- Tarjeta 3: Total Reservas -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow-lg rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium opacity-90">Total Reservas</div>
                            <div class="mt-2 text-4xl font-bold">{{ $stats['total_bookings'] }}</div>
                            <div class="text-xs opacity-75 mt-1">{{ $stats['active_experiences'] }} experiencias</div>
                        </div>
                        <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Tarjeta 4: Ingresos Totales -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-lg rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium opacity-90">Ingresos Totales</div>
                            <div class="mt-2 text-4xl font-bold">${{ number_format($stats['revenue'], 0) }}</div>
                            <div class="text-xs opacity-75 mt-1">Reservas completadas</div>
                        </div>
                        <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Panel de Accesos Rápidos -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Accesos Rápidos
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Gestión de Usuarios -->
                    <a href="{{ route('admin.users') }}" class="group block p-4 bg-gray-50 dark:bg-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg border-2 border-transparent hover:border-blue-500 transition">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-gray-100">Usuarios</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Gestionar cuentas</div>
                            </div>
                        </div>
                    </a>

                    <!-- Moderación de Experiencias -->
                    <a href="{{ route('admin.experiences') }}" class="group block p-4 bg-gray-50 dark:bg-gray-700 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg border-2 border-transparent hover:border-purple-500 transition">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-gray-100">Experiencias</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Control de calidad</div>
                            </div>
                        </div>
                    </a>

                    <!-- Moderación de Reseñas -->
                    <a href="{{ route('admin.reviews') }}" class="group block p-4 bg-gray-50 dark:bg-gray-700 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg border-2 border-transparent hover:border-yellow-500 transition">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-gray-100">Reseñas</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Moderar contenido</div>
                            </div>
                        </div>
                    </a>

                    <!-- Auditoría de Reservas -->
                    <a href="{{ route('admin.audit.bookings') }}" class="group block p-4 bg-gray-50 dark:bg-gray-700 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg border-2 border-transparent hover:border-green-500 transition">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-gray-100">Auditoría</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Historial completo</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Estado del Sistema -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Estado del Sistema
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-blue-800 dark:text-blue-300 font-semibold">Sistema Operativo</span>
                        </div>
                        <div class="text-blue-600 dark:text-blue-400 text-xs mt-1">Todos los servicios funcionando</div>
                    </div>
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="text-green-800 dark:text-green-300 font-semibold">Seguridad Activa</span>
                        </div>
                        <div class="text-green-600 dark:text-green-400 text-xs mt-1">Sin amenazas detectadas</div>
                    </div>
                    <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="text-purple-800 dark:text-purple-300 font-semibold">Rendimiento</span>
                        </div>
                        <div class="text-purple-600 dark:text-purple-400 text-xs mt-1">Óptimo</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

