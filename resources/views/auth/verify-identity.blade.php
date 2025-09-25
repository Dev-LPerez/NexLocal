<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Verificación de Identidad de Guía
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(Auth::user()->identity_document_path && !Auth::user()->identity_verified_at)
                        <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                            <span class="font-medium">Tu documento está en revisión.</span> Ya hemos recibido tu archivo. Te notificaremos cuando tu cuenta haya sido verificada.
                        </div>
                    @elseif(Auth::user()->identity_verified_at)
                         <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                            <span class="font-medium">¡Tu cuenta ha sido verificada!</span> Ya puedes crear y gestionar tus experiencias turísticas.
                        </div>
                    @else
                        <p class="mb-4">Para garantizar la seguridad y confianza en nuestra plataforma, todos los guías deben verificar su identidad. Por favor, sube una foto o un PDF de tu documento de identidad (cédula de ciudadanía, pasaporte, etc.).</p>

                        <form action="{{ route('verification.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div>
                                <x-input-label for="identity_document" value="Documento de Identidad" />
                                <x-text-input id="identity_document" name="identity_document" type="file" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('identity_document')" />
                            </div>

                            <div class="flex items-center gap-4 mt-4">
                                <x-primary-button>Subir Documento</x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>