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

                    <form action="{{ route('experiences.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="imageUploadForm()">
                        @csrf

                        {{-- Título y Categoría --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="title" value="Título de la Experiencia *" />
                                <x-text-input type="text" name="title" id="title" class="mt-1 block w-full" placeholder="Ej: Tour Gastronómico por el Mercado Central" :value="old('title')" required />
                                <x-input-error :messages="$errors->get('title')" class="mt-1"/>
                            </div>
                            <div>
                                <x-input-label for="category" value="Categoría *" />
                                <select name="category" id="category" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="" disabled {{ old('category') ? '' : 'selected' }}>Selecciona una categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category')" class="mt-1"/>
                            </div>
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <x-input-label for="description" value="Descripción Detallada *" />
                            <textarea name="description" id="description" rows="5" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Describe qué hace única tu experiencia, el itinerario, qué verán o harán los turistas..." required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1"/>
                        </div>

                        {{-- Ubicación, Precio y Duración --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="location" value="Ubicación *" />
                                <x-text-input type="text" name="location" id="location" class="mt-1 block w-full" placeholder="Ej: Cereté, Córdoba" :value="old('location')" required />
                                <x-input-error :messages="$errors->get('location')" class="mt-1"/>
                            </div>
                            <div>
                                <x-input-label for="price" value="Precio por persona (COP) *" />
                                <x-text-input type="number" name="price" id="price" step="100" min="0" class="mt-1 block w-full" placeholder="Ej: 50000" :value="old('price')" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-1"/>
                            </div>
                            <div>
                                <x-input-label for="duration" value="Duración *" />
                                <x-text-input type="text" name="duration" id="duration" class="mt-1 block w-full" placeholder="Ej: 3 horas / Medio día" :value="old('duration')" required />
                                <x-input-error :messages="$errors->get('duration')" class="mt-1"/>
                            </div>
                        </div>

                        {{-- Qué Incluye / No Incluye --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="includes" value="¿Qué Incluye?" />
                                <textarea name="includes" id="includes" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Escribe cada ítem en una línea nueva:&#10;- Transporte&#10;- Guía bilingüe&#10;- Degustación de comida">{{ old('includes') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Un ítem por línea. Esto se mostrará a los turistas.</p>
                                <x-input-error :messages="$errors->get('includes')" class="mt-1"/>
                            </div>

                            <div>
                                <x-input-label for="not_includes" value="¿Qué NO Incluye?" />
                                <textarea name="not_includes" id="not_includes" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Escribe cada ítem en una línea nueva:&#10;- Almuerzo&#10;- Propinas&#10;- Souvenirs">{{ old('not_includes') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Un ítem por línea. Esto ayuda a tener expectativas claras.</p>
                                <x-input-error :messages="$errors->get('not_includes')" class="mt-1"/>
                            </div>
                        </div>

                        {{-- Imagen Principal con Vista Previa --}}
                        <div>
                            <x-input-label for="image" value="Imagen Principal *" />
                            <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-violet-50 dark:file:bg-violet-900/50 file:text-violet-700 dark:file:text-violet-300
                                hover:file:bg-violet-100 dark:hover:file:bg-violet-800/50"
                                   accept="image/*"
                                   @change="previewImage($event)" required>

                            {{-- Vista previa --}}
                            <div x-show="imagePreview" class="mt-4">
                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vista Previa:</span>
                                <img :src="imagePreview" class="h-48 w-auto rounded-md object-cover border dark:border-gray-600">
                            </div>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Sube una foto atractiva que represente tu experiencia (máx 2MB).</p>

                            <x-input-error :messages="$errors->get('image')" class="mt-1"/>
                        </div>

                        {{-- HORARIOS DISPONIBLES (SECCIÓN CORREGIDA) --}}
                        <div class="space-y-4 pt-4 border-t dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Horarios Disponibles</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Añade las fechas y horas específicas en que ofreces esta experiencia, como "funciones de cine".</p>

                            <div class="space-y-3">
                                {{-- Esta es la línea 130 del stack trace, ahora con :key="index" --}}
                                <template x-for="(slot, index) in slots" :key="index">
                                    <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-md border dark:border-gray-700">
                                        <div class="flex-1">
                                            {{-- CORRECCIÓN: Usar x-bind:for y template literals (`) --}}
                                            <x-input-label x-bind:for="`slot_start_time_${index}`" value="Fecha y Hora de Inicio *" />
                                            <x-text-input type="datetime-local"
                                                          {{-- CORRECCIÓN: Usar x-bind:name y template literals (`) --}}
                                                          x-bind:name="`slots[${index}][start_time]`"
                                                          {{-- CORRECCIÓN: Usar x-bind:id y template literals (`) --}}
                                                          x-bind:id="`slot_start_time_${index}`"
                                                          class="mt-1 block w-full"
                                                          x-model="slot.start_time" required />
                                        </div>
                                        <div class="w-1/4">
                                            {{-- CORRECCIÓN: Usar x-bind:for y template literals (`) --}}
                                            <x-input-label x-bind:for="`slot_max_slots_${index}`" value="Cupos *" />
                                            <x-text-input type="number"
                                                          {{-- CORRECCIÓN: Usar x-bind:name y template literals (`) --}}
                                                          x-bind:name="`slots[${index}][max_slots]`"
                                                          {{-- CORRECCIÓN: Usar x-bind:id y template literals (`) --}}
                                                          x-bind:id="`slot_max_slots_${index}`"
                                                          class="mt-1 block w-full" min="1"
                                                          x-model="slot.max_slots" required />
                                        </div>
                                        <div>
                                            <x-danger-button type="button" @click="slots.splice(index, 1)" x-show="slots.length > 1" class="mt-6 !p-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </x-danger-button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <x-secondary-button type="button" @click="slots.push({ start_time: '', max_slots: 10 })">
                                + Agregar Horario
                            </x-secondary-button>
                        </div>
                        {{-- FIN SECCIÓN CORREGIDA --}}

                        {{-- Botón de envío --}}
                        <div class="flex justify-end pt-4 border-t dark:border-gray-700">
                            <x-primary-button>
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
    function imageUploadForm() {
        return {
            imagePreview: '',
            previewImage(event) {
                const input = event.target;
                if (input.files && input.files[0]) {
                    this.imagePreview = URL.createObjectURL(input.files[0]);
                } else {
                    this.imagePreview = '';
                }
            },
            slots: [{ start_time: '', max_slots: 10 }]
        };
    }
    </script>
    @endpush
</x-app-layout>

