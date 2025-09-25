<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- AVISO DE VERIFICACIÓN PARA GUÍAS --}}
            @if (Auth::user()->role === 'guide' && !Auth::user()->identity_verified_at)
                <div class="p-4 mb-6 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                    <span class="font-medium">¡Acción requerida!</span> Tu cuenta de guía no está verificada.
                    <a href="{{ route('verification.create') }}" class="font-bold underline">Completa tu verificación aquí</a> para poder publicar experiencias.
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>