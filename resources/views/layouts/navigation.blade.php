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

                @auth
                    <!-- Notificaciones Dropdown -->
                    <div x-data="notificationDropdown()" class="relative">
                        <button @click="toggleDropdown()"
                                class="relative p-2 rounded-full text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition">
                            <!-- Icono de campana -->
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <!-- Badge con contador de notificaciones no leídas -->
                            <span x-show="unreadCount > 0"
                                  x-text="unreadCount > 9 ? '9+' : unreadCount"
                                  class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full min-w-[1.25rem]">
                            </span>
                        </button>

                        <!-- Dropdown de notificaciones -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50"
                             style="display: none;">
                            <div class="py-2">
                                <!-- Header -->
                                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Notificaciones</h3>
                                    <button @click="markAllAsRead()"
                                            x-show="unreadCount > 0"
                                            class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                        Marcar todas como leídas
                                    </button>
                                </div>

                                <!-- Lista de notificaciones -->
                                <div class="max-h-96 overflow-y-auto">
                                    <template x-if="notifications.length === 0">
                                        <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No tienes notificaciones
                                        </div>
                                    </template>

                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div @click="handleNotificationClick(notification)"
                                             :class="{'bg-indigo-50 dark:bg-indigo-900/20': !notification.is_read}"
                                             class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 transition">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900">
                                                        <span class="text-lg" x-text="notification.icon || '🔔'"></span>
                                                    </span>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="notification.title"></p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="notification.message"></p>
                                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="formatDate(notification.created_at)"></p>
                                                </div>
                                                <div class="ml-2">
                                                    <span x-show="!notification.is_read" class="inline-block w-2 h-2 bg-indigo-600 rounded-full"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Footer -->
                                <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('notifications.index') }}"
                                       class="block text-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                        Ver todas las notificaciones
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth

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
                    <x-responsive-nav-link :href="route('notifications.index')">
                        Notificaciones
                        @if(Auth::user()->unreadNotificationsCount() > 0)
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                {{ Auth::user()->unreadNotificationsCount() }}
                            </span>
                        @endif
                    </x-responsive-nav-link>

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

<script>
    function notificationDropdown() {
        return {
            open: false,
            notifications: [],
            unreadCount: 0,

            init() {
                this.loadNotifications();
                // Actualizar notificaciones cada 30 segundos
                setInterval(() => {
                    this.loadNotifications();
                }, 30000);
            },

            toggleDropdown() {
                this.open = !this.open;
                if (this.open) {
                    this.loadNotifications();
                }
            },

            async loadNotifications() {
                try {
                    const response = await fetch('{{ route("notifications.unread") }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });
                    const data = await response.json();
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                } catch (error) {
                    console.error('Error al cargar notificaciones:', error);
                }
            },

            async handleNotificationClick(notification) {
                if (!notification.is_read) {
                    await this.markAsRead(notification.id);
                }
                if (notification.link) {
                    window.location.href = notification.link;
                } else {
                    this.open = false;
                }
            },

            async markAsRead(id) {
                try {
                    await fetch(`/notifications/${id}/read`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    });
                    await this.loadNotifications();
                } catch (error) {
                    console.error('Error al marcar como leída:', error);
                }
            },

            async markAllAsRead() {
                try {
                    await fetch('{{ route("notifications.markAllAsRead") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    });
                    await this.loadNotifications();
                } catch (error) {
                    console.error('Error al marcar todas como leídas:', error);
                }
            },

            formatDate(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffMs = now - date;
                const diffMins = Math.floor(diffMs / 60000);
                const diffHours = Math.floor(diffMs / 3600000);
                const diffDays = Math.floor(diffMs / 86400000);

                if (diffMins < 1) return 'Ahora';
                if (diffMins < 60) return `Hace ${diffMins} min`;
                if (diffHours < 24) return `Hace ${diffHours} h`;
                if (diffDays < 7) return `Hace ${diffDays} d`;

                return date.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
            }
        }
    }
</script>
