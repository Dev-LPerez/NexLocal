<!-- Archivo: resources/views/admin/verify-guides.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                📋 Verificación de Guías
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                ← Volver al Panel
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Mensajes Flash -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 rounded shadow-sm">
                    <span class="font-bold">✅ ¡Éxito!</span>
                    <span class="block text-sm mt-1">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 rounded shadow-sm">
                    <span class="font-bold">❌ Error:</span>
                    <span class="block text-sm mt-1">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if($pendingGuides->isEmpty())
                        <!-- Estado Vacío -->
                        <div class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">¡Todo al día!</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                                No hay solicitudes pendientes de verificación. Cuando un guía envíe sus documentos, aparecerán aquí.
                            </p>
                        </div>
                    @else
                        <!-- Contador de Solicitudes -->
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-sm text-blue-800 dark:text-blue-300">
                                <span class="font-bold">{{ $pendingGuides->count() }}</span>
                                {{ $pendingGuides->count() === 1 ? 'solicitud pendiente' : 'solicitudes pendientes' }} de revisión
                            </p>
                        </div>

                        <!-- Lista de Solicitudes -->
                        <div class="space-y-6">
                            @foreach($pendingGuides as $guide)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:shadow-lg transition-shadow">
                                    <!-- Header del Guía -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center space-x-4">
                                            <!-- Avatar -->
                                            <div class="flex-shrink-0">
                                                @if($guide->profile_photo_path)
                                                    <img class="h-16 w-16 rounded-full object-cover border-2 border-indigo-500"
                                                         src="{{ asset('storage/' . $guide->profile_photo_path) }}"
                                                         alt="{{ $guide->name }}">
                                                @else
                                                    <div class="h-16 w-16 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xl font-bold">
                                                        {{ substr($guide->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Info del Guía -->
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $guide->name }}</h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $guide->email }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                    Registrado: {{ $guide->created_at->format('d/m/Y') }} •
                                                    Solicitud: {{ $guide->updated_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Badge de Estado -->
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            ⏳ Pendiente
                                        </span>
                                    </div>

                                    <!-- Documentos -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <!-- Documento Frontal -->
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">📄 Documento Frontal</span>
                                                @if($guide->identity_document_path)
                                                    <span class="text-xs text-green-600 dark:text-green-400">✓ Enviado</span>
                                                @endif
                                            </div>
                                            @if($guide->identity_document_path)
                                                <a href="{{ route('admin.download_document', ['id' => $guide->id, 'type' => 'front']) }}"
                                                   target="_blank"
                                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition w-full justify-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Ver/Descargar
                                                </a>
                                            @else
                                                <p class="text-xs text-red-500">No disponible</p>
                                            @endif
                                        </div>

                                        <!-- Documento Trasero -->
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">📄 Documento Trasero</span>
                                                @if($guide->identity_document_back_path)
                                                    <span class="text-xs text-green-600 dark:text-green-400">✓ Enviado</span>
                                                @endif
                                            </div>
                                            @if($guide->identity_document_back_path)
                                                <a href="{{ route('admin.download_document', ['id' => $guide->id, 'type' => 'back']) }}"
                                                   target="_blank"
                                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition w-full justify-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Ver/Descargar
                                                </a>
                                            @else
                                                <p class="text-xs text-red-500">No disponible</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Acciones -->
                                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <!-- Botón Rechazar con Modal -->
                                        <button type="button"
                                                onclick="openRejectModal({{ $guide->id }}, '{{ $guide->name }}')"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Rechazar
                                        </button>

                                        <!-- Botón Aprobar -->
                                        <form action="{{ route('admin.approve_guide', $guide->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    onclick="return confirm('¿Confirmas que los documentos son válidos y deseas verificar a {{ $guide->name }}?')"
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Aprobar Verificación
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Rechazo -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Rechazar Verificación
                    </h3>
                    <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="rejectForm" method="POST">
                    @csrf
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Vas a rechazar la solicitud de <strong id="guideName"></strong>.
                        Por favor, proporciona una razón clara para que el guía pueda corregir el problema.
                    </p>

                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Razón del Rechazo *
                        </label>
                        <textarea id="rejection_reason"
                                  name="rejection_reason"
                                  rows="4"
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                                  placeholder="Ej: El documento está borroso y no se puede leer la información claramente. Por favor, envía una imagen más nítida."></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Esta razón será enviada al guía vía notificación.
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="closeRejectModal()"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md transition">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                            Confirmar Rechazo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(guideId, guideName) {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('guideName').textContent = guideName;
            document.getElementById('rejectForm').action = '/admin/verification/' + guideId + '/reject';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejection_reason').value = '';
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('rejectModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeRejectModal();
            }
        });
    </script>
</x-app-layout>

