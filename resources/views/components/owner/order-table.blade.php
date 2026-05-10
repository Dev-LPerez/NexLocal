<div x-data="{ orderModalOpen: false, currentOrder: null }">
<div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Gestión de Órdenes Reales</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400">Revisa los pedidos realizados por tus clientes locales.</p>
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
                            <button @click="currentOrder = {{ json_encode($order) }}; orderModalOpen = true" class="text-purple-600 hover:text-purple-900 font-bold transition bg-purple-50 px-4 py-2 rounded-lg">Gestionar Pedido</button>
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

<!-- Modal Component -->
@include('components.owner.order-detail-modal')

</div>
