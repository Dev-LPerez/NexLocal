@props(['name', 'accept' => 'image/*', 'maxSize' => '2MB', 'preview' => true, 'multiple' => false])

<div
    x-data="fileUpload()"
    class="w-full"
>
    <!-- Drag & Drop Zone -->
    <div
        @dragover.prevent="dragOver = true"
        @dragleave.prevent="dragOver = false"
        @drop.prevent="handleDrop($event)"
        :class="dragOver ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-600'"
        class="relative border-2 border-dashed rounded-lg p-6 transition-all duration-200 hover:border-indigo-400 dark:hover:border-indigo-500 cursor-pointer"
        @click="$refs.fileInput.click()"
    >
        <!-- Icon & Text -->
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="mt-4 flex flex-col sm:flex-row items-center justify-center text-sm text-gray-600 dark:text-gray-400">
                <label class="relative cursor-pointer rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none">
                    <span>Sube un archivo</span>
                </label>
                <p class="sm:pl-1">o arrastra y suelta</p>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                {{ $accept === 'image/*' ? 'PNG, JPG, GIF hasta ' . $maxSize : 'Hasta ' . $maxSize }}
            </p>
        </div>

        <!-- Hidden File Input -->
        <input
            type="file"
            x-ref="fileInput"
            name="{{ $name }}"
            accept="{{ $accept }}"
            @if($multiple) multiple @endif
            @change="handleFileSelect($event)"
            class="hidden"
            {{ $attributes }}
        >
    </div>

    <!-- Preview Section -->
    @if($preview)
        <div x-show="previewUrl" class="mt-4" style="display: none;">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vista Previa:</p>
            <div class="relative inline-block">
                <img
                    :src="previewUrl"
                    class="h-32 w-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-600 object-cover"
                    alt="Preview"
                >
                <button
                    type="button"
                    @click="clearFile()"
                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-lg transition duration-150"
                >
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- File Info -->
    <div x-show="fileName" class="mt-3 text-sm text-gray-600 dark:text-gray-400" style="display: none;">
        <span class="font-medium">Archivo seleccionado:</span>
        <span x-text="fileName"></span>
        <span x-show="fileSize" class="text-gray-500">
            (<span x-text="fileSize"></span>)
        </span>
    </div>
</div>

@push('scripts')
<script>
function fileUpload() {
    return {
        dragOver: false,
        previewUrl: null,
        fileName: '',
        fileSize: '',

        handleDrop(e) {
            this.dragOver = false;
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.processFile(files[0]);
                // Assign to input
                this.$refs.fileInput.files = files;
            }
        },

        handleFileSelect(e) {
            const file = e.target.files[0];
            if (file) {
                this.processFile(file);
            }
        },

        processFile(file) {
            this.fileName = file.name;
            this.fileSize = this.formatFileSize(file.size);

            // Create preview for images
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        clearFile() {
            this.previewUrl = null;
            this.fileName = '';
            this.fileSize = '';
            this.$refs.fileInput.value = '';
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    }
}
</script>
@endpush

