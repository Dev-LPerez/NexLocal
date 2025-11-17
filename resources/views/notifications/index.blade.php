<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notificaciones') }}
            </h2>
            @if(Auth::user()->unreadNotificationsCount() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                        Marcar todas como leídas
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($notifications->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No hay notificaciones</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Cuando tengas nuevas notificaciones, aparecerán aquí.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($notifications as $notification)
                                <div class="flex items-start p-4 rounded-lg border {{ $notification->is_read ? 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800' : 'border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-900/20' }} hover:shadow-md transition">
                                    <!-- Icono -->
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900">
                                            <span class="text-2xl">{{ $notification->icon ?? '🔔' }}</span>
                                        </span>
                                    </div>

                                    <!-- Contenido -->
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $notification->title }}
                                                </h3>
                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $notification->message }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>

                                            <!-- Acciones -->
                                            <div class="ml-4 flex items-center space-x-2">
                                                @if(!$notification->is_read)
                                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300" title="Marcar como leída">
                                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($notification->link)
                                                    <a href="{{ $notification->link }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300" title="Ver detalles">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                        </svg>
                                                    </a>
                                                @endif

                                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta notificación?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300" title="Eliminar">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

