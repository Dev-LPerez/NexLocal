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

        <section class="py-10 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm relative z-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="text-center text-xs font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 mb-8">Explora por categorías</p>

                <div class="flex flex-wrap justify-center gap-8 md:gap-12">
                    {{-- Cultura --}}
                    {{-- CAMBIO: Usamos 'category' en lugar de 'search' --}}
                    <a href="{{ route('home', ['category' => 'Cultural']) }}" class="group flex flex-col items-center gap-3 cursor-pointer">
                        {{-- ... el div del icono se mantiene igual ... --}}
                        <div class="p-4 rounded-2xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 group-hover:bg-orange-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Cultura</span>
                    </a>

                    {{-- Gastronomía --}}
                    <a href="{{ route('home', ['category' => 'Gastronómica']) }}" class="group flex flex-col items-center gap-3 cursor-pointer">
                        <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 group-hover:bg-red-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">Gastronomía</span>
                    </a>

                    {{-- Naturaleza --}}
                    <a href="{{ route('home', ['category' => 'Naturaleza']) }}" class="group flex flex-col items-center gap-3 cursor-pointer">
                        <div class="p-4 rounded-2xl bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 group-hover:bg-green-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">Naturaleza</span>
                    </a>

                    {{-- Aventura --}}
                    <a href="{{ route('home', ['category' => 'Aventura']) }}" class="group flex flex-col items-center gap-3 cursor-pointer">
                        <div class="p-4 rounded-2xl bg-cyan-50 dark:bg-cyan-900/20 text-cyan-600 dark:text-cyan-400 group-hover:bg-cyan-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-translate-y-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Aventura</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="py-16 sm:py-24 bg-gray-50 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {{-- Encabezado Dinámico --}}
                <div class="text-center mb-12">
                    @if ($searchTerm)
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                            Resultados para: "<span class="text-indigo-600 dark:text-indigo-400">{{ $searchTerm }}</span>"
                        </h2>
                        <a href="{{ route('home') }}" class="inline-block mt-2 text-sm text-gray-500 hover:text-indigo-600 hover:underline">Limpiar búsqueda</a>

                    @elseif ($category)
                        {{-- Título específico para Categoría --}}
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                            Explorando: <span class="text-indigo-600 dark:text-indigo-400">{{ $category }}</span>
                        </h2>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Descubre las mejores experiencias en esta categoría.</p>
                        <a href="{{ route('home') }}" class="inline-block mt-2 text-sm text-gray-500 hover:text-indigo-600 hover:underline">Ver todas las categorías</a>

                    @else
                        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl bg-gradient-to-r from-indigo-600 to-violet-600 dark:from-indigo-400 dark:to-violet-400 bg-clip-text text-transparent">
                            Aventuras Inolvidables
                        </h2>
                        <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-400">
                            Desde paseos por el río hasta clases de porro, vive la cultura sinuana de la mano de expertos.
                        </p>
                    @endif
                </div>

                {{-- Grid de Experiencias --}}
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($experiences as $experience)
                        <x-experience-card :experience="$experience" />
                    @empty
                        <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-16 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No se encontraron resultados</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                @if ($searchTerm)
                                    No hay coincidencias para "{{ $searchTerm }}".
                                @elseif ($category)
                                    Aún no hay experiencias en la categoría "{{ $category }}".
                                @else
                                    Pronto habrá nuevas experiencias disponibles.
                                @endif
                            </p>
                            <a href="{{ route('home') }}" class="mt-4 inline-block text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Ver todo</a>
                        </div>
                    @endforelse
                </div>

                {{-- Paginación si fuera necesaria --}}
                <div class="mt-10">
                    {{ $experiences->links() }}
                </div>
            </div>
        </section>

        <section class="py-16 sm:py-24 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl bg-gradient-to-r from-orange-500 to-red-600 bg-clip-text text-transparent">
                        Delicias que Cuentan Historias
                    </h2>
                    <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-400">
                        Descubre los sabores auténticos de Montería y Cereté en los lugares preferidos por los locales.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($restaurants as $restaurant)
                        <x-restaurant-card :restaurant="$restaurant"/>
                    @endforeach

                    {{-- Restaurantes Estáticos de Ejemplo (Manteniendo lo que tenías) --}}
                    <x-restaurant-card :restaurant="[
                        'name' => 'La Cazuela Sinuana',
                        'description' => 'Especialidad en cazuelas de mariscos y cocina típica cordobesa.',
                        'image' => 'images/Cazuela Sinuana.png',
                        'location' => 'Montería',
                        'rating' => 4.8,
                        'category' => 'Típico',
                        'price_range' => '$$ - $$$',
                        'hours' => '12:00 - 23:00',
                        'specialties' => ['Cazuela de mariscos', 'Arroz con coco', 'Pescado frito']
                    ]"/>
                    <x-restaurant-card :restaurant="[
                        'name' => 'El Rincón del Queso',
                        'description' => 'Quesos artesanales y platos tradicionales de la región.',
                        'image' => 'images/Rincon del queso.png',
                        'location' => 'Montería',
                        'rating' => 4.7,
                        'category' => 'Lácteos',
                        'price_range' => '$ - $$',
                        'hours' => '08:00 - 20:00',
                        'specialties' => ['Queso costeño', 'Arepas de queso', 'Postres']
                    ]"/>
                    <x-restaurant-card :restaurant="[
                        'name' => 'Sabores del Río',
                        'description' => 'Pescados frescos y ambiente familiar junto al río Sinú.',
                        'image' => 'images/Sabores del rio.png',
                        'location' => 'Montería',
                        'rating' => 4.9,
                        'category' => 'Pescados',
                        'price_range' => '$$ - $$$',
                        'hours' => '11:00 - 22:00',
                        'specialties' => ['Mojarra frita', 'Sancocho de pescado', 'Patacones']
                    ]"/>
                </div>
            </div>
        </section>

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
