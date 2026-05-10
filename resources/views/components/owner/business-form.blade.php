<div x-show="!isRegistered" class="text-center py-16 px-4">
    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 mb-6 shadow-sm">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
    </div>
    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">Aún no has registrado tu local o emprendimiento</h3>
    <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-8">Comienza creando tu perfil comercial. Podrás recibir pedidos y mostrar tu ubicación a clientes locales.</p>
    
    <x-primary-button @click="isRegistered = true" class="bg-purple-600 hover:bg-purple-700 dark:bg-purple-600 dark:hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800 !py-3 !px-6 text-base shadow-md">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Registrar mi Negocio
    </x-primary-button>
</div>

<!-- Formulario de Edición -->
<div x-show="isRegistered" class="space-y-8" style="display: none;">
    <form action="{{ route('business.store') }}" method="POST">
        @csrf
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Información General</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Detalles principales de tu negocio que los clientes verán en tu perfil.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <x-input-label for="name" value="Nombre del Emprendimiento / Restaurante" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full focus:border-purple-500 focus:ring-purple-500" placeholder="Ej. El Buen Sabor" value="{{ $localBusiness->name ?? '' }}" />
            </div>
            
            <div class="md:col-span-2">
                <x-input-label for="description" value="Descripción detallada" />
                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-md shadow-sm" placeholder="Cuéntale a tus clientes qué hace especial a tu local...">{{ $localBusiness->description ?? '' }}</textarea>
            </div>

            <!-- Tipos Dinámicos -->
            <div>
                <x-input-label for="business_type" value="Tipo de Negocio" />
                <select id="business_type" x-model="businessType" name="business_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-md shadow-sm">
                    <option value="" disabled>Selecciona el tipo</option>
                    <option value="restaurante">Restaurante</option>
                    <option value="emprendimiento">Emprendimiento</option>
                </select>
            </div>

            <div>
                <x-input-label for="category" value="Categoría Específica" />
                <select id="category" x-model="category" name="category" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-md shadow-sm">
                    <option value="" disabled selected>Selecciona una categoría</option>
                    <template x-for="cat in availableCategories[businessType]" :key="cat">
                        <option :value="cat" x-text="cat" :selected="cat === category"></option>
                    </template>
                </select>
            </div>

            <div>
                <x-input-label for="price_range" value="Rango de Precios" />
                <select id="price_range" name="price_range" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-md shadow-sm">
                    <option value="1" {{ ($localBusiness->price_range ?? 1) == 1 ? 'selected' : '' }}>$ (Económico)</option>
                    <option value="2" {{ ($localBusiness->price_range ?? '') == 2 ? 'selected' : '' }}>$$ (Moderado)</option>
                    <option value="3" {{ ($localBusiness->price_range ?? '') == 3 ? 'selected' : '' }}>$$$ (Exclusivo)</option>
                </select>
            </div>

            <div class="md:col-span-2 lg:col-span-1" x-show="businessType === 'restaurante'">
                <x-input-label for="capacity" value="Capacidad de personas (Solo Restaurantes)" />
                <x-text-input id="capacity" name="capacity" type="number" class="mt-1 block w-full focus:border-purple-500 focus:ring-purple-500" placeholder="Ej. 50" min="1" value="{{ $localBusiness->capacity ?? '' }}" />
            </div>
        </div>

        <div class="pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
            <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Servicios Disponibles</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- WiFi Gratis -->
                <label class="relative flex cursor-pointer rounded-xl border-2 p-4 shadow-sm focus:outline-none transition-all duration-200"
                        :class="services.includes('wifi') ? 'border-purple-600 bg-purple-100 dark:bg-purple-900/30 ring-1 ring-purple-600' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-purple-300'">
                    <input type="checkbox" name="services[]" value="wifi" class="sr-only" x-model="services">
                    <div class="flex items-center w-full">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full mr-3 text-purple-600 dark:text-purple-400 transition-transform duration-200" :class="services.includes('wifi') ? 'bg-purple-200 dark:bg-purple-900/60 scale-110' : 'bg-purple-50 dark:bg-purple-900/20'">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">WiFi Gratis</span>
                    </div>
                </label>

                <!-- Parqueadero -->
                <label class="relative flex cursor-pointer rounded-xl border-2 p-4 shadow-sm focus:outline-none transition-all duration-200"
                        :class="services.includes('parking') ? 'border-purple-600 bg-purple-100 dark:bg-purple-900/30 ring-1 ring-purple-600' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-purple-300'">
                    <input type="checkbox" name="services[]" value="parking" class="sr-only" x-model="services">
                    <div class="flex items-center w-full">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full mr-3 text-purple-600 dark:text-purple-400 transition-transform duration-200" :class="services.includes('parking') ? 'bg-purple-200 dark:bg-purple-900/60 scale-110' : 'bg-purple-50 dark:bg-purple-900/20'">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Parqueadero</span>
                    </div>
                </label>

                <!-- A Domicilio -->
                <label class="relative flex cursor-pointer rounded-xl border-2 p-4 shadow-sm focus:outline-none transition-all duration-200"
                        :class="services.includes('delivery') ? 'border-purple-600 bg-purple-100 dark:bg-purple-900/30 ring-1 ring-purple-600' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-purple-300'">
                    <input type="checkbox" name="services[]" value="delivery" class="sr-only" x-model="services">
                    <div class="flex items-center w-full">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full mr-3 text-purple-600 dark:text-purple-400 transition-transform duration-200" :class="services.includes('delivery') ? 'bg-purple-200 dark:bg-purple-900/60 scale-110' : 'bg-purple-50 dark:bg-purple-900/20'">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">A Domicilio</span>
                    </div>
                </label>

                <!-- Pet Friendly -->
                <label class="relative flex cursor-pointer rounded-xl border-2 p-4 shadow-sm focus:outline-none transition-all duration-200"
                        :class="services.includes('pet_friendly') ? 'border-purple-600 bg-purple-100 dark:bg-purple-900/30 ring-1 ring-purple-600' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-purple-300'">
                    <input type="checkbox" name="services[]" value="pet_friendly" class="sr-only" x-model="services">
                    <div class="flex items-center w-full">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full mr-3 text-purple-600 dark:text-purple-400 transition-transform duration-200" :class="services.includes('pet_friendly') ? 'bg-purple-200 dark:bg-purple-900/60 scale-110' : 'bg-purple-50 dark:bg-purple-900/20'">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Pet Friendly</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700 mt-8">
            <x-primary-button type="submit" class="bg-purple-600 hover:bg-purple-700 dark:bg-purple-600 dark:hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800">
                Guardar Información
            </x-primary-button>
        </div>
    </form>
</div>
