<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Gestión de Usuarios
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
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 rounded shadow-sm flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <span class="font-bold">¡Éxito!</span>
                        <span class="block text-sm mt-1">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 rounded shadow-sm flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <div>
                        <span class="font-bold">Error:</span>
                        <span class="block text-sm mt-1">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Filtros y Búsqueda -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-6 mb-6">
                <form method="GET" action="{{ route('admin.users') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Búsqueda -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar por email o nombre</label>
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Buscar usuario..."
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <!-- Filtro por Rol -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol</label>
                        <select id="role"
                                name="role"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                            <option value="">Todos</option>
                            <option value="tourist" {{ request('role') == 'tourist' ? 'selected' : '' }}>Turistas</option>
                            <option value="guide" {{ request('role') == 'guide' ? 'selected' : '' }}>Guías</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                        </select>
                    </div>

                    <!-- Filtro por Suspendidos -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Buscar
                        </button>
                    </div>
                </form>

                <div class="mt-4 flex items-center space-x-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox"
                               name="suspended"
                               value="1"
                               {{ request('suspended') == '1' ? 'checked' : '' }}
                               onchange="this.form.submit()"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Solo mostrar suspendidos</span>
                    </label>
                </div>
            </div>

            <!-- Tabla de Usuarios -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rol</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ $user->is_suspended ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                    <!-- Usuario -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($user->profile_photo_path)
                                                <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                                                     src="{{ Storage::url($user->profile_photo_path) }}"
                                                     alt="{{ $user->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div class="ml-3">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: #{{ $user->id }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Email -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Registrado: {{ $user->created_at->format('d/m/Y') }}</div>
                                    </td>

                                    <!-- Rol -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('admin.users.changeRole', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            <select name="role"
                                                    onchange="if(confirm('¿Cambiar rol de este usuario?')) this.form.submit()"
                                                    class="text-sm px-2 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-gray-100
                                                           {{ $user->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                                           {{ $user->role === 'guide' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                                           {{ $user->role === 'tourist' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}">
                                                <option value="tourist" {{ $user->role === 'tourist' ? 'selected' : '' }}>Turista</option>
                                                <option value="guide" {{ $user->role === 'guide' ? 'selected' : '' }}>Guía</option>
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </form>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->is_suspended)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                </svg>
                                                Suspendido
                                            </span>
                                            @if($user->suspension_reason)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1" title="{{ $user->suspension_reason }}">
                                                    {{ Str::limit($user->suspension_reason, 30) }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Activo
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($user->role !== 'admin')
                                            @if($user->is_suspended)
                                                <!-- Restaurar -->
                                                <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            onclick="return confirm('¿Restaurar cuenta de {{ $user->name }}?')"
                                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 font-semibold inline-flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Restaurar
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Suspender -->
                                                <button type="button"
                                                        onclick="openSuspendModal({{ $user->id }}, '{{ $user->name }}')"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-semibold inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                    </svg>
                                                    Suspender
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-gray-400 text-xs">Protegido</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="mt-2">No se encontraron usuarios</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- Modal de Suspensión -->
    <div id="suspendModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                        Suspender Usuario
                    </h3>
                    <button onclick="closeSuspendModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="suspendForm" method="POST">
                    @csrf
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Vas a suspender la cuenta de <strong id="userName"></strong>.
                        Proporciona una razón clara para que el usuario la entienda.
                    </p>

                    <div class="mb-4">
                        <label for="suspension_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Razón de la Suspensión *
                        </label>
                        <textarea id="suspension_reason"
                                  name="suspension_reason"
                                  rows="4"
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                                  placeholder="Ej: Violación de términos de servicio - Comportamiento inapropiado en el chat con otros usuarios."></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Esta razón será visible para el usuario.
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="closeSuspendModal()"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md transition">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                            Confirmar Suspensión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openSuspendModal(userId, userName) {
            document.getElementById('suspendModal').classList.remove('hidden');
            document.getElementById('userName').textContent = userName;
            document.getElementById('suspendForm').action = '/admin/users/' + userId + '/suspend';
        }

        function closeSuspendModal() {
            document.getElementById('suspendModal').classList.add('hidden');
            document.getElementById('suspension_reason').value = '';
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('suspendModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeSuspendModal();
            }
        });
    </script>
</x-app-layout>

