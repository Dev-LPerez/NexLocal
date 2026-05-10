<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Ingresos Totales -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center space-x-4">
        <div class="p-3 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Ingresos Totales</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">$ {{ number_format($orders->whereNotIn('status', ['cancelled'])->sum('total_amount'), 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Pedidos Pendientes -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center space-x-4">
        <div class="p-3 rounded-full bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Pedidos Pendientes</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $orders->where('status', 'pending')->count() }}</p>
        </div>
    </div>

    <!-- Calificación Promedio -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center space-x-4">
        <div class="p-3 rounded-full bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Calificación</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">5.0 <span class="text-sm font-normal text-gray-500">(12 reseñas)</span></p>
        </div>
    </div>

    <!-- Productos Activos -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center space-x-4">
        <div class="p-3 rounded-full bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Productos Activos</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $products->where('is_available', true)->count() }}</p>
        </div>
    </div>
</div>
