<?php // la ruta del el archivo del codigo: resources/views/experiences/edit.blade.php ?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Experiencia: {{ $experience->title }}
        </h2>
    </x-slot>

    {{-- Definimos los datos y la función de Alpine ANTES de que se use en el x-data --}}
    @php
        // Pre-procesamos la variable $slotsData para Alpine.js
        $slotsData = $experience->availabilitySlots->isNotEmpty()
            ? $experience->availabilitySlots->map(fn($slot) => [
                'id' => $slot->id,
                'start_time' => $slot->start_time->format('Y-m-d\TH:i'), // Formato correcto para datetime-local
                'max_slots' => $slot->max_slots
            ])
            : [[ 'id' => null, 'start_time' => '', 'max_slots' => 10 ]]; // Valor por defecto si no hay slots
    @endphp

    <script>
        // --- RENOMBRAMOS LA FUNCIÓN PARA MÁS CLARIDAD ---
        function experienceEditForm() {
            return {
                // Establece la imagen actual si existe, de lo contrario, string vacío
                imagePreview: '{{ $experience->image_path ? Storage::url($experience->image_path) : '' }}',

                previewImage(event) {
                    const input = event.target;
                    if (input.files && input.files[0]) {
                        this.imagePreview = URL.createObjectURL(input.files[0]);
                    }
                },

                // Usamos la variable $slotsData pre-procesada en PHP
                slots: @json($slotsData),

                // --- NUEVA LÓGICA PARA EL MAPA ---
                showMapEditor: false
                // --- FIN DE NUEVA LÓGICA ---
            };
        }
    </script>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    {{-- Mostrar errores de validación generales --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">¡Ups! Hubo algunos problemas:</strong>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- --- x-data AHORA USA LA FUNCIÓN RENOMBRADA --- --}}
                    <form action="{{ route('experiences.update', $experience) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="experienceEditForm()">
                        @csrf
                        @method('PUT')

                        {{-- Título y Categoría --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="title" value="Título de la Experiencia *" />
                                <x-text-input type="text" name="title" id="title" class="mt-1 block w-full" placeholder="Ej: Tour Gastronómico por el Mercado Central" :value="old('title', $experience->title)" required />
                                <x-input-error :messages="$errors->get('title')" class="mt-1"/>
                            </div>
                            <div>
                                <x-input-label for="category" value="Categoría *" />
                                <select name="category" id="category" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="" disabled {{ old('category', $experience->category) ? '' : 'selected' }}>Selecciona una categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('category', $experience->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category')" class="mt-1"/>
                            </div>
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <x-input-label for="description" value="Descripción Detallada *" />
                            <textarea name="description" id="description" rows="5" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Describe qué hace única tu experiencia..." required>{{ old('description', $experience->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1"/>
                        </div>

                        {{-- Ubicación, Precio y Duración --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- SELECCIÓN DE UBICACIÓN (DEPARTAMENTO Y MUNICIPIO) --}}
                            <div x-data="locationHandler('{{ old('location', $experience->location) }}')">
                                <x-input-label value="Ubicación *" class="mb-1" />

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    {{-- Select Departamento --}}
                                    <div>
                                        <select x-model="selectedDept" @change="updateMunicipalities()"
                                                class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">Departamento</option>
                                            <template x-for="dept in departments" :key="dept.id">
                                                <option :value="dept.departamento" x-text="dept.departamento"></option>
                                            </template>
                                        </select>
                                    </div>

                                    {{-- Select Municipio --}}
                                    <div>
                                        <select x-model="selectedCity" @change="updateFullLocation()"
                                                class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                :disabled="!selectedDept">
                                            <option value="">Municipio</option>
                                            <template x-for="city in municipalities" :key="city">
                                                <option :value="city" x-text="city"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                {{-- Input Oculto --}}
                                <input type="hidden" name="location" id="location" :value="fullLocation">
                                <x-input-error :messages="$errors->get('location')" class="mt-1"/>
                            </div>
                            <div>
                                <x-input-label for="price" value="Precio por persona (COP) *" />
                                <x-text-input type="number" name="price" id="price" step="100" min="0" class="mt-1 block w-full" placeholder="Ej: 50000" :value="old('price', $experience->price)" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-1"/>
                            </div>
                            {{-- CAMPO DURACIÓN (SELECT) --}}
                            <div>
                                <x-input-label for="duration" value="Duración *" />
                                <div class="relative mt-1">
                                    <select name="duration" id="duration" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm cursor-pointer" required>
                                        <option value="" disabled>Selecciona la duración</option>
                                        @foreach(['30 Minutos', '1 Hora', '1.5 Horas', '2 Horas', '2.5 Horas', '3 Horas', '4 Horas', '5 Horas', '6 Horas', '7 Horas', '8 Horas', '10 Horas', '12 Horas', 'Medio Día', 'Día Completo'] as $time)
                                            {{-- El select verifica si el valor guardado coincide con la opción para seleccionarla --}}
                                            <option value="{{ $time }}" {{ old('duration', $experience->duration) == $time ? 'selected' : '' }}>{{ $time }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('duration')" class="mt-1"/>
                            </div>
                        </div>

                        {{-- Qué Incluye / No Incluye --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="includes" value="¿Qué Incluye?" />
                                <textarea name="includes" id="includes" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Un ítem por línea...">{{ old('includes', is_array($experience->includes) ? implode("\n", $experience->includes) : '') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Un ítem por línea.</p>
                                <x-input-error :messages="$errors->get('includes')" class="mt-1"/>
                            </div>

                            <div>
                                <x-input-label for="not_includes" value="¿Qué NO Incluye?" />
                                <textarea name="not_includes" id="not_includes" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Un ítem por línea...">{{ old('not_includes', is_array($experience->not_includes) ? implode("\n", $experience->not_includes) : '') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Un ítem por línea.</p>
                                <x-input-error :messages="$errors->get('not_includes')" class="mt-1"/>
                            </div>
                        </div>

                        {{-- Imagen Principal con Vista Previa --}}
                        <div>
                            <x-input-label for="image" value="Imagen Principal (Opcional: cambiar)" />
                            <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-violet-50 dark:file:bg-violet-900/50 file:text-violet-700 dark:file:text-violet-300
                                hover:file:bg-violet-100 dark:hover:file:bg-violet-800/50"
                                   accept="image/*"
                                   @change="previewImage($event)">

                            <div x-show="imagePreview" class="mt-4">
                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vista Previa:</span>
                                <img :src="imagePreview" class="h-48 w-auto rounded-md object-cover border dark:border-gray-600">
                            </div>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Sube una foto atractiva si deseas reemplazar la actual (máx 10MB).</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-1"/>
                        </div>

                        <div class="space-y-4 pt-4 border-t dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Punto de Encuentro</h3>

                            <div x-show="!showMapEditor">
                                @if($experience->meeting_point_name || ($experience->meeting_point_lat && $experience->meeting_point_lng))
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Punto actual: <strong>{{ $experience->meeting_point_name ?? 'Coordenadas guardadas' }}</strong>
                                    </p>
                                @else
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        No se ha definido un punto de encuentro exacto.
                                    </p>
                                @endif
                                <x-secondary-button type="button" @click="showMapEditor = true; if(typeof initMap === 'function') initMap();" class="mt-2">
                                    Modificar Punto de Encuentro
                                </x-secondary-button>
                            </div>

                            <div x-show="showMapEditor" class="space-y-4" x-cloak>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Define un lugar exacto para encontrar a los turistas. Puedes buscar un lugar o hacer clic en el mapa.</p>

                                <div>
                                    <x-input-label for="meeting_point_name" value="Nombre del Punto de Encuentro" />
                                    <x-text-input type="text" name="meeting_point_name" id="meeting_point_name" class="mt-1 block w-full" placeholder="Ej: Entrada principal del Parque" :value="old('meeting_point_name', $experience->meeting_point_name)" />
                                    <x-input-error :messages="$errors->get('meeting_point_name')" class="mt-1"/>
                                </div>

                                <div>
                                    <x-input-label for="map_search" value="Buscar dirección" />
                                    <x-text-input type="text" id="map_search" class="mt-1 block w-full" placeholder="Buscar en Google Maps..."/>
                                </div>

                                <div id="map" class="w-full h-96 rounded-md border dark:border-gray-700"></div>

                                <input type="hidden" name="meeting_point_lat" id="meeting_point_lat" value="{{ old('meeting_point_lat', $experience->meeting_point_lat) }}">
                                <input type="hidden" name="meeting_point_lng" id="meeting_point_lng" value="{{ old('meeting_point_lng', $experience->meeting_point_lng) }}">

                                <x-input-error :messages="$errors->get('meeting_point_lat')" class="mt-1"/>
                                <x-input-error :messages="$errors->get('meeting_point_lng')" class="mt-1"/>

                                <x-secondary-button type="button" @click="showMapEditor = false">
                                    Ocultar Mapa
                                </x-secondary-button>
                            </div>
                        </div>
                        {{-- HORARIOS DISPONIBLES --}}
                        {{-- HORARIOS DISPONIBLES (DISEÑO MEJORADO) --}}
                        <div class="space-y-6 pt-8 border-t dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                        <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Horarios Disponibles
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        Define cuándo ocurrirá tu experiencia. Puedes agregar múltiples fechas.
                                    </p>
                                </div>
                                <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-semibold rounded-full border border-indigo-100 dark:border-indigo-800">
                                    <span x-text="slots.length"></span> Horarios activos
                                </span>
                            </div>

                            <div class="space-y-4">
                                {{-- Loop de Horarios con AlpineJS --}}
                                <template x-for="(slot, index) in slots" :key="index">
                                    <div class="group relative bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-600 transition-all duration-200">

                                        {{-- Botón Eliminar (Flotante) --}}
                                        <button type="button" @click="slots.splice(index, 1)"
                                                class="absolute top-4 right-4 text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400 transition-colors p-1 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20"
                                                title="Eliminar este horario">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>

                                        <input type="hidden" x-bind:name="`slots[${index}][id]`" x-model="slot.id">

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pr-8"> {{-- pr-8 para no tapar el botón borrar --}}

                                            {{-- Campo Fecha y Hora --}}
                                            <div>
                                                <x-input-label x-bind:for="`slot_start_time_${index}`" value="Fecha y Hora de Inicio" class="!text-gray-500 dark:!text-gray-400 !text-xs !uppercase !tracking-wider !font-bold !mb-2" />
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <x-text-input type="datetime-local"
                                                                  x-bind:name="`slots[${index}][start_time]`"
                                                                  x-bind:id="`slot_start_time_${index}`"
                                                                  class="pl-10 block w-full bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500"
                                                                  x-model="slot.start_time" required />
                                                </div>
                                            </div>

                                            {{-- Campo Cupos --}}
                                            <div>
                                                <x-input-label x-bind:for="`slot_max_slots_${index}`" value="Cupos Disponibles" class="!text-gray-500 dark:!text-gray-400 !text-xs !uppercase !tracking-wider !font-bold !mb-2" />
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0z" />
                                                        </svg>
                                                    </div>
                                                    <x-text-input type="number"
                                                                  x-bind:name="`slots[${index}][max_slots]`"
                                                                  x-bind:id="`slot_max_slots_${index}`"
                                                                  class="pl-10 block w-full bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500"
                                                                  min="1"
                                                                  placeholder="Ej: 10"
                                                                  x-model="slot.max_slots" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                {{-- Botón Agregar (Estilo Dashed) --}}
                                <button type="button"
                                        @click="slots.push({ id: null, start_time: '', max_slots: 10 })"
                                        class="w-full py-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-gray-500 dark:text-gray-400 hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all duration-200 flex flex-col items-center justify-center gap-2 group">
                                    <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-full group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/50 transition-colors">
                                        <svg class="w-6 h-6 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Agregar nueva fecha</span>
                                </button>
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="flex items-center justify-between pt-4 border-t dark:border-gray-700">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">
                                Cancelar
                            </a>
                            <x-primary-button>
                                Actualizar Experiencia
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let map;
            let marker;
            // Ubicación por defecto (Montería, Córdoba)
            const defaultLocation = { lat: 8.74798, lng: -75.88143 };
            let mapInitialized = false; // Flag para evitar reinicialización

            function initMap() {
                // Solo inicializa el mapa una vez, cuando se muestra
                // y comprueba que la API de google esté cargada
                if (mapInitialized || typeof google === 'undefined' || !document.getElementById('map')) {
                    return;
                }
                mapInitialized = true;

                const latInput = document.getElementById('meeting_point_lat');
                const lngInput = document.getElementById('meeting_point_lng');

                // Usamos los valores actuales (pueden ser 'old' o del modelo)
                const currentLat = parseFloat(latInput.value);
                const currentLng = parseFloat(lngInput.value);

                const initialLocation = (currentLat && currentLng && !isNaN(currentLat) && !isNaN(currentLng))
                    ? { lat: currentLat, lng: currentLng }
                    : defaultLocation;

                map = new google.maps.Map(document.getElementById('map'), {
                    center: initialLocation,
                    zoom: 13,
                });

                marker = new google.maps.Marker({
                    position: initialLocation,
                    map: map,
                    draggable: true // Permitir arrastrar el marcador
                });

                // Si no había valor válido, no poner el marcador hasta el primer clic
                if (!currentLat || !currentLng || isNaN(currentLat) || isNaN(currentLng)) {
                    marker.setPosition(null);
                }

                // Actualizar inputs cuando el marcador se arrastra
                google.maps.event.addListener(marker, 'dragend', function() {
                    updateInputs(marker.getPosition());
                });

                // Actualizar inputs cuando se hace clic en el mapa
                google.maps.event.addListener(map, 'click', function(event) {
                    if (!marker.getPosition()) { // Si es el primer clic, crea el marcador
                        marker.setPosition(event.latLng);
                    } else { // Si ya existe, solo lo mueve
                        marker.setPosition(event.latLng);
                    }
                    updateInputs(event.latLng);
                });

                // --- Autocompletado de Google Places ---
                const searchInput = document.getElementById('map_search');
                const autocomplete = new google.maps.places.Autocomplete(searchInput);
                autocomplete.bindTo('bounds', map); // Sesgar resultados a la vista del mapa

                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    if (!place.geometry || !place.geometry.location) {
                        // El usuario introdujo algo que no se pudo geolocalizar
                        window.alert("No se encontraron detalles para: '" + place.name + "'");
                        return;
                    }

                    // Si el lugar tiene geometría, centrar el mapa y mover el marcador
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);

                    if (!marker.getPosition()) {
                        marker.setPosition(place.geometry.location);
                    } else {
                        marker.setPosition(place.geometry.location);
                    }
                    updateInputs(place.geometry.location);

                    // Actualizar el nombre del punto de encuentro si está vacío
                    const nameInput = document.getElementById('meeting_point_name');
                    if (nameInput.value === '') {
                        nameInput.value = place.name;
                    }
                });
            }

            function updateInputs(latLng) {
                document.getElementById('meeting_point_lat').value = latLng.lat();
                document.getElementById('meeting_point_lng').value = latLng.lng();
            }
        </script>

        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places">
        </script>
    @endpush
</x-app-layout>
