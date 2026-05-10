<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen flex flex-col">

        <section class="relative h-screen min-h-[600px] flex flex-col justify-center items-center w-full overflow-hidden">

            <div class="absolute inset-0 w-full h-full">
                <video id="heroVideo" autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
                    <source src="{{ asset('videos/Video 4.mp4') }}" type="video/mp4">
                    {{-- <source src="{{ asset('videos/hero.webm') }}" type="video/webm"> --}}
                </video>
                {{-- Imagen estática de respaldo si el video falla o carga lento --}}
                <img id="heroPoster" src="{{ asset('images/Imagen 1.jpeg') }}" class="absolute inset-0 w-full h-full object-cover opacity-0 pointer-events-none transition-opacity duration-700" alt="Córdoba Turística" />

                <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/20 to-gray-900/90"></div>
            </div>

            <button id="videoToggleBtn" class="absolute top-24 right-6 z-20 p-2 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 transition text-white" aria-label="Pausar video">
                <svg id="iconPause" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>
                <svg id="iconPlay" class="hidden" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </button>

            <div class="relative z-10 w-full max-w-5xl px-4 flex flex-col items-center text-center mt-16 sm:mt-0">

                {{-- Badge Animado --}}
                <div class="inline-flex animate-bounce items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-1.5 text-sm font-medium text-white backdrop-blur-md mb-6 shadow-lg">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    Turismo auténtico en Córdoba
                </div>

                {{-- Título Principal --}}
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold tracking-tight text-white mb-6 drop-shadow-lg leading-tight">
                    Descubre lo que <br class="hidden md:block" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-cyan-300">nadie te ha contado</span>
                </h1>

                {{-- Subtítulo --}}
                <p class="mt-2 max-w-2xl text-lg md:text-xl text-gray-100 mb-10 font-light drop-shadow-md">
                    Conecta con anfitriones locales y vive experiencias que no salen en las guías turísticas tradicionales.
                </p>

                <div class="w-full max-w-2xl transform transition-all hover:scale-[1.01]">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-emerald-600 to-blue-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>

                        <div class="relative rounded-2xl bg-white/95 dark:bg-gray-800/90 p-2 shadow-2xl ring-1 ring-gray-900/5 flex flex-col sm:flex-row gap-2 backdrop-blur-sm">
                            <form action="{{ route('home') }}" method="GET" class="flex-1 flex gap-2 w-full">
                                <div class="relative flex-grow flex items-center">
                                    <svg class="absolute left-4 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <input type="text" name="search" placeholder="¿Qué quieres vivir hoy? (Ej: Gastronomía, Río Sinú...)"
                                           value="{{ $searchTerm ?? '' }}"
                                           class="w-full border-0 bg-transparent py-3 sm:py-4 pl-12 pr-4 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:ring-0 text-base sm:text-lg">
                                </div>
                                <button type="submit" class="w-full sm:w-auto rounded-xl bg-indigo-600 px-8 py-3 text-base font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all">
                                    Buscar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Accesos Rápidos (Tendencias) --}}
                <div class="mt-8 sm:mt-10 flex flex-wrap justify-center gap-3 text-sm text-white/90">
                    <span class="uppercase tracking-wider text-xs font-bold text-white/70 mr-2 pt-1">Tendencia:</span>
                    <a href="{{ route('home', ['search' => 'Río Sinú']) }}" class="hover:text-white hover:underline decoration-emerald-400 underline-offset-4 transition shadow-black drop-shadow-md">🌊 Río Sinú</a>
                    <a href="{{ route('home', ['search' => 'Gastronomía']) }}" class="hover:text-white hover:underline decoration-emerald-400 underline-offset-4 transition shadow-black drop-shadow-md">🥘 Gastronomía</a>
                    <a href="{{ route('home', ['search' => 'Naturaleza']) }}" class="hover:text-white hover:underline decoration-emerald-400 underline-offset-4 transition shadow-black drop-shadow-md">🍃 Naturaleza</a>
                </div>
            </div>
        </section>

        {{-- ===== SECCIÓN UNIFICADA: SWITCH + CATEGORÍAS + CONTENIDO ===== --}}
        <div x-data="{ activeTab: 'experiencias', catFilter: 'todos', expCatFilter: 'todos' }">

            {{-- SWITCH PILL (encima de todo) --}}
            <div class="py-8 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="flex justify-center px-4 w-full">
                    <div class="flex flex-col sm:flex-row sm:inline-flex items-stretch sm:items-center w-full max-w-sm sm:max-w-none sm:w-auto p-1.5 bg-gray-100 dark:bg-gray-800 rounded-2xl shadow-inner border border-gray-200 dark:border-gray-700 gap-1.5 sm:gap-0">

                        {{-- Tab: Experiencias --}}
                        <button @click="activeTab = 'experiencias'; catFilter = 'todos'"
                                id="tab-experiencias"
                                :class="activeTab === 'experiencias'
                                    ? 'bg-gradient-to-r from-indigo-500 to-violet-600 text-white shadow-md'
                                    : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700'"
                                class="w-full sm:w-56 flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-300 cursor-pointer select-none focus:outline-none">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0L9 7" />
                            </svg>
                            <span>Experiencias</span>
                        </button>

                        {{-- Tab: Descubre tu ciudad --}}
                        <button @click="activeTab = 'ciudad'; catFilter = 'todos'"
                                id="tab-ciudad"
                                :class="activeTab === 'ciudad'
                                    ? 'bg-orange-500 text-white shadow-md'
                                    : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700'"
                                class="w-full sm:w-56 flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-300 cursor-pointer select-none focus:outline-none">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Descubre tu ciudad</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- CATEGORÍAS DINÁMICAS --}}
            <div class="bg-white dark:bg-gray-900 shadow-sm">
                {{-- Categorías: Experiencias --}}
                <div x-show="activeTab === 'experiencias'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="py-8 border-b border-gray-200 dark:border-gray-800">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <p class="text-center text-xs font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 mb-12">Explora por categorías</p>
                        <div class="flex flex-wrap justify-center gap-8 md:gap-12">
                            <button @click="expCatFilter = 'todos'"
                                    class="group flex flex-col items-center gap-3 cursor-pointer focus:outline-none">
                                <div :class="expCatFilter === 'todos' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 group-hover:bg-indigo-600 group-hover:text-white'"
                                     class="p-4 rounded-2xl transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Todas</span>
                            </button>
                            <button @click="expCatFilter = 'Cultural'"
                                    class="group flex flex-col items-center gap-3 cursor-pointer focus:outline-none">
                                <div :class="expCatFilter === 'Cultural' ? 'bg-orange-600 text-white shadow-lg' : 'bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 group-hover:bg-orange-600 group-hover:text-white'"
                                     class="p-4 rounded-2xl transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Cultura</span>
                            </button>
                            <button @click="expCatFilter = 'Gastron\u00f3mica'"
                                    class="group flex flex-col items-center gap-3 cursor-pointer focus:outline-none">
                                <div :class="expCatFilter === 'Gastron\u00f3mica' ? 'bg-red-600 text-white shadow-lg' : 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 group-hover:bg-red-600 group-hover:text-white'"
                                     class="p-4 rounded-2xl transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">Gastronomía</span>
                            </button>
                            <button @click="expCatFilter = 'Naturaleza'"
                                    class="group flex flex-col items-center gap-3 cursor-pointer focus:outline-none">
                                <div :class="expCatFilter === 'Naturaleza' ? 'bg-green-600 text-white shadow-lg' : 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 group-hover:bg-green-600 group-hover:text-white'"
                                     class="p-4 rounded-2xl transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">Naturaleza</span>
                            </button>
                            <button @click="expCatFilter = 'Aventura'"
                                    class="group flex flex-col items-center gap-3 cursor-pointer focus:outline-none">
                                <div :class="expCatFilter === 'Aventura' ? 'bg-cyan-600 text-white shadow-lg' : 'bg-cyan-50 dark:bg-cyan-900/20 text-cyan-600 dark:text-cyan-400 group-hover:bg-cyan-600 group-hover:text-white'"
                                     class="p-4 rounded-2xl transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Aventura</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Categorías: Ciudad --}}
                <div x-show="activeTab === 'ciudad'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     style="display:none;"
                     class="py-8 border-b border-gray-200 dark:border-gray-800">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <p class="text-center text-xs font-bold uppercase tracking-widest text-orange-500 dark:text-orange-400 mb-12">Filtra por tipo de lugar</p>
                        <div class="flex flex-wrap justify-center gap-8 md:gap-12">
                            <button @click="catFilter = 'todos'"
                                    class="group flex flex-col items-center gap-3 cursor-pointer focus:outline-none">
                                <div :class="catFilter === 'todos' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white'"
                                     class="p-4 rounded-2xl transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Todos</span>
                            </button>
                            <button @click="catFilter = 'Típico'"
                                    class="group flex flex-col items-center gap-3 cursor-pointer focus:outline-none">
                                <div :class="catFilter === 'Típico' ? 'bg-orange-600 text-white shadow-lg' : 'bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 group-hover:bg-orange-600 group-hover:text-white'"
                                     class="p-4 rounded-2xl transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 7.1 10c.1.9-.3 1.9-.9 2.5a5.9 5.9 0 00-1.6 3A8.1 8.1 0 0117.657 18.657z" /><path stroke-linecap="round" stroke-linejoin="round" d="M14.9 14.9a4 4 0 01-5.8 0c-.2-.2-.5-.3-.8-.3.3-.8.2-1.7-.2-2.4a8.1 8.1 0 016.8 2.7z" /></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Típico</span>
                            </button>
                            <button @click="catFilter = 'Pescados'"
                                    class="group flex flex-col items-center gap-3 cursor-pointer focus:outline-none">
                                <div :class="catFilter === 'Pescados' ? 'bg-cyan-600 text-white shadow-lg' : 'bg-cyan-50 dark:bg-cyan-900/20 text-cyan-600 dark:text-cyan-400 group-hover:bg-cyan-600 group-hover:text-white'"
                                     class="p-4 rounded-2xl transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.12 9.07c-3-2-7.2-2-10.33-.28L4 6v12h.02l4.77-2.8c3.12 1.73 7.33 1.73 10.33-.27C21 13.3 22 12 22 12s-1-1.3-2.88-2.93z M14.5 10.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z" /></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Pescados</span>
                            </button>
                            <button @click="catFilter = 'Lácteos'"
                                    class="group flex flex-col items-center gap-3 cursor-pointer focus:outline-none">
                                <div :class="catFilter === 'Lácteos' ? 'bg-amber-600 text-white shadow-lg' : 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 group-hover:bg-amber-600 group-hover:text-white'"
                                     class="p-4 rounded-2xl transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2.5s-6 6.5-6 10.5a6 6 0 0012 0c0-4-6-10.5-6-10.5z" /></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Lácteos</span>
                            </button>
                            <button @click="catFilter = 'Comida de Mar'"
                                    class="group flex flex-col items-center gap-3 cursor-pointer focus:outline-none">
                                <div :class="catFilter === 'Comida de Mar' ? 'bg-red-600 text-white shadow-lg' : 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 group-hover:bg-red-600 group-hover:text-white'"
                                     class="p-4 rounded-2xl transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">Comida de Mar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTENIDO PRINCIPAL --}}
            <div class="py-16 sm:py-24 bg-gray-50 dark:bg-gray-900">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                    {{-- ===== PANEL: EXPERIENCIAS ===== --}}
                    <div x-show="activeTab === 'experiencias'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4">

                        <div class="text-center mb-12">
                            <template x-if="expCatFilter !== 'todos'">
                                <div>
                                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl bg-gradient-to-r from-indigo-600 to-violet-600 dark:from-indigo-400 dark:to-violet-400 bg-clip-text text-transparent" x-text="'Explorando: ' + expCatFilter"></h2>
                                    <button @click="expCatFilter = 'todos'" class="mt-3 text-sm text-gray-500 hover:text-indigo-600 hover:underline cursor-pointer">Ver todas las categorías</button>
                                </div>
                            </template>
                            <template x-if="expCatFilter === 'todos'">
                                <div>
                                    @if ($searchTerm)
                                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                                            Resultados para: "<span class="text-indigo-600 dark:text-indigo-400">{{ $searchTerm }}</span>"
                                        </h2>
                                        <a href="{{ route('home') }}" class="inline-block mt-2 text-sm text-gray-500 hover:text-indigo-600 hover:underline">Limpiar búsqueda</a>
                                    @else
                                        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl bg-gradient-to-r from-indigo-600 to-violet-600 dark:from-indigo-400 dark:to-violet-400 bg-clip-text text-transparent">
                                            Aventuras Inolvidables
                                        </h2>
                                        <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-400">
                                            Encuentra y disfruta de las actividades más emocionantes y únicas.
                                        </p>
                                    @endif
                                </div>
                            </template>
                        </div>

                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                            @forelse ($experiences as $experience)
                                <div x-show="expCatFilter === 'todos' || expCatFilter === '{{ $experience->category }}'"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95">
                                    <x-experience-card :experience="$experience" />
                                </div>
                            @empty
                                <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-16 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No se encontraron resultados</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        @if ($searchTerm) No hay coincidencias para "{{ $searchTerm }}".
                                        @else Pronto habrá nuevas experiencias disponibles.
                                        @endif
                                    </p>
                                    <a href="{{ route('home') }}" class="mt-4 inline-block text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Ver todo</a>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-10">{{ $experiences->links() }}</div>
                    </div>

                    {{-- ===== PANEL: DESCUBRE TU CIUDAD ===== --}}
                    <div x-show="activeTab === 'ciudad'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         style="display: none;">

                        <div class="text-center mb-12">
                            <h2 class="text-3xl font-bold tracking-tight sm:text-4xl bg-gradient-to-r from-orange-500 to-rose-500 dark:from-orange-400 dark:to-rose-400 bg-clip-text text-transparent">
                                Descubre tu ciudad
                            </h2>
                            <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-400">
                                Conoce y apoya los emprendimientos locales, descubre lugares únicos y disfruta de un excelente servicio.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($restaurants as $restaurant)
                                <div x-show="catFilter === 'todos' || catFilter === '{{ $restaurant['category'] }}'"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100">
                                    <x-restaurant-card :restaurant="$restaurant"/>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <section class="py-16 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">¿Por qué viajar con NexLocal?</h2>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Transformamos la manera de conocer Córdoba.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Feature 1 --}}
                    <div class="flex flex-col items-center text-center p-6 rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/50 rounded-full text-indigo-600 dark:text-indigo-400 mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Guías Verificados</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">Todos nuestros anfitriones pasan por un proceso de verificación de identidad para tu seguridad y tranquilidad.</p>
                    </div>

                    {{-- Feature 2 --}}
                    <div class="flex flex-col items-center text-center p-6 rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="p-3 bg-pink-100 dark:bg-pink-900/50 rounded-full text-pink-600 dark:text-pink-400 mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Impacto Local</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">Tu dinero va directamente a la comunidad local, apoyando el emprendimiento y preservando nuestra cultura.</p>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="flex flex-col items-center text-center p-6 rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="p-3 bg-emerald-100 dark:bg-emerald-900/50 rounded-full text-emerald-600 dark:text-emerald-400 mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Transparencia Total</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">Sin costos ocultos ni sorpresas. El precio que ves es el precio final. Reserva fácil, rápido y seguro.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- 6. CALL TO ACTION (REGISTRO) --}}
        <section class="py-20 px-4 bg-white dark:bg-gray-900">
            <div class="mx-auto max-w-4xl text-center">
                <div class="group relative rounded-3xl bg-gradient-to-br from-indigo-600 to-purple-700 p-10 shadow-2xl overflow-hidden">
                    {{-- Decoración de fondo --}}
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>

                    <div class="relative z-10">
                        <h3 class="text-3xl font-bold text-white mb-4">¿Eres un guía local apasionado?</h3>
                        <p class="text-lg text-indigo-100 max-w-2xl mx-auto mb-8">
                            Comparte tu pasión por nuestra tierra y genera ingresos convirtiéndote en anfitrión en NexLocal. Únete a nuestra comunidad hoy mismo.
                        </p>

                        {{-- BOTÓN DE REGISTRO CORRECTO --}}
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-bold text-indigo-700 bg-white rounded-full shadow-lg hover:bg-gray-50 hover:scale-105 transition-all duration-300">
                            Conviértete en anfitrión
                            <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-1">
                        <div class="flex items-center gap-2 mb-4">
                            <x-application-logo class="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                            <span class="text-xl font-bold text-gray-900 dark:text-white">NexLocal</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Conectando viajeros con la cultura auténtica de Córdoba, Colombia. Vive experiencias reales con gente real.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white tracking-wider uppercase mb-4">Explorar</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-base text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">Experiencias</a></li>
                            <li><a href="#" class="text-base text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">Gastronomía</a></li>
                            <li><a href="#" class="text-base text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">Montería</a></li>
                            <li><a href="#" class="text-base text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">Cereté</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white tracking-wider uppercase mb-4">Compañía</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-base text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">Sobre Nosotros</a></li>
                            <li><a href="{{ route('register') }}" class="text-base text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">Sé un Guía</a></li>
                            <li><a href="#" class="text-base text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">Blog</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white tracking-wider uppercase mb-4">Legal</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-base text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">Términos y Condiciones</a></li>
                            <li><a href="#" class="text-base text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">Política de Privacidad</a></li>
                            <li><a href="#" class="text-base text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">Centro de Ayuda</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-12 border-t border-gray-200 dark:border-gray-800 pt-8 text-center">
                    <p class="text-base text-gray-400 dark:text-gray-500">&copy; {{ date('Y') }} NexLocal. Todos los derechos reservados. Hecho con ♥ en Córdoba.</p>
                </div>
            </div>
        </footer>
    </div>

    {{-- Script para control del video hero --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const video = document.getElementById('heroVideo');
            const poster = document.getElementById('heroPoster');
            const btn = document.getElementById('videoToggleBtn');
            const iconPlay = document.getElementById('iconPlay');
            const iconPause = document.getElementById('iconPause');

            if (!video || !poster || !btn) return;

            function updateUIPlaying(isPlaying) {
                if (isPlaying) {
                    video.classList.remove('opacity-0'); // Mostrar video
                    poster.classList.remove('opacity-100'); // Ocultar poster
                    poster.classList.add('opacity-0');

                    iconPlay.classList.add('hidden');
                    iconPause.classList.remove('hidden');
                    btn.setAttribute('aria-label', 'Pausar video');
                } else {
                    video.classList.add('opacity-0'); // Ocultar video
                    poster.classList.remove('opacity-0'); // Mostrar poster
                    poster.classList.add('opacity-100');

                    iconPlay.classList.remove('hidden');
                    iconPause.classList.add('hidden');
                    btn.setAttribute('aria-label', 'Reproducir video');
                }
            }

            // Intentar reproducir al cargar
            video.play().then(() => updateUIPlaying(true)).catch(() => updateUIPlaying(false));

            btn.addEventListener('click', function () {
                if (video.paused) {
                    video.play();
                    updateUIPlaying(true);
                } else {
                    video.pause();
                    updateUIPlaying(false);
                }
            });
        });
    </script>
</x-app-layout>
