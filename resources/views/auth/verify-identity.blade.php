<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Verificación de Identidad de Guía
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('status'))
                        <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-700 dark:text-green-400" role="alert">
                            <span class="font-medium">✅ {{ session('status') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-700 dark:text-red-400" role="alert">
                            <span class="font-medium">❌ {{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Estado: Verificación Aprobada --}}
                    @if(Auth::user()->verification_status === 'approved')
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-700 dark:text-green-400" role="alert">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-bold text-lg">¡Tu identidad ha sido verificada!</span>
                            </div>
                            <p>Ya puedes crear y gestionar tus experiencias turísticas sin restricciones.</p>
                            <div class="mt-3">
                                <a href="{{ route('experiences.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                                    Crear Mi Primera Experiencia →
                                </a>
                            </div>
                        </div>

                    {{-- Estado: Verificación Rechazada --}}
                    @elseif(Auth::user()->verification_status === 'rejected')
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-700 dark:text-red-400" role="alert">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-bold text-lg">Tu verificación fue rechazada</span>
                            </div>
                            <p class="mb-3"><strong>Razón:</strong> {{ Auth::user()->rejection_reason ?? 'No se proporcionó una razón específica.' }}</p>
                            <p class="mb-3">Por favor, revisa los documentos y vuelve a enviarlos asegurándote de que sean claros, legibles y correspondan a tu identidad.</p>
                        </div>

                        {{-- Mostrar formulario de nuevo envío --}}
                        @include('auth.partials.verification-form')

                    {{-- Estado: Verificación Pendiente --}}
                    @elseif(Auth::user()->verification_status === 'pending')
                        <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-700 dark:text-blue-400" role="alert">
                            <div class="flex items-center mb-2">
                                <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="font-bold">Tu verificación está en proceso</span>
                            </div>
                            <p>Ya hemos recibido tus documentos. Un administrador los revisará pronto y te notificaremos del resultado.</p>
                            <p class="mt-2 text-xs">Esto puede tomar entre 24-48 horas hábiles.</p>
                        </div>

                        {{-- Mostrar documentos enviados --}}
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold mb-3">Documentos Enviados:</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="text-center">
                                    <p class="text-sm mb-2">📄 Documento Frontal</p>
                                    <span class="text-xs text-gray-500">Enviado ✓</span>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm mb-2">📄 Documento Trasero</p>
                                    <span class="text-xs text-gray-500">Enviado ✓</span>
                                </div>
                            </div>
                        </div>

                    {{-- Estado: Sin Verificación --}}
                    @else
                        <div class="mb-6">
                            <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-700 dark:text-yellow-400" role="alert">
                                <span class="font-medium">⚠️ Verificación Requerida</span>
                                <p class="mt-1">Para garantizar la seguridad y confianza en nuestra plataforma, todos los guías deben verificar su identidad antes de crear experiencias.</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                                <h3 class="font-semibold mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    ¿Qué necesitas?
                                </h3>
                                <ul class="list-disc list-inside space-y-2 text-sm">
                                    <li>Foto o escaneo del <strong>frente de tu documento de identidad</strong> (cédula, pasaporte, DNI, etc.)</li>
                                    <li>Foto o escaneo del <strong>reverso de tu documento de identidad</strong></li>
                                    <li>Formatos aceptados: <strong>JPG, JPEG, PNG, PDF</strong></li>
                                    <li>Tamaño máximo por archivo: <strong>5MB</strong></li>
                                    <li>Asegúrate de que las imágenes sean <strong>claras y legibles</strong></li>
                                </ul>
                            </div>

                            @include('auth.partials.verification-form')
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

