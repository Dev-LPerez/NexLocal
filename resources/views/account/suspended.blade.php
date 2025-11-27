<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cuenta Suspendida') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Alerta Principal de Suspensión -->
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-2xl font-bold text-red-800">
                                Tu cuenta ha sido suspendida
                            </h3>
                            <p class="mt-1 text-sm text-red-700">
                                Suspendida el {{ $suspended_at ? $suspended_at->format('d/m/Y H:i') : 'Fecha desconocida' }}
                            </p>
                        </div>
                    </div>

                    @if($reason)
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Motivo de la suspensión:</p>
                            <p class="text-gray-900">{{ $reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información sobre la suspensión -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h4 class="text-lg font-semibold mb-4 text-gray-800">¿Qué significa esto?</h4>

                    <div class="space-y-3 text-gray-600">
                        <div class="flex items-start">
                            <svg class="h-6 w-6 text-red-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <p><strong>No puedes crear nuevas experiencias</strong> ni publicar contenido</p>
                        </div>

                        <div class="flex items-start">
                            <svg class="h-6 w-6 text-red-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <p><strong>No puedes realizar nuevas reservas</strong> de experiencias</p>
                        </div>

                        <div class="flex items-start">
                            <svg class="h-6 w-6 text-red-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <p><strong>Tus experiencias existentes están ocultas</strong> y no aparecen en búsquedas</p>
                        </div>

                        <div class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <p><strong>Puedes ver tu perfil</strong> y tus reservas existentes</p>
                        </div>

                        <div class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <p><strong>Puedes contactar a soporte</strong> para resolver el problema</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cómo resolver la suspensión -->
            <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4 text-blue-900 flex items-center">
                        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        ¿Cómo puedo resolver esto?
                    </h4>

                    <div class="space-y-4 text-gray-700">
                        <p class="font-medium">Para restablecer tu cuenta, sigue estos pasos:</p>

                        <ol class="list-decimal list-inside space-y-2 ml-4">
                            <li>Revisa el motivo de la suspensión indicado arriba</li>
                            <li>Contacta a nuestro equipo de soporte</li>
                            <li>Proporciona cualquier información o aclaración necesaria</li>
                            <li>Espera la revisión de tu caso por parte del equipo</li>
                        </ol>

                        <div class="bg-white rounded-lg p-4 border border-blue-200 mt-4">
                            <p class="font-semibold text-gray-800 mb-2">📧 Contacto de Soporte:</p>
                            <p class="text-gray-600">Email: <a href="mailto:soporte@nexlocal.com" class="text-blue-600 hover:text-blue-800 underline">soporte@nexlocal.com</a></p>
                            <p class="text-gray-600 mt-1">Horario: Lunes a Viernes, 9:00 AM - 6:00 PM</p>
                            <p class="text-xs text-gray-500 mt-2">
                                En tu mensaje, incluye tu nombre de usuario: <strong>{{ $user->email }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones disponibles -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4 text-gray-800">Acciones disponibles</h4>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="mailto:soporte@nexlocal.com?subject=Solicitud%20de%20reactivación%20de%20cuenta&body=Hola,%0D%0A%0D%0AMi%20correo%20es:%20{{ $user->email }}%0D%0A%0D%0ADeseo%20solicitar%20información%20sobre%20la%20suspensión%20de%20mi%20cuenta."
                           class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Contactar Soporte
                        </a>

                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Ver Mi Perfil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center justify-center w-full px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Advertencia final -->
            <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Importante:</strong> Mientras tu cuenta esté suspendida, no podrás acceder a la mayoría de funciones de la plataforma. Contacta a soporte lo antes posible para resolver esta situación.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

