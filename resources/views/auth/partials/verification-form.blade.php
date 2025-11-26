<form action="{{ route('verification.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Documento Frontal --}}
        <div>
            <x-input-label for="identity_document_front" value="📄 Documento Frontal *" class="mb-2" />
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-indigo-500 dark:hover:border-indigo-400 transition">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                        <label for="identity_document_front" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none">
                            <span>Subir archivo</span>
                            <input id="identity_document_front"
                                   name="identity_document_front"
                                   type="file"
                                   class="sr-only"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   onchange="updateFileName(this, 'front-file-name')"
                                   required />
                        </label>
                        <p class="pl-1">o arrastra aquí</p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">JPG, PNG, PDF hasta 5MB</p>
                    <p id="front-file-name" class="text-xs text-indigo-600 dark:text-indigo-400 font-medium mt-2"></p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('identity_document_front')" />
        </div>

        {{-- Documento Trasero --}}
        <div>
            <x-input-label for="identity_document_back" value="📄 Documento Trasero *" class="mb-2" />
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-indigo-500 dark:hover:border-indigo-400 transition">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                        <label for="identity_document_back" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none">
                            <span>Subir archivo</span>
                            <input id="identity_document_back"
                                   name="identity_document_back"
                                   type="file"
                                   class="sr-only"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   onchange="updateFileName(this, 'back-file-name')"
                                   required />
                        </label>
                        <p class="pl-1">o arrastra aquí</p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">JPG, PNG, PDF hasta 5MB</p>
                    <p id="back-file-name" class="text-xs text-indigo-600 dark:text-indigo-400 font-medium mt-2"></p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('identity_document_back')" />
        </div>
    </div>

    <div class="flex items-center justify-between pt-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
            </svg>
            Tus datos están protegidos y seguros
        </p>
        <x-primary-button>
            📤 Enviar Documentos
        </x-primary-button>
    </div>
</form>

<script>
    function updateFileName(input, displayId) {
        const display = document.getElementById(displayId);
        if (input.files && input.files[0]) {
            display.textContent = '✓ ' + input.files[0].name;
        }
    }
</script>

