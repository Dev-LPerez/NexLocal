<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <div x-show="showProductModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showProductModal = false"></div>
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    <div x-show="showProductModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">Añadir Nuevo Producto</h3>
                        <div class="mt-4 space-y-4 text-left">
                            <div>
                                <x-input-label for="prod_name" value="Nombre del Producto" />
                                <x-text-input id="prod_name" name="name" type="text" class="mt-1 block w-full" required />
                            </div>
                            <div>
                                <x-input-label for="prod_desc" value="Descripción" />
                                <textarea id="prod_desc" name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm"></textarea>
                            </div>
                            <div>
                                <x-input-label for="prod_price" value="Precio" />
                                <x-text-input id="prod_price" name="price" type="number" class="mt-1 block w-full" required />
                            </div>
                            
                            <!-- Image Upload para Producto -->
                            <div class="pt-2">
                                <x-input-label value="Imágenes del Producto (Máx. 10 fotos, 10MB total)" />
                                <div class="mt-1 flex justify-center px-6 pt-4 pb-4 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-purple-500 dark:hover:border-purple-400 transition bg-gray-50 dark:bg-gray-800/50 cursor-pointer"
                                        onclick="document.getElementById('prod_images').click()">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-8 w-8 text-purple-400 dark:text-purple-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                            <span class="font-bold text-purple-600 dark:text-purple-400">Sube imágenes</span>
                                            <p class="pl-1">o arrástralas aquí</p>
                                        </div>
                                    </div>
                                    <input id="prod_images" name="prod_images[]" type="file" multiple accept="image/*" class="sr-only" @change="productImagesCount = $event.target.files.length">
                                </div>
                                <p x-show="productImagesCount > 0" class="text-xs text-purple-600 dark:text-purple-400 font-bold mt-2" x-text="productImagesCount + ' archivo(s) seleccionado(s)'"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <x-primary-button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Guardar
                </x-primary-button>
                <button type="button" @click="showProductModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>
