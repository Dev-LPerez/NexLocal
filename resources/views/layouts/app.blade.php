<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="darkMode && document.documentElement.classList.add('dark')"
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

            {{-- Banner de Cuenta Suspendida --}}
            @auth
                @if(auth()->user()->isSuspended())
                    <div class="bg-red-600 text-white">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            <div class="flex items-center justify-between flex-wrap">
                                <div class="flex-1 flex items-center">
                                    <span class="flex p-2 rounded-lg bg-red-800">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </span>
                                    <p class="ml-3 font-medium">
                                        <span class="md:hidden">Tu cuenta está suspendida</span>
                                        <span class="hidden md:inline">
                                            ⚠️ Tu cuenta ha sido suspendida. No puedes crear experiencias ni hacer reservas.
                                            @if(auth()->user()->suspension_reason)
                                                Razón: {{ Str::limit(auth()->user()->suspension_reason, 50) }}
                                            @endif
                                        </span>
                                    </p>
                                </div>
                                <div class="mt-2 flex-shrink-0 w-full sm:mt-0 sm:w-auto">
                                    <a href="{{ route('account.suspended') }}"
                                       class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-red-600 bg-white hover:bg-red-50">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

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

        <script>
            // Datos de Departamentos y Municipios de Colombia
            const colombiaData = [
                { "id": 0, "departamento": "Amazonas", "ciudades": ["Leticia", "Puerto Nariño"] },
                { "id": 1, "departamento": "Antioquia", "ciudades": ["Medellín", "Bello", "Itagüí", "Envigado", "Apartadó", "Rionegro", "Turbo", "Caucasia"] },
                { "id": 2, "departamento": "Arauca", "ciudades": ["Arauca", "Arauquita", "Saravena", "Tame"] },
                { "id": 3, "departamento": "Atlántico", "ciudades": ["Barranquilla", "Soledad", "Malambo", "Sabanalarga", "Baranoa"] },
                { "id": 4, "departamento": "Bolívar", "ciudades": ["Cartagena", "Magangué", "El Carmen de Bolívar", "Turbaco", "Arjona"] },
                { "id": 5, "departamento": "Boyacá", "ciudades": ["Tunja", "Duitama", "Sogamoso", "Chiquinquirá", "Paipa"] },
                { "id": 6, "departamento": "Caldas", "ciudades": ["Manizales", "La Dorada", "Chinchiná", "Villamaría", "Riosucio"] },
                { "id": 7, "departamento": "Caquetá", "ciudades": ["Florencia", "San Vicente del Caguán"] },
                { "id": 8, "departamento": "Casanare", "ciudades": ["Yopal", "Aguazul", "Villanueva"] },
                { "id": 9, "departamento": "Cauca", "ciudades": ["Popayán", "Santander de Quilichao", "Puerto Tejada"] },
                { "id": 10, "departamento": "Cesar", "ciudades": ["Valledupar", "Aguachica", "Agustín Codazzi"] },
                { "id": 11, "departamento": "Chocó", "ciudades": ["Quibdó", "Istmina"] },
                { "id": 12, "departamento": "Córdoba", "ciudades": ["Montería", "Cereté", "Sahagún", "Lorica", "Montelíbano", "Planeta Rica", "Ciénaga de Oro", "Tierralta"] },
                { "id": 13, "departamento": "Cundinamarca", "ciudades": ["Bogotá", "Soacha", "Fusagasugá", "Facatativá", "Zipaquirá", "Chía", "Girardot", "Mosquera"] },
                { "id": 14, "departamento": "Guainía", "ciudades": ["Inírida"] },
                { "id": 15, "departamento": "Guaviare", "ciudades": ["San José del Guaviare"] },
                { "id": 16, "departamento": "Huila", "ciudades": ["Neiva", "Pitalito", "Garzón", "La Plata"] },
                { "id": 17, "departamento": "La Guajira", "ciudades": ["Riohacha", "Maicao", "Uribia", "Manaure"] },
                { "id": 18, "departamento": "Magdalena", "ciudades": ["Santa Marta", "Ciénaga", "Zona Bananera", "Plato"] },
                { "id": 19, "departamento": "Meta", "ciudades": ["Villavicencio", "Acacías", "Granada"] },
                { "id": 20, "departamento": "Nariño", "ciudades": ["Pasto", "Tumaco", "Ipiales"] },
                { "id": 21, "departamento": "Norte de Santander", "ciudades": ["Cúcuta", "Ocaña", "Villa del Rosario", "Los Patios"] },
                { "id": 22, "departamento": "Putumayo", "ciudades": ["Mocoa", "Puerto Asís"] },
                { "id": 23, "departamento": "Quindío", "ciudades": ["Armenia", "Calarcá", "La Tebaida", "Montenegro"] },
                { "id": 24, "departamento": "Risaralda", "ciudades": ["Pereira", "Dosquebradas", "Santa Rosa de Cabal"] },
                { "id": 25, "departamento": "San Andrés y Providencia", "ciudades": ["San Andrés", "Providencia"] },
                { "id": 26, "departamento": "Santander", "ciudades": ["Bucaramanga", "Floridablanca", "Barrancabermeja", "Girón", "Piedecuesta", "San Gil"] },
                { "id": 27, "departamento": "Sucre", "ciudades": ["Sincelejo", "Corozal", "San Marcos"] },
                { "id": 28, "departamento": "Tolima", "ciudades": ["Ibagué", "Espinal", "Melgar", "Chaparral"] },
                { "id": 29, "departamento": "Valle del Cauca", "ciudades": ["Cali", "Buenaventura", "Palmira", "Tuluá", "Yumbo", "Cartago", "Jamundí", "Buga"] },
                { "id": 30, "departamento": "Vaupés", "ciudades": ["Mitú"] },
                { "id": 31, "departamento": "Vichada", "ciudades": ["Puerto Carreño"] }
            ];

            function locationHandler(initialValue = '') {
                let initialDept = '';
                let initialCity = '';

                // Si hay un valor inicial (ej: "Cereté, Córdoba"), intentamos separarlo
                if (initialValue && initialValue.includes(',')) {
                    const parts = initialValue.split(',').map(s => s.trim());
                    if (parts.length >= 2) {
                        initialCity = parts[0]; // Cereté
                        initialDept = parts[1]; // Córdoba
                    }
                }

                return {
                    departments: colombiaData,
                    municipalities: [],
                    selectedDept: initialDept,
                    selectedCity: initialCity,
                    fullLocation: initialValue,

                    init() {
                        // Si ya había un departamento seleccionado al cargar, llenar los municipios
                        if (this.selectedDept) {
                            this.updateMunicipalities(false); // false para no borrar la ciudad seleccionada
                        }
                    },

                    updateMunicipalities(resetCity = true) {
                        const deptData = this.departments.find(d => d.departamento === this.selectedDept);
                        this.municipalities = deptData ? deptData.ciudades : [];

                        if (resetCity) {
                            this.selectedCity = '';
                            this.updateFullLocation();
                        }
                    },

                    updateFullLocation() {
                        if (this.selectedDept && this.selectedCity) {
                            this.fullLocation = `${this.selectedCity}, ${this.selectedDept}`;
                        } else {
                            this.fullLocation = '';
                        }
                    }
                };
            }
        </script>
    </body>
    </body>
</html>

