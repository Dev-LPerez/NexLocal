<nav x-data="{ open: false, scrolled: false }"
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{
        'bg-white/80 dark:bg-gray-900/80 shadow-lg backdrop-blur-xl border-gray-200/50 dark:border-gray-700/50': scrolled,
        'bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800': !scrolled
     }"
     class="sticky top-0 z-50 w-full transition-all duration-300 border-b">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> {{-- Aumenté altura de h-16 a h-20 para más elegancia --}}
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="group flex items-center gap-2 transition-transform duration-300 hover:scale-105">
                        <x-application-logo class="block h-10 w-auto fill-current text-indigo-600 dark:text-indigo-400" />
                        <span class="text-xl font-bold text-gray-900 dark:text-white tracking-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                            NexLocal
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- Estilizamos los links para que se vean más limpios --}}
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-base font-medium">
                        Inicio
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-base font-medium text-red-600 dark:text-red-400">
                                Panel Admin
                            </x-nav-link>

                        @elseif(Auth::user()->role === 'guide')
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-base font-medium">
                                Panel de Guía
                            </x-nav-link>
                            <x-nav-link :href="route('experiences.create')" :active="request()->routeIs('experiences.create')" class="text-base font-medium">
                                + Crear
                            </x-nav-link>

                        @elseif(Auth::user()->role === 'tourist')
                            <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.index')" class="text-base font-medium">
                                Mis Reservas
                            </x-nav-link>
                            
                        @elseif(Auth::user()->role === 'owner')
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-base font-medium text-purple-600 dark:text-purple-400">
                                Gestión de Emprendimiento
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-3">

                <button @click="darkMode = !darkMode; toggleDarkMode();"
                        class="p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all duration-200"
                        title="Cambiar tema">
                    <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <svg x-show="darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                </button>

                @auth
                    <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 mx-2"></div>

                    <div x-data="chatDropdown()" class="relative">
                        <button @click="toggleDropdown()" class="relative p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all duration-200">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span x-show="unreadCount > 0" x-text="unreadCount > 9 ? '9+' : unreadCount" class="absolute top-1.5 right-1.5 inline-flex items-center justify-center h-4 w-4 text-[10px] font-bold text-white bg-green-500 rounded-full ring-2 ring-white dark:ring-gray-800"></span>
                        </button>
                        {{-- Dropdown Chat Contenido (Mismo código interno) --}}
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute right-0 mt-4 w-80 rounded-2xl shadow-xl bg-white dark:bg-gray-800 ring-1 ring-black/5 dark:ring-white/10 z-50 overflow-hidden" style="display: none;">
                            {{-- ... (Misma lógica de lista de chat) ... --}}
                            <div class="py-2">
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Mensajes</h3>
                                </div>
                                <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                    <template x-if="conversations.length === 0">
                                        <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No hay mensajes recientes</div>
                                    </template>
                                    <template x-for="conversation in conversations" :key="conversation.booking_id">
                                        <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 transition relative group cursor-pointer" @click="openChatWindow(conversation)">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 relative">
                                                    <template x-if="conversation.other_user.profile_photo_path">
                                                        <img :src="'{{ rtrim(Storage::url(''), '/') }}/' + conversation.other_user.profile_photo_path" class="w-10 h-10 rounded-full object-cover">
                                                    </template>
                                                    <template x-if="!conversation.other_user.profile_photo_path">
                                                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold" x-text="conversation.other_user.name.charAt(0)"></div>
                                                    </template>
                                                </div>
                                                <div class="ml-3 flex-1 min-w-0">
                                                    <div class="flex justify-between items-baseline">
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="conversation.other_user.name"></p>
                                                        <span x-show="conversation.unread_count > 0" class="ml-2 w-2 h-2 bg-green-500 rounded-full"></span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="conversation.experience_title"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-data="notificationDropdown()" class="relative">
                        <button @click="toggleDropdown()" class="relative p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all duration-200">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="unreadCount > 0" x-text="unreadCount > 9 ? '9+' : unreadCount" class="absolute top-1.5 right-1.5 inline-flex items-center justify-center h-4 w-4 text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-white dark:ring-gray-800"></span>
                        </button>
                        {{-- Dropdown Notifications Content --}}
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute right-0 mt-4 w-80 rounded-2xl shadow-xl bg-white dark:bg-gray-800 ring-1 ring-black/5 dark:ring-white/10 z-50 overflow-hidden" style="display: none;">
                            <div class="py-2">
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Notificaciones</h3>
                                    <button @click="markAllAsRead()" x-show="unreadCount > 0" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800">Marcar leídas</button>
                                </div>
                                <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                    <template x-if="notifications.length === 0">
                                        <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Sin novedades</div>
                                    </template>
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div @click="handleNotificationClick(notification)" :class="{'bg-indigo-50/50 dark:bg-indigo-900/10': !notification.is_read}" class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 transition">
                                            <div class="flex gap-3">
                                                <span class="mt-1 flex-shrink-0 text-lg" x-text="notification.icon || '🔔'"></span>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 leading-snug" x-text="notification.title"></p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2" x-text="notification.message"></p>
                                                    <p class="text-[10px] text-gray-400 mt-1" x-text="formatDate(notification.created_at)"></p>
                                                </div>
                                                <span x-show="!notification.is_read" class="mt-2 w-2 h-2 bg-indigo-500 rounded-full flex-shrink-0"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <a href="{{ route('notifications.index') }}" class="block py-2 text-center text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-gray-50 dark:bg-gray-700/30 hover:bg-gray-100 dark:hover:bg-gray-700 transition">Ver historial completo</a>
                            </div>
                        </div>
                    </div>
                @endauth

                @guest
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            Ingresar
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-md shadow-indigo-200 dark:shadow-none transition-all hover:-translate-y-0.5">
                            Registrarse
                        </a>
                    </div>
                @else
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 pl-3 pr-2 py-1.5 border border-gray-200 dark:border-gray-700 rounded-full text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="Foto" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 flex items-center justify-center font-bold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="hidden md:block text-left mr-1">
                                    <p class="text-xs font-bold leading-none">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-gray-500 leading-none mt-0.5 uppercase">{{ Auth::user()->role === 'admin' ? 'Admin' : (Auth::user()->role === 'guide' ? 'Guía' : (Auth::user()->role === 'owner' ? 'Dueño' : 'Turista')) }}</p>
                                </div>
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
                                Gestionar cuenta
                            </div>
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Perfil') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden gap-2">
                <button @click="darkMode = !darkMode; toggleDarkMode();" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <svg x-show="!darkMode" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <svg x-show="darkMode" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                </button>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 shadow-lg">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">Inicio</x-responsive-nav-link>
            @auth
                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-red-600 dark:text-red-400">Panel Admin</x-responsive-nav-link>
                @elseif(Auth::user()->role === 'guide')
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Panel de Guía</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('experiences.create')" :active="request()->routeIs('experiences.create')">Crear Experiencia</x-responsive-nav-link>
                @elseif(Auth::user()->role === 'tourist')
                    <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.index')">Mis Reservas</x-responsive-nav-link>
                @elseif(Auth::user()->role === 'owner')
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-purple-600 dark:text-purple-400">Gestión de Emprendimiento</x-responsive-nav-link>
                @endif
            @endauth
            @guest
                <div class="pt-4 pb-4 border-t border-gray-100 dark:border-gray-800 flex flex-col gap-2 px-4">
                    <a href="{{ route('login') }}" class="w-full text-center py-2 bg-gray-100 dark:bg-gray-800 rounded-lg text-gray-700 dark:text-gray-300 font-medium">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2 bg-indigo-600 rounded-lg text-white font-bold">Registrarse</a>
                </div>
            @endguest
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="px-4 flex items-center">
                    <div class="flex-shrink-0">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" class="h-10 w-10 rounded-full object-cover">
                        @else
                            <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('notifications.index')">
                        Notificaciones
                        @if(Auth::user()->unreadNotificationsCount() > 0)
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ Auth::user()->unreadNotificationsCount() }}</span>
                        @endif
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.edit')">Perfil</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 dark:text-red-400">Cerrar Sesión</x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>

{{-- Scripts JS para lógica de Chat y Notificaciones (Se mantienen igual, solo se aseguran estilos) --}}
<script>
    // ... (Tus scripts de chatDropdown y notificationDropdown existentes) ...
    // Asegúrate de copiar los scripts de abajo del archivo anterior si no están en app.js
    function chatDropdown() {
        return {
            open: false,
            conversations: [],
            unreadCount: 0,
            init() { this.loadConversations(); setInterval(() => { this.loadConversations(); }, 15000); },
            toggleDropdown() { this.open = !this.open; if (this.open) { this.loadConversations(); } },
            async loadConversations() {
                try {
                    const response = await fetch('{{ route("chat.conversations") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
                    const data = await response.json();
                    this.conversations = data.conversations;
                    this.unreadCount = this.conversations.reduce((total, conv) => total + conv.unread_count, 0);
                } catch (error) { console.error(error); }
            },
            openChatWindow(conversation) {
                this.open = false;
                window.dispatchEvent(new CustomEvent('open-chat-window', { detail: conversation }));
            }
        }
    }

    function notificationDropdown() {
        return {
            open: false,
            notifications: [],
            unreadCount: 0,
            init() { this.loadNotifications(); setInterval(() => { this.loadNotifications(); }, 30000); },
            toggleDropdown() { this.open = !this.open; if (this.open) { this.loadNotifications(); } },
            async loadNotifications() {
                try {
                    const response = await fetch('{{ route("notifications.unread") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
                    const data = await response.json();
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                } catch (error) { console.error(error); }
            },
            async handleNotificationClick(notification) {
                if (!notification.is_read) { await this.markAsRead(notification.id); }
                if (notification.link) { window.location.href = notification.link; } else { this.open = false; }
            },
            async markAsRead(id) {
                try { await fetch(`/notifications/${id}/read`, { method: 'PATCH', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } }); await this.loadNotifications(); } catch (error) {}
            },
            async markAllAsRead() {
                try { await fetch('{{ route("notifications.markAllAsRead") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } }); await this.loadNotifications(); } catch (error) {}
            },
            formatDate(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffMins = Math.floor((now - date) / 60000);
                if (diffMins < 60) return `Hace ${diffMins} min`;
                if (diffMins < 1440) return `Hace ${Math.floor(diffMins/60)} h`;
                return date.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
            }
        }
    }
</script>
