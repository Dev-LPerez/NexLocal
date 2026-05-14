<div x-show="orderModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div x-show="orderModalOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             @click="orderModalOpen = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="orderModalOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            
            <template x-if="currentOrder">
                <div>
                    <!-- Header -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-gray-100" id="modal-title">
                                Pedido #<span x-text="currentOrder.id"></span>
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Creado el <span x-text="new Date(currentOrder.created_at).toLocaleDateString('es-CO')"></span>
                            </p>
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-500" @click="orderModalOpen = false">
                            <span class="sr-only">Cerrar</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-4">
                        <!-- Detalles del Cliente -->
                        <div class="mb-6">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-2">Datos del Cliente</h4>
                            <div class="bg-gray-50 dark:bg-gray-900/30 p-4 rounded-lg flex items-center">
                                <div class="h-10 w-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold mr-4 text-lg">
                                    <span x-text="currentOrder.user && currentOrder.user.name ? currentOrder.user.name.substring(0,1) : '?'"></span>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-gray-100" x-text="currentOrder.user ? currentOrder.user.name : 'Desconocido'"></p>
                                    <p class="text-sm text-gray-500" x-text="currentOrder.user ? currentOrder.user.email : ''"></p>
                                    <template x-if="currentOrder.user">
                                        <button type="button" @click="openChatFromOrder(currentOrder.id, currentOrder.user.name, currentOrder.status)" class="mt-2 inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded-md text-xs font-bold transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                            Contactar Cliente
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen del Pedido -->
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-2">Artículos del Pedido</h4>
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Producto</th>
                                            <th class="px-4 py-2 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Cant.</th>
                                            <th class="px-4 py-2 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                        <template x-for="item in currentOrder.items" :key="item.id">
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    <span x-text="item.product ? item.product.name : 'Producto Eliminado'"></span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center text-gray-500 dark:text-gray-400" x-text="item.quantity"></td>
                                                <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-gray-100 font-bold" x-text="'$ ' + new Intl.NumberFormat('es-CO').format(item.unit_price * item.quantity)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-900/50">
                                        <tr>
                                            <th colspan="2" class="px-4 py-3 text-right text-sm font-bold text-gray-900 dark:text-gray-100">Total a Pagar:</th>
                                            <th class="px-4 py-3 text-right text-lg font-black text-purple-600 dark:text-purple-400" x-text="'$ ' + new Intl.NumberFormat('es-CO').format(currentOrder.total_amount)"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Acciones y Estado -->
                        <div class="mt-6">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-2">Cambiar Estado</h4>
                            
                            <form :action="`/dashboard/business/orders/${currentOrder.id}/status`" method="POST" class="flex flex-col sm:flex-row gap-3">
                                @csrf
                                <select name="status" class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-purple-500 focus:ring-purple-500" :value="currentOrder.status">
                                    <option value="pending">Pendiente</option>
                                    <option value="preparing">En Preparación</option>
                                    <option value="ready">Listo para Entregar/Recoger</option>
                                    <option value="delivered">Entregado Completado</option>
                                    <option value="cancelled">Rechazar / Cancelar</option>
                                </select>
                                
                                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-sm">
                                    Actualizar Pedido
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
