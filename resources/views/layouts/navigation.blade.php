<nav x-data="{ open: false, darkMode: localStorage.getItem('darkMode') === 'true' }"
     class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}"> {{-- Logo siempre lleva a Home --}}
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- Enlace principal (Home o Dashboard según autenticación) --}}
                    @guest
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            Inicio
                        </x-nav-link>
                    @endguest
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{-- El texto cambia según el rol --}}
                            @if(Auth::user()->role === 'guide')
                                Panel de Guía
                            @elseif(Auth::user()->role === 'tourist')
                                Dashboard {{-- El controlador redirigirá a "Mis Reservas" --}}
                            @else
                                Dashboard
                            @endif
                        </x-nav-link>
                    @endauth

                    {{-- Enlace para "Descubrir Experiencias" (siempre visible, enlace a Home) --}}
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home') && Auth::check()">
                        Experiencias
                    </x-nav-link>

                    {{-- --- Enlaces Condicionales por Rol --- --}}
                    @auth
                        @if(Auth::user()->role === 'guide')
                            <x-nav-link :href="route('experiences.create')" :active="request()->routeIs('experiences.create')">
                                Crear Experiencia
                            </x-nav-link>
                            {{-- El enlace a "Panel de Guía" ya está cubierto por 'dashboard' --}}
                        @endif

                        @if(Auth::user()->role === 'tourist')
                            <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.index')">
                                Mis Reservas
                            </x-nav-link>
                        @endif
                    @endauth

                </div>
            </div>

            <!-- Settings Dropdown y Botones de Login/Register -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">

                <!-- Botón Dark Mode - SIMPLIFICADO -->
                <button @click="darkMode = !darkMode"
                        class="p-2 rounded-full text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition">
                    {{-- SVG Icons --}}
                    <svg x-show="!darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                @guest
                    <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        Iniciar Sesión
                    </x-nav-link>
                    <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        Registrarse
                    </x-nav-link>
                @else
                    <!-- Settings Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto de perfil" class="w-8 h-8 rounded-full object-cover mr-2 border-2 border-indigo-500">
                                @endif
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Perfil
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    Cerrar Sesión
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden space-x-2">

                <!-- Botón Dark Mode (Móvil) - SIMPLIFICADO -->
                <button @click="darkMode = !darkMode"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    {{-- SVG Icons --}}
                    <svg x-show="!darkMode" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="darkMode" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <!-- Hamburger Button -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @guest
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    Inicio
                </x-responsive-nav-link>
            @endguest
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    @if(Auth::user()->role === 'guide')
                        Panel de Guía
                    @else
                        Dashboard
                    @endif
                </x-responsive-nav-link>
            @endauth

            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home') && Auth::check()">
                Experiencias
            </x-responsive-nav-link>

            @auth
                @if(Auth::user()->role === 'guide')
                    <x-responsive-nav-link :href="route('experiences.create')" :active="request()->routeIs('experiences.create')">
                        Crear Experiencia
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->role === 'tourist')
                    <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.index')">
                        Mis Reservas
                    </x-responsive-nav-link>
                @endif
            @endauth

            @guest
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    Iniciar Sesión
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                    Registrarse
                </x-responsive-nav-link>
            @endguest
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Perfil
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Cerrar Sesión
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>

