<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
    <div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Catálogo de Productos</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Gestiona los platillos o artículos de tu negocio.</p>
    </div>
    <x-primary-button @click="openCreateProduct()" type="button" class="mt-4 sm:mt-0 bg-purple-600 hover:bg-purple-700 dark:bg-purple-600 dark:hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800 shadow-md">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Añadir Producto
    </x-primary-button>
</div>

@if($products->isEmpty())
    <div class="text-center py-12">
        <p class="text-gray-500 dark:text-gray-400 mb-4">Aún no tienes productos registrados en tu menú.</p>
        <button @click="openCreateProduct()" class="text-purple-600 font-bold hover:underline">Empieza agregando tu primer producto</button>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
            <!-- Card Producto -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow group flex flex-col">
                <div class="h-48 bg-gray-200 dark:bg-gray-700 relative overflow-hidden">
                    @if($product->image_path)
                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 {{ !$product->is_available ? 'grayscale opacity-70' : '' }}">
                    @else
                        <img src="https://placehold.co/400x300/e2e8f0/64748b?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 {{ !$product->is_available ? 'grayscale opacity-70' : '' }}">
                    @endif
                    <div class="absolute top-3 right-3 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm rounded-full px-3 py-1 shadow-sm text-sm font-bold text-purple-700 dark:text-purple-400">
                        $ {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-bold text-gray-900 dark:text-gray-100 text-lg truncate pr-2" :class="{ 'line-through text-gray-400': {{ $product->is_available ? 'false' : 'true' }} }">{{ $product->name }}</h4>
                        <!-- Toggle Availability button -->
                        <form action="{{ route('products.toggle', $product->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2 {{ $product->is_available ? 'bg-purple-600' : 'bg-gray-300' }}" role="switch" aria-checked="{{ $product->is_available ? 'true' : 'false' }}">
                                <span class="sr-only">Disponibilidad del producto</span>
                                <span aria-hidden="true" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $product->is_available ? 'translate-x-4' : 'translate-x-0' }}"></span>
                            </button>
                        </form>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 flex-1">{{ $product->description }}</p>
                    <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-700 pt-4 mt-auto">
                        <button @click="editProduct({{ json_encode($product) }})" class="flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-semibold text-sm transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Editar
                        </button>
                        <button @click="deleteProduct({{ $product->id }})" class="flex items-center text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-semibold text-sm transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
