<div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Contacto y Ubicación</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400">Ayuda a tus clientes a encontrarte fácilmente con Google Maps.</p>
</div>

<form action="{{ route('business.location') }}" method="POST" class="space-y-8">
    @csrf
    
    <!-- Contact Info -->
    <div>
        <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Información de Contacto</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="phone" value="Teléfono / WhatsApp" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <x-text-input id="phone" name="phone" type="tel" class="pl-10 block w-full focus:border-purple-500 focus:ring-purple-500" placeholder="+57 300 000 0000" value="{{ $localBusiness->phone ?? '' }}" />
                </div>
            </div>
            <div>
                <x-input-label for="email" value="Correo Electrónico" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <x-text-input id="email" name="email" type="email" class="pl-10 block w-full focus:border-purple-500 focus:ring-purple-500" placeholder="contacto@ejemplo.com" value="{{ $localBusiness->email ?? '' }}" />
                </div>
            </div>
        </div>
    </div>

    <!-- Location & Map -->
    <div>
        <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Ubicación Física</h4>
        <div class="space-y-4">
            <div>
                <x-input-label for="map_search" value="Buscar dirección en Google Maps" />
                <x-text-input type="text" id="map_search" class="mt-1 block w-full" placeholder="Buscar mi local en el mapa..."/>
            </div>
            
            <!-- Map Container -->
            <div id="map" class="w-full h-96 bg-gray-100 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-inner"></div>

            <div>
                <x-input-label for="address" value="Dirección Escrita (Editable)" />
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" value="{{ $localBusiness->address ?? '' }}" placeholder="Ej. Calle 10 # 5-20, Centro Histórico" />
            </div>

            <input type="hidden" name="lat" id="lat" value="{{ $localBusiness->lat ?? '' }}">
            <input type="hidden" name="lng" id="lng" value="{{ $localBusiness->lng ?? '' }}">
        </div>
    </div>
    
    <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
        <x-primary-button type="submit" class="bg-purple-600 hover:bg-purple-700 dark:bg-purple-600 dark:hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800">
            Guardar Ubicación
        </x-primary-button>
    </div>
</form>
