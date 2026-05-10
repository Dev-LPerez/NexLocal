<div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Gestión de Imágenes</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400">Sube fotos atractivas para destacar tu local.</p>
</div>

<form action="{{ route('business.images') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf
    
    <!-- Imagen Principal -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Imagen Principal (Avatar del Local)</label>
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-purple-500 dark:hover:border-purple-400 transition bg-gray-50 dark:bg-gray-800/50 cursor-pointer relative"
                onclick="document.getElementById('file-upload-main').click()">
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-purple-400 dark:text-purple-500" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                    <span class="relative rounded-md font-bold text-purple-600 dark:text-purple-400 hover:text-purple-500 px-1 pointer-events-none">Sube un archivo</span>
                    <p class="pl-1 pointer-events-none">o haz clic aquí</p>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG hasta 10MB total máximo (Recomendado 500x500px)</p>
            </div>
            <input id="file-upload-main" name="cover_image" type="file" accept="image/*" class="sr-only" @change="imagePreview($event, 'preview-main')">
        </div>
        <div id="preview-main" class="mt-4 {{ $localBusiness && $localBusiness->cover_image_path ? '' : 'hidden' }} w-32 h-32 rounded-lg overflow-hidden border">
            <img src="{{ $localBusiness && $localBusiness->cover_image_path ? asset('storage/' . $localBusiness->cover_image_path) : '' }}" class="w-full h-full object-cover" alt="Preview">
        </div>
    </div>

    <!-- Galería -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Galería de Imágenes</label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" id="gallery-container">
            @if($localBusiness && is_array($localBusiness->gallery_images))
                @foreach($localBusiness->gallery_images as $image)
                <div class="relative group rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 aspect-square shadow-sm">
                    <img src="{{ asset('storage/' . $image) }}" alt="Gallery" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="text-white hover:text-red-400 transition bg-red-600/80 p-2 rounded-full" onclick="document.getElementById('delete-gallery-{{ $loop->index }}').submit();">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            @endif

            @if($localBusiness && is_array($localBusiness->gallery_images))
                @foreach($localBusiness->gallery_images as $image)
                    <form id="delete-gallery-{{ $loop->index }}" action="{{ route('business.images.delete') }}" method="POST" class="hidden">
                        @csrf
                        <input type="hidden" name="image" value="{{ $image }}">
                    </form>
                @endforeach
            @endif
            
            <!-- Añadir más fotos -->
            <label class="flex items-center justify-center border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-purple-500 dark:hover:border-purple-400 transition bg-gray-50 dark:bg-gray-800/50 cursor-pointer aspect-square" for="gallery-upload">
                <div class="space-y-2 text-center">
                    <svg class="mx-auto h-8 w-8 text-purple-400 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="block text-sm font-bold text-gray-600 dark:text-gray-400">Añadir foto</span>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Máx 10 fotos (10MB total)</p>
                </div>
                <input id="gallery-upload" name="gallery_images[]" type="file" multiple accept="image/*" class="sr-only" @change="galleryImagesCount = $event.target.files.length">
            </label>
        </div>
        <p x-show="galleryImagesCount > 0" class="text-xs text-purple-600 dark:text-purple-400 font-bold mt-2" x-text="galleryImagesCount + ' foto(s) nueva(s) seleccionada(s)'"></p>
    </div>
    
    <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
        <x-primary-button type="submit" class="bg-purple-600 hover:bg-purple-700 dark:bg-purple-600 dark:hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800">
            Guardar Imágenes
        </x-primary-button>
    </div>
</form>
