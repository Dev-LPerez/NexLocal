<?php // la ruta del el archivo del codigo: resources/views/experiences/edit.blade.php ?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Experiencia: {{ $experience->title }}
        </h2>
    </x-slot>

    {{-- v-- ESTA ES LA CORRECCIÓN --v --}}
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
        function imageUploadForm() {
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
                slots: @json($slotsData)
            };
        }
    </script>
    {{-- ^-- FIN DE LA CORRECCIÓN --^ --}}

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

                    {{-- Ahora el x-data="imageUploadForm()" encontrará la función --}}
                    <form action="{{ route('experiences.update', $experience) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="imageUploadForm()">
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
                            <div>
                                <x-input-label for="location" value="Ubicación *" />
                                <x-text-input type="text" name="location" id="location" class="mt-1 block w-full" placeholder="Ej: Cereté, Córdoba" :value="old('location', $experience->location)" required />
                                <x-input-error :messages="$errors->get('location')" class="mt-1"/>
                            </div>
                            <div>
                                <x-input-label for="price" value="Precio por persona (COP) *" />
                                <x-text-input type="number" name="price" id="price" step="100" min="0" class="mt-1 block w-full" placeholder="Ej: 50000" :value="old('price', $experience->price)" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-1"/>
                            </div>
                            <div>
                                <x-input-label for="duration" value="Duración *" />
                                <x-text-input type="text" name="duration" id="duration" class="mt-1 block w-full" placeholder="Ej: 3 horas / Medio día" :value="old('duration', $experience->duration)" required />
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

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Sube una foto atractiva si deseas reemplazar la actual (máx 2MB).</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-1"/>
                        </div>

                        {{-- HORARIOS DISPONIBLES --}}
                        <div class="space-y-4 pt-4 border-t dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Horarios Disponibles</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Añade o edita las fechas y horas específicas en que ofreces esta experiencia.</p>

                            <div class="space-y-3">
                                {{-- Ahora esto funcionará --}}
                                <template x-for="(slot, index) in slots" :key="index">
                                    <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-md border dark:border-gray-700">
                                        <input type="hidden" x-bind:name="`slots[${index}][id]`" x-model="slot.id">

                                        <div class="flex-1">
                                            <x-input-label x-bind:for="`slot_start_time_${index}`" value="Fecha y Hora de Inicio *" />
                                            <x-text-input type="datetime-local"
                                                          x-bind:name="`slots[${index}][start_time]`"
                                                          x-bind:id="`slot_start_time_${index}`"
                                                          class="mt-1 block w-full"
                                                          x-model="slot.start_time" required />
                                        </div>
                                        <div class="w-1/4">
                                            <x-input-label x-bind:for="`slot_max_slots_${index}`" value="Cupos *" />
                                            <x-text-input type="number"
                                                          x-bind:name="`slots[${index}][max_slots]`"
                                                          x-bind:id="`slot_max_slots_${index}`"
                                                          class="mt-1 block w-full" min="1"
                                                          x-model="slot.max_slots" required />
                                        </div>
                                        <div>
                                            <x-danger-button type="button" @click="slots.splice(index, 1)" class="mt-6 !p-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </x-danger-button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Y esto también funcionará --}}
                            <x-secondary-button type="button" @click="slots.push({ id: null, start_time: '', max_slots: 10 })">
                                + Agregar Horario
                            </x-secondary-button>
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
</x-app-layout>

{{-- Ya no se necesita el @push('scripts') ni el @php al final --}}

