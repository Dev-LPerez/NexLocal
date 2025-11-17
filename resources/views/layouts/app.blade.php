<!DOCTYPE html>
{{-- Configuración inicial para dark mode con AlpineJS --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8"> {{-- Asegurado UTF-8 --}}
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Título dinámico o default --}}
        <title>{{ config('app.name', 'NexLocal') }} - Experiencias Auténticas</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts y Estilos de Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js CDN para funcionalidad de formularios -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-background dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <div class="min-h-screen flex flex-col"> {{-- Flex column para footer (si lo hubiera) --}}
            {{-- Incluye la barra de navegación --}}
            @include('layouts.navigation')

            {{-- Slot para el Header (si se define en la vista hija) --}}
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Contenido Principal --}}
            <main class="flex-grow"> {{-- flex-grow para empujar footer hacia abajo --}}
                {{-- Sección para mostrar mensajes flash (RF-018 web notification) --}}
                {{-- Se muestra solo si existe una sesión flash con la clave correspondiente --}}
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4 space-y-3"> {{-- Contenedor para mensajes --}}
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="p-4 text-sm text-green-800 rounded-lg bg-green-100 dark:bg-green-900 dark:text-green-300 border border-green-200 dark:border-green-700" role="alert">
                            <span class="font-medium">¡Éxito!</span> {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="p-4 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-red-900 dark:text-red-300 border border-red-200 dark:border-red-700" role="alert">
                            <span class="font-medium">¡Error!</span> {{ session('error') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="p-4 text-sm text-yellow-800 rounded-lg bg-yellow-100 dark:bg-yellow-900 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700" role="alert">
                            <span class="font-medium">¡Atención!</span> {{ session('warning') }}
                        </div>
                    @endif

                    {{-- Mostrar errores generales de validación si existen --}}
                    @if ($errors->any())
                        <div class="p-4 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-red-900 dark:text-red-300 border border-red-200 dark:border-red-700" role="alert">
                            <span class="font-medium">¡Ups!</span> Por favor corrige los siguientes errores:
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                {{-- Fin sección mensajes flash --}}

                {{-- Slot para el contenido principal de la vista hija --}}
                {{ $slot }}
            </main>

            {{-- Footer podría ir aquí si lo necesitas --}}
            {{-- <footer class="bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-auto">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    © {{ date('Y') }} {{ config('app.name', 'NexLocal') }}. Todos los derechos reservados.
                </div>
            </footer> --}}
        </div>

        {{-- Ventanas de Chat estilo Facebook --}}
        @auth
            @include('components.chat-windows')
        @endauth

        {{-- Scripts adicionales si son necesarios --}}
        @stack('scripts')
    </body>
</html>

