<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('¡Pago Exitoso!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Animación de Éxito --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 dark:bg-green-900/30 rounded-full mb-4 animate-bounce-slow">
                    <svg class="w-16 h-16 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                    ¡Reserva Confirmada!
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Tu pago ha sido procesado exitosamente
                </p>
            </div>

            {{-- Detalles de la Reserva --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Detalles de tu Reserva
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Número de Reserva --}}
                        <div class="col-span-2 bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Número de Reserva</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>

                        {{-- Experiencia --}}
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Experiencia</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $booking->experience->title }}
                            </p>
                        </div>

                        {{-- Estado --}}
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Estado</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Pendiente de Confirmación
                            </span>
                        </div>

                        {{-- Fecha y Hora --}}
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Fecha y Hora</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $booking->availabilitySlot->start_time->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        {{-- Número de Viajeros --}}
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Viajeros</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $booking->num_travelers }} {{ Str::plural('persona', $booking->num_travelers) }}
                            </p>
                        </div>

                        {{-- Total Pagado --}}
                        <div class="col-span-2 border-t dark:border-gray-700 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    Total Pagado
                                </span>
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    ${{ number_format($booking->total_amount, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información de Pago --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Información de Pago
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Estado del Pago</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                ✓ Pagado
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Método de Pago</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100 capitalize">
                                {{ str_replace('_', ' ', $booking->payment_method) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">ID de Transacción</p>
                            <p class="font-mono text-sm text-gray-900 dark:text-gray-100">
                                {{ $booking->payment_intent_id }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Fecha de Pago</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ ($booking->paid_at ?? $booking->created_at)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Próximos Pasos --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Próximos Pasos
                </h3>
                <ol class="list-decimal list-inside space-y-2 text-blue-800 dark:text-blue-300">
                    <li>El guía revisará tu solicitud de reserva</li>
                    <li>Recibirás una notificación cuando sea confirmada</li>
                    <li>Podrás chatear con el guía desde "Mis Reservas"</li>
                    <li>Disfruta tu experiencia en la fecha programada</li>
                </ol>
            </div>

            {{-- Acciones --}}
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('bookings.index') }}" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Ver Mis Reservas
                </a>
                <a href="{{ route('home') }}" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-lg border border-gray-300 dark:border-gray-600 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Explorar Más Experiencias
                </a>
            </div>

            {{-- Mensaje de Email --}}
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    📧 Se ha enviado una copia de tu reserva a tu correo electrónico
                </p>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes bounce-slow {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        .animate-bounce-slow {
            animation: bounce-slow 2s ease-in-out infinite;
        }
    </style>
    @endpush
</x-app-layout>

