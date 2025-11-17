<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Crea una Nueva Experiencia
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg relative" role="alert">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <strong class="font-bold">¡Ups! Hubo algunos problemas:</strong>
                                    <ul class="mt-2 list-disc list-inside text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('experiences.store') }}" method="POST" enctype="multipart/form-data"
                          x-data="experienceWizard()"
                          @submit="loading = true">
                        @csrf

                        <!-- Wizard Progress -->
                        <x-form-wizard :steps="['Básico', 'Detalles', 'Inclusiones', 'Imagen', 'Horarios']" />

                        <!-- Step 1: Información Básica -->
                        <div x-show="currentStep === 1" class="space-y-6">
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Información Básica</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Cuéntanos sobre tu experiencia</p>
                            </div>

                            <div>
                                <x-input-label for="title" value="Título de la Experiencia *" />
                                <x-text-input type="text" name="title" id="title" class="mt-1 block w-full" placeholder="Ej: Tour Gastronómico por el Mercado Central" :value="old('title')" required />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Un título atractivo y descriptivo</p>
                                <x-input-error :messages="$errors->get('title')" class="mt-1"/>
                            </div>

                            <div>
                                <x-input-label for="category" value="Categoría *" />
                                <x-select-input name="category" id="category" class="mt-1" required placeholder="Selecciona una categoría">
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('category')" class="mt-1"/>
                            </div>

                            <div>
                                <x-input-label for="description" value="Descripción Detallada *" />
                                <x-textarea-input name="description" id="description" rows="6" class="mt-1" required showCounter maxlength="1000" placeholder="Describe qué hace única tu experiencia, el itinerario, qué verán o harán los turistas...">{{ old('description') }}</x-textarea-input>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Sé específico y emocionante (mín. 50 caracteres)</p>
                                <x-input-error :messages="$errors->get('description')" class="mt-1"/>
                            </div>
                        </div>

                        <!-- Step 2: Detalles -->
                        <div x-show="currentStep === 2" class="space-y-6" style="display: none;">
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detalles de la Experiencia</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Información práctica para los turistas</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="location" value="Ubicación *" />
                                    <x-input-with-icon type="text" name="location" id="location" class="mt-1" placeholder="Ej: Cereté, Córdoba" :value="old('location')" required>
                                        <x-slot name="icon">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </x-slot>
                                    </x-input-with-icon>
                                    <x-input-error :messages="$errors->get('location')" class="mt-1"/>
                                </div>

                                <div>
                                    <x-input-label for="price" value="Precio por Persona (COP) *" />
                                    <x-input-with-icon type="number" name="price" id="price" step="100" min="0" class="mt-1" placeholder="50000" :value="old('price')" required>
                                        <x-slot name="icon">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </x-slot>
                                    </x-input-with-icon>
                                    <x-input-error :messages="$errors->get('price')" class="mt-1"/>
                                </div>

                                <div>
                                    <x-input-label for="duration" value="Duración *" />
                                    <x-input-with-icon type="text" name="duration" id="duration" class="mt-1" placeholder="Ej: 3 horas" :value="old('duration')" required>
                                        <x-slot name="icon">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </x-slot>
                                    </x-input-with-icon>
                                    <x-input-error :messages="$errors->get('duration')" class="mt-1"/>
                                </div>
                            </div>

                            <div class="space-y-4 pt-4 border-t dark:border-gray-700">
                                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Punto de Encuentro (Opcional)</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Define un lugar exacto. Busca o haz clic en el mapa.</p>

                                <div>
                                    <x-input-label for="meeting_point_name" value="Nombre del Punto" />
                                    <x-text-input type="text" name="meeting_point_name" id="meeting_point_name" class="mt-1 block w-full" placeholder="Ej: Entrada principal del Parque" :value="old('meeting_point_name')" />
                                    <x-input-error :messages="$errors->get('meeting_point_name')" class="mt-1"/>
                                </div>

                                <div>
                                    <x-input-label for="map_search" value="Buscar dirección" />
                                    <x-text-input type="text" id="map_search" class="mt-1 block w-full" placeholder="Buscar en Google Maps..."/>
                                </div>

                                <div id="map" class="w-full h-96 rounded-lg border dark:border-gray-700 shadow-inner"></div>

                                <input type="hidden" name="meeting_point_lat" id="meeting_point_lat" value="{{ old('meeting_point_lat') }}">
                                <input type="hidden" name="meeting_point_lng" id="meeting_point_lng" value="{{ old('meeting_point_lng') }}">
                            </div>
                        </div>

                        <!-- Step 3: Inclusiones -->
                        <div x-show="currentStep === 3" class="space-y-6" style="display: none;">
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">¿Qué Incluye y Qué No?</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Sé claro para evitar confusiones</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="includes" value="✅ ¿Qué Incluye?" />
                                    <x-textarea-input name="includes" id="includes" rows="8" class="mt-1" placeholder="Escribe cada ítem en una línea nueva:&#10;- Transporte&#10;- Guía bilingüe&#10;- Degustación de comida&#10;- Seguro de accidentes">{{ old('includes') }}</x-textarea-input>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Un ítem por línea. Esto se mostrará a los turistas.</p>
                                    <x-input-error :messages="$errors->get('includes')" class="mt-1"/>
                                </div>

                                <div>
                                    <x-input-label for="not_includes" value="❌ ¿Qué NO Incluye?" />
                                    <x-textarea-input name="not_includes" id="not_includes" rows="8" class="mt-1" placeholder="Escribe cada ítem en una línea nueva:&#10;- Almuerzo&#10;- Propinas&#10;- Souvenirs&#10;- Transporte al punto de encuentro">{{ old('not_includes') }}</x-textarea-input>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Un ítem por línea. Esto ayuda a tener expectativas claras.</p>
                                    <x-input-error :messages="$errors->get('not_includes')" class="mt-1"/>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Imagen -->
                        <div x-show="currentStep === 4" class="space-y-6" style="display: none;">
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Imagen Principal</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Una buena foto puede marcar la diferencia</p>
                            </div>

                            <div>
                                <x-file-upload name="image" accept="image/*" maxSize="2MB" required />
                                <div class="mt-3 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="text-sm text-blue-700 dark:text-blue-300">
                                            <strong>Consejos para una buena foto:</strong>
                                            <ul class="mt-1 list-disc list-inside space-y-1">
                                                <li>Alta resolución (mín. 1200x800px)</li>
                                                <li>Bien iluminada</li>
                                                <li>Que muestre la actividad principal</li>
                                                <li>Sin marcas de agua</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('image')" class="mt-2"/>
                            </div>
                        </div>

                        <!-- Step 5: Horarios -->
                        <div x-show="currentStep === 5" class="space-y-6" style="display: none;">
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Horarios Disponibles</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Define cuándo ofreces esta experiencia</p>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(slot, index) in slots" :key="index">
                                    <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border dark:border-gray-700">
                                        <div class="flex-1">
                                            <x-input-label x-bind:for="`slot_start_time_${index}`" value="Fecha y Hora *" />
                                            <x-text-input type="datetime-local" x-bind:name="`slots[${index}][start_time]`" x-bind:id="`slot_start_time_${index}`" class="mt-1 block w-full" x-model="slot.start_time" required />
                                        </div>
                                        <div class="w-32">
                                            <x-input-label x-bind:for="`slot_max_slots_${index}`" value="Cupos *" />
                                            <x-text-input type="number" x-bind:name="`slots[${index}][max_slots]`" x-bind:id="`slot_max_slots_${index}`" class="mt-1 block w-full" min="1" x-model="slot.max_slots" required />
                                        </div>
                                        <div class="pt-6">
                                            <x-danger-button type="button" @click="slots.splice(index, 1)" x-show="slots.length > 1" class="!p-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </x-danger-button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <x-secondary-button type="button" @click="slots.push({ start_time: '', max_slots: 10 })" class="w-full justify-center">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Agregar Otro Horario
                            </x-secondary-button>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between pt-6 border-t dark:border-gray-700 mt-8">
                            <x-secondary-button
                                type="button"
                                @click="prevStep()"
                                x-show="currentStep > 1"
                                style="display: none;"
                            >
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Anterior
                            </x-secondary-button>

                            <div x-show="currentStep === 1"></div>

                            <x-secondary-button
                                type="button"
                                @click="nextStep()"
                                x-show="currentStep < 5"
                                class="ml-auto"
                            >
                                Siguiente
                                <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </x-secondary-button>

                            <x-primary-button
                                type="submit"
                                x-show="currentStep === 5"
                                style="display: none;"
                                class="ml-auto"
                            >
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Guardar Experiencia
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function experienceWizard() {
                return {
                    currentStep: 1,
                    loading: false,
                    slots: @json(old('slots') ? collect(old('slots')) : [['start_time' => '', 'max_slots' => 10]]),

                    nextStep() {
                        if (this.currentStep < 5) {
                            this.currentStep++;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    },

                    prevStep() {
                        if (this.currentStep > 1) {
                            this.currentStep--;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    }
                };
            }

            // --- Google Maps ---
            let map, marker;
            const defaultLocation = { lat: 8.74798, lng: -75.88143 };

            function initMap() {
                const latInput = document.getElementById('meeting_point_lat');
                const lngInput = document.getElementById('meeting_point_lng');
                const oldLat = parseFloat(latInput.value);
                const oldLng = parseFloat(lngInput.value);
                const initialLocation = (oldLat && oldLng && !isNaN(oldLat) && !isNaN(oldLng)) ? { lat: oldLat, lng: oldLng } : defaultLocation;

                map = new google.maps.Map(document.getElementById('map'), {
                    center: initialLocation,
                    zoom: 13,
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
                    if (!marker.getPosition()) {
                        marker.setPosition(event.latLng);
                    } else {
                        marker.setPosition(event.latLng);
                    }
                    updateInputs(event.latLng);
                });

                const searchInput = document.getElementById('map_search');
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

                    if (!marker.getPosition()) {
                        marker.setPosition(place.geometry.location);
                    } else {
                        marker.setPosition(place.geometry.location);
                    }
                    updateInputs(place.geometry.location);

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

        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMpMyjTMPg7JWsAT4s9UpAPjT6cjvxBjk&libraries=places&callback=initMap"></script>
    @endpush
</x-app-layout>
