<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Auditoría de Reservas
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                ← Volver al Panel
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase">Total</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 shadow rounded-lg p-4">
                    <div class="text-xs text-yellow-800 dark:text-yellow-400 uppercase">Pendientes</div>
                    <div class="text-2xl font-bold text-yellow-800 dark:text-yellow-400">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 shadow rounded-lg p-4">
                    <div class="text-xs text-blue-800 dark:text-blue-400 uppercase">Confirmadas</div>
                    <div class="text-2xl font-bold text-blue-800 dark:text-blue-400">{{ $stats['confirmed'] }}</div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 shadow rounded-lg p-4">
                    <div class="text-xs text-green-800 dark:text-green-400 uppercase">Completadas</div>
                    <div class="text-2xl font-bold text-green-800 dark:text-green-400">{{ $stats['completed'] }}</div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 shadow rounded-lg p-4">
                    <div class="text-xs text-red-800 dark:text-red-400 uppercase">Canceladas</div>
                    <div class="text-2xl font-bold text-red-800 dark:text-red-400">{{ $stats['cancelled'] }}</div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar Usuario</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o email..."
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100">
                            <option value="">Todos</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completada</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de Auditoría -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Turista</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Experiencia</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Guía</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Monto</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">#{{ $booking->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->user->email }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ Str::limit($booking->experience->title, 30) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->experience->location }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $booking->experience->user->name }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($booking->booking_date)
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $booking->booking_date->format('d/m/Y') }}</div>
                                    @else
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Sin fecha</div>
                                    @endif
                                    @if($booking->availabilitySlot)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->availabilitySlot->start_time }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">${{ number_format($booking->total_amount ?? 0) }}</div>
                                    @if($booking->num_travelers)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->num_travelers }} personas</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                        {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    No se encontraron reservas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $bookings->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

