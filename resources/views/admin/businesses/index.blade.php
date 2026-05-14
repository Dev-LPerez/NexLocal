<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Moderación de Negocios Locales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Buscador --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <form method="GET" action="{{ route('admin.businesses') }}" class="w-full md:w-1/2 flex">
                    <x-text-input name="search" value="{{ request('search') }}" class="w-full rounded-r-none" placeholder="Buscar negocio por nombre..." />
                    <x-primary-button class="rounded-l-none">Buscar</x-primary-button>
                </form>
            </div>

            {{-- Lista de Negocios --}}
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Negocio</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Propietario</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Categoría</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($businesses as $business)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $business->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $business->owner->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $business->owner->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $business->category }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($business->status === 'open')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Abierto
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            Cerrado (Oculto)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('admin.businesses.status', $business->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $business->status === 'open' ? 'closed' : 'open' }}">
                                        <button type="submit" class="{{ $business->status === 'open' ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                            {{ $business->status === 'open' ? 'Forzar Cierre' : 'Abrir Negocio' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        
                        @if ($businesses->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No hay negocios registrados.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-4">
                {{ $businesses->links() }}
            </div>

        </div>
    </div>
</x-app-layout>