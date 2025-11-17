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

                    <!-- Experience Info Card -->
                    <div class="mb-8 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-12 w-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Estás reseñando:
                                </h3>
                                <p class="text-2xl text-indigo-600 dark:text-indigo-400 font-bold mt-1">
                                    {{ $booking->experience->title }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    Tu opinión ayuda a otros viajeros a tomar mejores decisiones
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                        <!-- Star Rating -->
                        <div>
                            <x-input-label for="rating" value="¿Cómo fue tu experiencia?" class="text-lg mb-4" />
                            <x-star-rating name="rating" :value="old('rating', 0)" size="large" />
                            <x-input-error :messages="$errors->get('rating')" class="mt-2"/>
                        </div>

                        <!-- Comment -->
                        <div>
                            <x-input-label for="comment" value="Cuéntanos más (Opcional)" class="text-lg" />
                            <x-textarea-input
                                name="comment"
                                id="comment"
                                rows="6"
                                class="mt-2"
                                showCounter
                                maxlength="500"
                                placeholder="¿Qué fue lo mejor? ¿Algún consejo para otros turistas? ¿Volverías a repetir esta experiencia?"
                            >{{ old('comment') }}</x-textarea-input>

                            <div class="mt-3 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="text-sm text-blue-700 dark:text-blue-300">
                                        <strong>💡 Consejos para una buena reseña:</strong>
                                        <ul class="mt-2 space-y-1 list-disc list-inside">
                                            <li>Sé específico sobre lo que más te gustó</li>
                                            <li>Menciona si cumplió tus expectativas</li>
                                            <li>Da consejos útiles para futuros viajeros</li>
                                            <li>Sé honesto pero respetuoso</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <x-input-error :messages="$errors->get('comment')" class="mt-2"/>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-6 border-t dark:border-gray-700">
                            <x-secondary-button as="a" :href="route('bookings.index')" class="mr-3">
                                Cancelar
                            </x-secondary-button>
                            <x-primary-button>
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Publicar Reseña
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Privacy Notice -->
            <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                <p>Tu reseña será pública y aparecerá en la página de la experiencia</p>
            </div>
        </div>
    </div>
</x-app-layout>

