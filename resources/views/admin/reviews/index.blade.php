<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
                Moderación de Reseñas
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                ← Volver al Panel
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 rounded flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Búsqueda -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-6">
                <form method="GET" class="flex gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar en comentarios..."
                           class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Buscar
                    </button>
                </form>
            </div>

            <!-- Lista de Reseñas -->
            <div class="space-y-4">
                @forelse($reviews as $review)
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Rating -->
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                ⭐
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $review->rating }}/5
                                    </span>
                                </div>

                                <!-- Comentario -->
                                <p class="text-gray-900 dark:text-gray-100 mb-3">{{ $review->comment }}</p>

                                <!-- Info -->
                                <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                                    <div class="flex items-center">
                                        @if($review->user->profile_photo_path)
                                            <img src="{{ Storage::url($review->user->profile_photo_path) }}"
                                                 class="w-8 h-8 rounded-full mr-2" alt="">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs mr-2">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $review->user->name }}</span>
                                    </div>
                                    <span>•</span>
                                    <a href="{{ route('experiences.show', $review->experience_id) }}"
                                       class="text-indigo-600 dark:text-indigo-400 hover:underline"
                                       target="_blank">
                                        {{ $review->experience->title }}
                                    </a>
                                    <span>•</span>
                                    <span>{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Botón Eliminar -->
                            <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" class="ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('¿Eliminar esta reseña?')"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">No se encontraron reseñas</p>
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            @if($reviews->hasPages())
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
        </div>
    </div>

