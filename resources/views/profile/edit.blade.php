<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- GRID DE DISEÑO --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- COLUMNA IZQUIERDA: Información del Perfil (Ocupa 2 espacios) --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg border-t-4 border-indigo-500">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: Seguridad y Zona de Peligro --}}
                <div class="space-y-6">
                    {{-- Actualizar Contraseña --}}
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg border-t-4 border-gray-400 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            Seguridad
                        </h3>
                        @include('profile.partials.update-password-form')
                    </div>

                    {{-- Eliminar Cuenta --}}
                    <div class="p-4 sm:p-8 bg-red-50 dark:bg-red-900/20 shadow sm:rounded-lg border border-red-100 dark:border-red-800">
                        <h3 class="text-lg font-medium text-red-600 dark:text-red-400 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            Zona de Peligro
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Una vez que elimines tu cuenta, no hay vuelta atrás. Por favor, asegúrate.
                        </p>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
