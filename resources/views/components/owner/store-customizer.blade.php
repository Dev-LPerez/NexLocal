<div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Personalización de tu Tienda</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Diseña cómo se verá tu negocio para los clientes.</p>
        </div>
        <a href="#" class="inline-flex items-center text-sm font-semibold text-purple-600 hover:text-purple-700">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            Ver vista previa
        </a>
    </div>
</div>

<form action="{{ route('business.customize') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Columna Izquierda: Apariencia -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Banner y Bienvenida -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Apariencia Principal</h4>
                
                <div class="space-y-4">
                    <div>
                        <x-input-label value="Banner de la Tienda (Hero Image)" />
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-purple-500 transition cursor-pointer relative"
                             onclick="document.getElementById('banner-upload').click()">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="font-bold text-purple-600">Sube un banner</span>
                                <p class="text-xs text-gray-500">1200x400px recomendado</p>
                            </div>
                            <input id="banner-upload" name="banner_image" type="file" accept="image/*" class="sr-only">
                        </div>
                    </div>

                    <div>
                        <x-input-label for="welcome_message" value="Mensaje de Bienvenida" />
                        <x-text-input id="welcome_message" name="welcome_message" type="text" class="mt-1 block w-full" placeholder="Ej. ¡Bienvenidos a la mejor comida de la ciudad!" value="{{ $localBusiness->welcome_message ?? '' }}" />
                        <p class="text-xs text-gray-500 mt-1">Este texto aparecerá grande al entrar a tu tienda.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="color_primary" value="Color Principal" />
                            <div class="flex items-center mt-1">
                                <input type="color" id="color_primary" name="theme_colors[primary]" class="h-10 w-10 border-0 rounded p-0 cursor-pointer" value="{{ $localBusiness->theme_colors['primary'] ?? '#7c3aed' }}">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">El color de tus botones</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horarios -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Horarios de Atención</h4>
                
                @php
                    $days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
                    $hours = $localBusiness->operating_hours ?? [];
                @endphp
                <div class="space-y-3">
                    @foreach($days as $day)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center w-1/3">
                            <input type="checkbox" id="day_{{ $day }}" name="operating_hours[{{ $day }}][is_open]" value="1" class="rounded text-purple-600 focus:ring-purple-500" {{ isset($hours[$day]['is_open']) && $hours[$day]['is_open'] ? 'checked' : '' }}>
                            <label for="day_{{ $day }}" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">{{ $day }}</label>
                        </div>
                        <div class="flex items-center space-x-2 w-2/3">
                            <x-text-input type="time" name="operating_hours[{{ $day }}][open]" class="block w-full text-sm" value="{{ $hours[$day]['open'] ?? '08:00' }}" />
                            <span class="text-gray-500">a</span>
                            <x-text-input type="time" name="operating_hours[{{ $day }}][close]" class="block w-full text-sm" value="{{ $hours[$day]['close'] ?? '18:00' }}" />
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Columna Derecha: Configuración Extra -->
        <div class="space-y-8">
            
            <!-- Redes Sociales -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Redes Sociales</h4>
                
                <div class="space-y-4">
                    <div>
                        <x-input-label for="social_instagram" value="Instagram URL" />
                        <x-text-input id="social_instagram" name="social_links[instagram]" type="url" class="mt-1 block w-full text-sm" placeholder="https://instagram.com/tu_local" value="{{ $localBusiness->social_links['instagram'] ?? '' }}" />
                    </div>
                    <div>
                        <x-input-label for="social_facebook" value="Facebook URL" />
                        <x-text-input id="social_facebook" name="social_links[facebook]" type="url" class="mt-1 block w-full text-sm" placeholder="https://facebook.com/tu_local" value="{{ $localBusiness->social_links['facebook'] ?? '' }}" />
                    </div>
                    <div>
                        <x-input-label for="social_tiktok" value="TikTok URL" />
                        <x-text-input id="social_tiktok" name="social_links[tiktok]" type="url" class="mt-1 block w-full text-sm" placeholder="https://tiktok.com/@tu_local" value="{{ $localBusiness->social_links['tiktok'] ?? '' }}" />
                    </div>
                </div>
            </div>

            <!-- Métodos de Pago -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Métodos de Pago</h4>
                <p class="text-sm text-gray-500 mb-4">Selecciona los métodos que aceptas para pedidos.</p>
                
                @php
                    $methods = ['efectivo' => 'Efectivo', 'nequi' => 'Nequi', 'daviplata' => 'Daviplata', 'tarjeta' => 'Tarjeta (Datáfono)', 'transferencia' => 'Transferencia Bancaria'];
                    $activeMethods = $localBusiness->payment_methods ?? [];
                @endphp
                <div class="space-y-2">
                    @foreach($methods as $key => $label)
                    <label class="flex items-center">
                        <input type="checkbox" name="payment_methods[]" value="{{ $key }}" class="rounded text-purple-600 focus:ring-purple-500" {{ in_array($key, $activeMethods) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
        <x-primary-button type="submit" class="bg-purple-600 hover:bg-purple-700">
            Guardar Personalización
        </x-primary-button>
    </div>
</form>
