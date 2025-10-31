<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dejar una Reseña') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    <h3 class="text-2xl font-semibold mb-2">Estás reseñando:</h3>
                    <p class="text-lg text-indigo-600 dark:text-indigo-400 font-medium mb-6">{{ $booking->experience->title }}</p>

                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-6" x-data="{ rating: 0 }">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                        {{-- Selector de Estrellas --}}
                        <div>
                            <x-input-label for="rating" value="Tu Calificación (1 a 5 estrellas) *" class="mb-2" />
                            <div class="flex space-x-2">
                                <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                    <button type="button" @click="rating = star" class="focus:outline-none">
                                        <svg class="h-10 w-10" :class="rating >= star ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.445a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.539 1.118l-3.367-2.445a1 1 0 00-1.175 0l-3.367 2.445c-.784.57-1.838-.197-1.539-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.07 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"></path>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                            {{-- Input oculto que se actualiza con Alpine --}}
                            <input type="hidden" name="rating" x-model="rating">
                            <x-input-error :messages="$errors->get('rating')" class="mt-2"/>
                        </div>

                        {{-- Comentario --}}
                        <div>
                            <x-input-label for="comment" value="Tu Comentario (Opcional)" />
                            <textarea name="comment" id="comment" rows="5" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Comparte tu experiencia con otros turistas: ¿qué fue lo mejor? ¿algún consejo?">{{ old('comment') }}</textarea>
                            <x-input-error :messages="$errors->get('comment')" class="mt-1"/>
                        </div>

                        {{-- Botón de envío --}}
                        <div class="flex justify-end pt-4 border-t dark:border-gray-700">
                            <x-primary-button>
                                Enviar Reseña
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

