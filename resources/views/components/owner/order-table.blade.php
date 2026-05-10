<div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Gestión de Órdenes Reales</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400">Revisa los pedidos realizados por tus clientes locales.</p>
</div>

<!-- KPIs -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center space-x-4">
        <div class="p-3 rounded-full bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Pedidos</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $orders->count() }}</p>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-orange-200 dark:border-orange-900/30 flex items-center space-x-4 relative overflow-hidden">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-orange-400"></div>
        <div class="p-3 rounded-full bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Pendientes</p>
            <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $orders->where('status', 'pending')->count() }}</p>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-emerald-200 dark:border-emerald-900/30 flex items-center space-x-4 relative overflow-hidden">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-emerald-500"></div>
        <div class="p-3 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Confirmadas</p>
            <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $orders->where('status', 'confirmed')->count() }}</p>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cliente</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Detalles</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold mr-3">{{ substr($order->user->name ?? '?', 0, 1) }}</div>
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $order->user->name ?? 'Usuario Desconocido' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $order->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">$ {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                            <div class="text-xs text-purple-600 dark:text-purple-400 font-medium">{{ $order->items->count() }} Ítems</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->status === 'pending')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-orange-100 text-orange-800 border border-orange-200">Pendiente</span>
                            @elseif($order->status === 'confirmed')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">Confirmado</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-800 border border-gray-200">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="alert('Ver Detalles de Orden #{{ $order->id }} (Simulación)')" class="text-purple-600 hover:text-purple-900 font-bold transition bg-purple-50 px-4 py-2 rounded-lg">Ver Detalles</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No tienes pedidos aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
