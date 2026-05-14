<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard - Panel de Propietario') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="ownerDashboard()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Alerta de Verificación --}}
            @if(!Auth::user()->isVerifiedOwner())
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border-l-4 border-yellow-500 p-6 rounded-lg shadow-lg mb-8">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            @if(Auth::user()->verification_status === 'pending')
                                <h3 class="text-lg font-bold text-yellow-800 dark:text-yellow-300 mb-2">
                                    ⏳ Verificación en Proceso
                                </h3>
                                <p class="text-sm text-yellow-700 dark:text-yellow-400 mb-3">
                                    Hemos recibido tus documentos y están siendo revisados por nuestro equipo.
                                    Te notificaremos cuando tu cuenta sea verificada (generalmente en 24-48 horas).
                                </p>
                                <p class="text-xs text-yellow-600 dark:text-yellow-500">
                                    Mientras tanto, puedes configurar tu tienda, pero no estará visible al público hasta completar la verificación.
                                </p>
                            @elseif(Auth::user()->verification_status === 'rejected')
                                <h3 class="text-lg font-bold text-red-800 dark:text-red-300 mb-2">
                                    ❌ Verificación Rechazada
                                </h3>
                                <p class="text-sm text-red-700 dark:text-red-400 mb-2">
                                    <strong>Razón:</strong> {{ Auth::user()->rejection_reason ?? 'No se proporcionó una razón específica.' }}
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-400 mb-3">
                                    Por favor, revisa los documentos y envíalos nuevamente asegurándote de que coincidan con la titularidad del negocio.
                                </p>
                                <a href="{{ route('verification.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                                    📤 Enviar Nuevos Documentos
                                </a>
                            @else
                                <h3 class="text-lg font-bold text-yellow-800 dark:text-yellow-300 mb-2">
                                    📋 Verificación de Identidad Requerida
                                </h3>
                                <p class="text-sm text-yellow-700 dark:text-yellow-400 mb-3">
                                    Para que tu negocio sea visible y empiece a recibir pedidos, primero debes verificar tu identidad como propietario.
                                </p>
                                <a href="{{ route('verification.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Verificar Mi Negocio Ahora
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Contenedor Principal -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border dark:border-gray-700">

                @if($localBusiness)
                    <div class="p-6 md:p-8 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        @include('components.owner.stats-overview')
                    </div>
                @endif

                <!-- Navegación de Pestañas -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex overflow-x-auto overflow-y-hidden hide-scrollbar" aria-label="Tabs">
                        <button @click="activeTab = 'informacion'"
                                :class="activeTab === 'informacion' ? 'border-purple-600 text-purple-600 dark:text-purple-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="whitespace-nowrap flex py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Información
                        </button>

                        <button @click="activeTab = 'imagenes'"
                                :class="activeTab === 'imagenes' ? 'border-purple-600 text-purple-600 dark:text-purple-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="whitespace-nowrap flex py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Imágenes
                        </button>

                        <button @click="activeTab = 'ubicacion'"
                                :class="activeTab === 'ubicacion' ? 'border-purple-600 text-purple-600 dark:text-purple-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="whitespace-nowrap flex py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Contacto y Ubicación
                        </button>

                        <button @click="activeTab = 'productos'"
                                :class="activeTab === 'productos' ? 'border-purple-600 text-purple-600 dark:text-purple-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="whitespace-nowrap flex py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Productos
                        </button>

                        <button @click="activeTab = 'pedidos'"
                                :class="activeTab === 'pedidos' ? 'border-purple-600 text-purple-600 dark:text-purple-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="whitespace-nowrap flex py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Pedidos
                        </button>

                        <button @click="activeTab = 'personalizacion'"
                                :class="activeTab === 'personalizacion' ? 'border-purple-600 text-purple-600 dark:text-purple-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="whitespace-nowrap flex py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                            Personalizar Tienda
                        </button>
                    </nav>
                </div>

                <!-- Contenido de las Pestañas -->
                <div class="p-6 md:p-8">

                    <!-- ============================== -->
                    <!-- TAB 1: INFORMACIÓN             -->
                    <!-- ============================== -->
                    <div x-show="activeTab === 'informacion'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;">

                        @include('components.owner.business-form')
                    </div>

                    <!-- ============================== -->
                    <!-- TAB 2: IMÁGENES                -->
                    <!-- ============================== -->
                    <div x-show="activeTab === 'imagenes'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;">

                        @include('components.owner.image-manager')
                    </div>

                    <!-- ============================== -->
                    <!-- TAB 3: CONTACTO Y UBICACIÓN    -->
                    <!-- ============================== -->
                    <div x-show="activeTab === 'ubicacion'"
                         x-init="$watch('activeTab', value => { if(value === 'ubicacion') { setTimeout(() => { if(window.google && map) { google.maps.event.trigger(map, 'resize'); map.setCenter(marker.getPosition() || defaultLocation); } }, 200); } })"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;">

                        @include('components.owner.location-form')             </form>
                    </div>

                    <!-- ============================== -->
                    <!-- TAB 4: PRODUCTOS               -->
                    <!-- ============================== -->
                    <div x-show="activeTab === 'productos'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;">

                        @include('components.owner.product-grid')
                    </div>

                    <!-- ============================== -->
                    <!-- TAB 5: PEDIDOS                 -->
                    <!-- ============================== -->
                    <div x-show="activeTab === 'pedidos'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;">

                        @include('components.owner.order-table')
                    </div>

                    <!-- ============================== -->
                    <!-- TAB 6: PERSONALIZACIÓN         -->
                    <!-- ============================== -->
                    <div x-show="activeTab === 'personalizacion'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;">

                        @include('components.owner.store-customizer')
                    </div>

                </div>
            </div>
        </div>

        <!-- MODAL AGREGAR PRODUCTO (Alpine) -->
        @include('components.owner.product-modal')

    </div>

    @push('scripts')
        <script>
            function ownerDashboard() {
                return {
                    activeTab: 'informacion',
                    isRegistered: {{ $localBusiness ? 'true' : 'false' }},
                    businessType: '{{ $localBusiness->business_type ?? 'restaurante' }}',
                    category: '{{ $localBusiness->category ?? '' }}',
                    availableCategories: {
                        restaurante: ['Comida Típica', 'Pescados y Mariscos', 'Lácteos y Postres', 'Comida Rápida', 'Bebidas y Licores'],
                        emprendimiento: ['Artesanías', 'Ropa y Accesorios', 'Servicios Locales', 'Otros']
                    },
                    services: @json($localBusiness->services ?? []), // Inicializado con los servicios del backend
                    showProductModal: false,
                    productImagesCount: 0,
                    isEditingProduct: false,
                    currentProduct: {},

                    imagePreview(event, previewId) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                const previewBox = document.getElementById(previewId);
                                previewBox.classList.remove('hidden');
                                previewBox.querySelector('img').src = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    },

                    editProduct(product) {
                        this.isEditingProduct = true;
                        this.currentProduct = product;
                        this.showProductModal = true;
                    },

                    openCreateProduct() {
                        this.isEditingProduct = false;
                        this.currentProduct = {};
                        this.showProductModal = true;
                    },

                    deleteProduct(id) {
                        if(confirm('¿Estás seguro de eliminar este producto?')) {
                            let form = document.getElementById('delete-product-form');
                            if(!form) {
                                form = document.createElement('form');
                                form.method = 'POST';
                                form.style.display = 'none';

                                let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                let csrfInput = document.createElement('input');
                                csrfInput.type = 'hidden';
                                csrfInput.name = '_token';
                                csrfInput.value = csrfToken;
                                form.appendChild(csrfInput);

                                let methodInput = document.createElement('input');
                                methodInput.type = 'hidden';
                                methodInput.name = '_method';
                                methodInput.value = 'DELETE';
                                form.appendChild(methodInput);

                                document.body.appendChild(form);
                            }
                            form.action = `/dashboard/products/${id}`;
                            form.submit();
                        }
                    }
                }
            }

            // --- Google Maps ---
            let map, marker;
            const defaultLocation = { lat: 8.74798, lng: -75.88143 }; // Montería por defecto u otra

            function initMap() {
                const latInput = document.getElementById('lat');
                const lngInput = document.getElementById('lng');
                const oldLat = parseFloat(latInput.value);
                const oldLng = parseFloat(lngInput.value);
                const initialLocation = (oldLat && oldLng && !isNaN(oldLat) && !isNaN(oldLng)) ? { lat: oldLat, lng: oldLng } : defaultLocation;

                const mapElement = document.getElementById('map');
                if(!mapElement) return;

                map = new google.maps.Map(mapElement, {
                    center: initialLocation,
                    zoom: 15,
                });

                marker = new google.maps.Marker({
                    position: initialLocation,
                    map: map,
                    draggable: true
                });

                if (!oldLat || !oldLng || isNaN(oldLat) || isNaN(oldLng)) {
                    marker.setPosition(null);
                }

                google.maps.event.addListener(marker, 'dragend', function() {
                    updateInputs(marker.getPosition());
                });

                google.maps.event.addListener(map, 'click', function(event) {
                    marker.setPosition(event.latLng);
                    updateInputs(event.latLng);
                });

                const searchInput = document.getElementById('map_search');
                if(searchInput) {
                    const autocomplete = new google.maps.places.Autocomplete(searchInput);
                    autocomplete.bindTo('bounds', map);

                    autocomplete.addListener('place_changed', () => {
                        const place = autocomplete.getPlace();
                        if (!place.geometry || !place.geometry.location) {
                            window.alert("No se encontraron detalles para: '" + place.name + "'");
                            return;
                        }

                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                        marker.setPosition(place.geometry.location);
                        updateInputs(place.geometry.location);

                        const addressInput = document.getElementById('address');
                        if (addressInput && addressInput.value === '') {
                            addressInput.value = place.name;
                        }
                    });
                }
            }

            function updateInputs(latLng) {
                const latInput = document.getElementById('lat');
                const lngInput = document.getElementById('lng');
                if(latInput && lngInput) {
                    latInput.value = latLng.lat();
                    lngInput.value = latLng.lng();
                }
            }

            function openChatFromOrder(orderId, userName, orderStatus) {
                const conversation = {
                    id: orderId,
                    type: 'order',
                    booking_id: 'order_' + orderId, // backward compatibility
                    other_user: {
                        name: userName
                    },
                    experience_title: 'Pedido de ' + userName,
                    booking_status: orderStatus
                };
                window.dispatchEvent(new CustomEvent('open-chat-window', {
                    detail: conversation
                }));
            }
        </script>

        <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initMap"></script>
    @endpush
</x-app-layout>
