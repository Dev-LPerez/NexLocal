<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout - Pago Seguro') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Banner Informativo de Demo (OPCIONAL - Comentar en producción) --}}
            <div class="mb-6 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg p-4 shadow-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-white font-semibold">
                            🎓 Modo Demo - Pago Simulado
                        </p>
                        <p class="text-purple-100 text-sm">
                            Esta es una pasarela de pagos simulada para fines académicos. Usa cualquier número de tarjeta (ej: 4532 1234 5678 9010). No se procesarán cargos reales.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Columna Izquierda: Formulario de Pago --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    Pago Seguro Garantizado
                                </h3>
                            </div>

                            <form id="payment-form" class="space-y-6">
                                @csrf

                                {{-- Número de Tarjeta --}}
                                <div>
                                    <label for="card_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Número de Tarjeta
                                    </label>
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="card_number"
                                            name="card_number"
                                            maxlength="19"
                                            placeholder="1234 5678 9012 3456"
                                            class="block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 text-lg tracking-wider"
                                            required
                                        >
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        </div>
                                        <div id="card-type-icon" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            {{-- Iconos de tarjetas se añadirán aquí dinámicamente --}}
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        💳 Aceptamos Visa, Mastercard, American Express
                                    </p>
                                </div>

                                {{-- Nombre del Titular --}}
                                <div>
                                    <label for="card_holder" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nombre del Titular
                                    </label>
                                    <input
                                        type="text"
                                        id="card_holder"
                                        name="card_holder"
                                        placeholder="JUAN PÉREZ"
                                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 uppercase"
                                        required
                                    >
                                </div>

                                {{-- Fecha de Expiración y CVV --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Fecha de Expiración
                                        </label>
                                        <input
                                            type="text"
                                            id="expiry_date"
                                            name="expiry_date"
                                            placeholder="MM/AA"
                                            maxlength="5"
                                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 text-center text-lg"
                                            required
                                        >
                                    </div>
                                    <div>
                                        <label for="cvv" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            CVV
                                        </label>
                                        <input
                                            type="text"
                                            id="cvv"
                                            name="cvv"
                                            placeholder="123"
                                            maxlength="4"
                                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 text-center text-lg"
                                            required
                                        >
                                    </div>
                                </div>

                                {{-- Mensaje de Error --}}
                                <div id="error-message" class="hidden p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                    <p class="text-sm text-red-600 dark:text-red-400"></p>
                                </div>

                                {{-- Botón de Pago --}}
                                <div class="pt-4">
                                    <button
                                        type="submit"
                                        id="pay-button"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-150 ease-in-out flex items-center justify-center text-lg shadow-lg"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <span id="button-text">Pagar ${{ number_format($bookingData['total_amount'], 2) }}</span>
                                    </button>
                                </div>

                                {{-- Indicadores de Seguridad --}}
                                <div class="flex items-center justify-center space-x-4 pt-4 border-t dark:border-gray-700">
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Conexión Segura SSL
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Pago Verificado
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Columna Derecha: Resumen de Reserva --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Resumen de Reserva
                            </h3>

                            <div class="space-y-4">
                                {{-- Experiencia --}}
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Experiencia</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $bookingData['experience_title'] }}
                                    </p>
                                </div>

                                {{-- Fecha --}}
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Fecha y Hora</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $bookingData['booking_date'] }}
                                    </p>
                                </div>

                                {{-- Número de Viajeros --}}
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Número de Viajeros</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $bookingData['num_travelers'] }} {{ Str::plural('persona', $bookingData['num_travelers']) }}
                                    </p>
                                </div>

                                {{-- Separador --}}
                                <div class="border-t dark:border-gray-700 pt-4">
                                    {{-- Subtotal --}}
                                    <div class="flex justify-between mb-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            Precio por persona
                                        </span>
                                        <span class="text-sm text-gray-900 dark:text-gray-100">
                                            ${{ number_format($bookingData['experience_price'], 2) }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between mb-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            Cantidad
                                        </span>
                                        <span class="text-sm text-gray-900 dark:text-gray-100">
                                            × {{ $bookingData['num_travelers'] }}
                                        </span>
                                    </div>

                                    {{-- Total --}}
                                    <div class="flex justify-between pt-4 border-t dark:border-gray-700">
                                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                            Total
                                        </span>
                                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                            ${{ number_format($bookingData['total_amount'], 2) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Política de Cancelación --}}
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg mt-4">
                                    <p class="text-xs text-blue-800 dark:text-blue-300">
                                        <strong>Política de Cancelación:</strong><br>
                                        Cancelación gratuita hasta 24 horas antes de la experiencia.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Procesamiento --}}
    <div id="processing-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-md mx-4 text-center">
            <div class="mb-4">
                {{-- Spinner Animado --}}
                <svg class="animate-spin h-16 w-16 mx-auto text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                Procesando Pago Seguro...
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                Por favor, no cierres esta ventana.<br>
                Estamos verificando tu transacción.
            </p>
            <div class="mt-4 flex items-center justify-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                </svg>
                <span>Conexión encriptada</span>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Formatear número de tarjeta automáticamente y detectar tipo
        const cardNumberInput = document.getElementById('card_number');
        const cardTypeIcon = document.getElementById('card-type-icon');

        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;

            // Detectar tipo de tarjeta
            detectCardType(value);
        });

        function detectCardType(number) {
            let cardType = '';
            let icon = '';

            if (number.startsWith('4')) {
                cardType = 'Visa';
                icon = '💳 Visa';
            } else if (number.startsWith('5')) {
                cardType = 'Mastercard';
                icon = '💳 MC';
            } else if (number.startsWith('3')) {
                cardType = 'Amex';
                icon = '💳 Amex';
            } else if (number.length > 0) {
                icon = '💳';
            }

            cardTypeIcon.innerHTML = icon ? `<span class="text-sm text-gray-500 dark:text-gray-400">${icon}</span>` : '';
        }

        // Formatear fecha de expiración automáticamente
        const expiryInput = document.getElementById('expiry_date');
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });

        // Solo permitir números en CVV
        const cvvInput = document.getElementById('cvv');
        cvvInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        // Procesar el formulario de pago
        const paymentForm = document.getElementById('payment-form');
        const payButton = document.getElementById('pay-button');
        const buttonText = document.getElementById('button-text');
        const processingModal = document.getElementById('processing-modal');
        const errorMessage = document.getElementById('error-message');

        paymentForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Ocultar errores previos
            errorMessage.classList.add('hidden');

            // Validar formulario
            if (!paymentForm.checkValidity()) {
                paymentForm.reportValidity();
                return;
            }

            // Deshabilitar botón
            payButton.disabled = true;
            buttonText.textContent = 'Procesando...';

            // Mostrar modal de procesamiento
            processingModal.classList.remove('hidden');

            // Preparar datos
            const formData = new FormData(paymentForm);

            try {
                const response = await fetch('{{ route('checkout.process') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Redirigir a página de éxito
                    window.location.href = data.redirect_url;
                } else {
                    // Mostrar error
                    processingModal.classList.add('hidden');
                    errorMessage.querySelector('p').textContent = data.message;
                    errorMessage.classList.remove('hidden');
                    payButton.disabled = false;
                    buttonText.textContent = 'Pagar ${{ number_format($bookingData['total_amount'], 2) }}';
                }
            } catch (error) {
                processingModal.classList.add('hidden');
                errorMessage.querySelector('p').textContent = 'Error de conexión. Por favor, intenta de nuevo.';
                errorMessage.classList.remove('hidden');
                payButton.disabled = false;
                buttonText.textContent = 'Pagar ${{ number_format($bookingData['total_amount'], 2) }}';
            }
        });
    </script>
    @endpush
</x-app-layout>

