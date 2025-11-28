<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                {{ __('Centro de Notificaciones') }}
            </h2>
            @if(Auth::user()->unreadNotificationsCount() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Marcar todo leído
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if($notifications->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center border border-gray-100 dark:border-gray-700">
                    <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Estás al día</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">No tienes notificaciones nuevas por el momento.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        <div class="relative pl-8 sm:pl-0 group">

                            {{-- Línea de tiempo visual (solo en escritorio) --}}
                            <div class="hidden sm:block absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700 group-last:bottom-auto group-last:h-1/2"></div>

                            <div class="relative sm:ml-12 bg-white dark:bg-gray-800 p-5 rounded-xl border transition-all duration-200
                                        {{ $notification->is_read
                                            ? 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'
                                            : 'border-indigo-200 dark:border-indigo-800 shadow-md ring-1 ring-indigo-50 dark:ring-indigo-900/20' }}">

                                {{-- Icono Flotante en la línea de tiempo --}}
                                <div class="hidden sm:flex absolute -left-12 top-5 w-8 h-8 rounded-full items-center justify-center border-2 border-white dark:border-gray-900 shadow-sm z-10
                                            {{ $notification->is_read ? 'bg-gray-100 text-gray-500' : 'bg-indigo-600 text-white' }}">
                                    <span class="text-sm">{{ $notification->icon ?? '🔔' }}</span>
                                </div>

                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            @if(!$notification->is_read)
                                                <span class="inline-block w-2 h-2 bg-indigo-600 rounded-full animate-pulse sm:hidden"></span>
                                            @endif
                                            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                                                {{ $notification->title }}
                                            </h3>
                                            <span class="text-xs text-gray-400 dark:text-gray-500 font-normal">
                                                • {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                            {{ $notification->message }}
                                        </p>

                                        @if($notification->link)
                                            <a href="{{ $notification->link }}" class="inline-flex items-center mt-3 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 group-hover:underline">
                                                Ver detalles
                                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Acciones --}}
                                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @if(!$notification->is_read)
                                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition" title="Marcar leída">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('¿Eliminar notificación?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-full transition" title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
