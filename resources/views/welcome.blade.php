<x-app-layout>
    <div class="bg-background dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen flex flex-col justify-center items-center">
        <!-- Hero Section -->
        <section class="relative flex flex-col justify-center items-center w-full pt-8 pb-12 sm:pt-12 sm:pb-16 text-center overflow-hidden">
            {{-- Fondo y patrones eliminados --}}

            <!-- Video background -->
            <video id="heroVideo" autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out opacity-100">
                <source src="{{ asset('videos/Video 4.mp4') }}" type="video/mp4">
                <!-- Puedes añadir una versión .webm para mejor compatibilidad -->
                {{-- <source src="{{ asset('videos/hero.webm') }}" type="video/webm"> --}}
                Tu navegador no soporta video HTML5.
            </video>
            <!-- Imagen estática que se muestra cuando el video está pausado (inicia oculta con opacity-0) -->
            <img id="heroPoster" src="{{ asset('images/Imagen 1.jpeg') }}" alt="Hero poster" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out opacity-0 pointer-events-none" />

            <!-- Botón play/pause (esquina superior derecha) -->
            <button id="videoToggleBtn" type="button" aria-pressed="false" aria-label="Pausar video"
                    class="absolute top-4 right-4 z-30 flex items-center justify-center h-10 w-10 rounded-full bg-white/70 dark:bg-gray-800/70 border border-white/20 shadow-sm backdrop-blur-sm transition transform hover:scale-105">
                <!-- Icono pausa por defecto (se mostrará mientras el video esté reproduciéndose) -->
                <svg id="iconPause" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-800 dark:text-gray-100">
                    <rect x="6" y="4" width="4" height="16" rx="1"></rect>
                    <rect x="14" y="4" width="4" height="16" rx="1"></rect>
                </svg>
                <!-- Icono play oculto inicialmente -->
                <svg id="iconPlay" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden text-gray-800 dark:text-gray-100">
                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                </svg>
            </button>

             <!-- Overlay para mejorar legibilidad -->
             <div class="absolute inset-0 bg-black/25 dark:bg-black/40"></div>

             <div class="relative z-10 mx-auto max-w-4xl px-4">
                <!-- Buscador Funcional (RF-011) -->
                <div class="mb-6 mt-0 mx-auto max-w-xl"> {{-- Barra de búsqueda más arriba --}}
                    <div class="rounded-custom border border-primary/20 bg-white/80 dark:bg-gray-800/80 p-4 shadow-primary-lg backdrop-blur-sm">
                        {{-- Formulario apunta a la ruta 'home' (esta misma página) con método GET --}}
                        <form action="{{ route('home') }}" method="GET" class="flex gap-4">
                            <div class="relative flex-grow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                                {{-- Input con nombre 'search', muestra el valor actual de búsqueda --}}
                                <input type="text" name="search" placeholder="Buscar por título o ubicación..."
                                       value="{{ $searchTerm ?? '' }}"
                                       class="w-full rounded-custom border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 pl-10 h-12 focus:border-primary focus:ring-primary">
                            </div>
                            <button type="submit" class="h-12 w-12 flex-shrink-0 rounded-custom bg-primary text-white shadow-primary transition-all hover:bg-primary/90 hover:shadow-primary-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Títulos y párrafo (sin cambios) --}}
                <div class="inline-flex animate-pulse items-center gap-2 rounded-full border border-primary/30 bg-secondary/20 px-3 py-1 text-sm font-medium text-primary dark:text-accent mt-2">
                    ✨ Descubre la magia de Córdoba
                </div>
                <h1 class="mt-4 text-3xl font-medium tracking-tight sm:text-5xl bg-gradient-to-r from-primary via-accent to-primary bg-clip-text text-transparent">
                    Vive Experiencias Únicas en el Corazón del Sinú
                </h1>
                <p class="mt-4 mx-auto max-w-2xl text-base text-white dark:text-white">
                    Conecta con guías locales, explora la cultura, la gastronomía y los paisajes que hacen de nuestra región un lugar inolvidable.
                </p>

                <!-- Tags (sin cambios) -->
                <div class="mt-8 flex flex-wrap justify-center gap-2 sm:gap-3">
                    <button class="rounded-full border border-primary/20 bg-white/80 px-4 py-1.5 text-sm text-primary transition-colors hover:bg-secondary dark:bg-gray-800/80 dark:hover:bg-primary/20">Río Sinú</button>
                    <button class="rounded-full border border-primary/20 bg-white/80 px-4 py-1.5 text-sm text-primary transition-colors hover:bg-secondary dark:bg-gray-800/80 dark:hover:bg-primary/20">Malecón</button>
                    <button class="rounded-full border border-primary/20 bg-white/80 px-4 py-1.5 text-sm text-primary transition-colors hover:bg-secondary dark:bg-gray-800/80 dark:hover:bg-primary/20">Plaza Cultural</button>
                    <button class="rounded-full border border-primary/20 bg-white/80 px-4 py-1.5 text-sm text-primary transition-colors hover:bg-secondary dark:bg-gray-800/80 dark:hover:bg-primary/20">Gastronomía</button>
                    <button class="rounded-full border border-primary/20 bg-white/80 px-4 py-1.5 text-sm text-primary transition-colors hover:bg-secondary dark:bg-gray-800/80 dark:hover:bg-primary/20">Naturaleza</button>
                </div>
            </div>
            {{-- Eliminado el gradiente inferior --}}
        </section>

        {{-- Sección de Experiencias (Adaptada para mostrar resultados de búsqueda) --}}
        <section class="py-16 sm:py-24 bg-secondary/20 dark:bg-primary/10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {{-- Título dinámico si hay búsqueda --}}
                @if ($searchTerm)
                    <h2 class="text-3xl font-medium tracking-tight text-center sm:text-4xl">
                        Resultados para: "<span class="text-primary dark:text-secondary">{{ $searchTerm }}</span>"
                    </h2>
                @else
                    <h2 class="text-3xl font-medium tracking-tight text-center sm:text-4xl bg-gradient-to-r from-primary via-accent to-primary bg-clip-text text-transparent">Aventuras Inolvidables te Esperan</h2>
                    <p class="mt-4 mx-auto max-w-2xl text-center text-lg text-gray-600 dark:text-gray-300">Desde paseos por el río hasta clases de porro, vive la cultura sinuana de la mano de expertos.</p>
                @endif

                <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- Itera sobre las experiencias (ahora filtradas si hay búsqueda) --}}
                    @forelse ($experiences as $experience)
                        <x-experience-card :experience="$experience" />
                    @empty
                        <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-12 text-gray-500 dark:text-gray-400">
                            {{-- Mensaje diferente si no hay resultados de búsqueda --}}
                            @if ($searchTerm)
                                <p>No se encontraron experiencias que coincidan con tu búsqueda.</p>
                                <a href="{{ route('home') }}" class="mt-4 inline-block text-primary dark:text-secondary hover:underline">Mostrar todas las experiencias</a>
                            @else
                                <p>Pronto habrá nuevas experiencias disponibles. ¡Vuelve a visitarnos!</p>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
        <!-- Delicias que Cuentan Historias debajo de Aventuras Inolvidables -->
        <section class="py-16 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-medium tracking-tight text-center sm:text-4xl bg-gradient-to-r from-primary via-accent to-primary bg-clip-text text-transparent">Delicias que Cuentan Historias</h2>
                <p class="mt-4 mx-auto max-w-2xl text-center text-lg text-gray-600 dark:text-gray-300">Descubre los sabores auténticos de Montería y Cereté en los lugares preferidos por los locales.</p>
                <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($restaurants as $restaurant)
                        <x-restaurant-card :restaurant="$restaurant"/>
                    @endforeach
                    {{-- Restaurantes adicionales de ejemplo --}}
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

        {{-- CTA y Footer (sin cambios) --}}
        <section class="py-20 px-4">
            <div class="mx-auto max-w-2xl text-center">
                <div class="group relative rounded-custom bg-gradient-to-br from-primary to-accent p-8 shadow-primary-lg transition-transform hover:scale-105">
                    <h3 class="text-2xl font-medium text-white">¿Eres un guía local?</h3>
                    <p class="mt-2 text-white/80">Comparte tu pasión por nuestra tierra y genera ingresos convirtiéndote en anfitrión en NexLocal.</p>
                    <a href="#" class="mt-6 inline-block rounded-full bg-white px-6 py-2 font-medium text-primary transition-transform group-hover:scale-110">
                        Conviértete en anfitrión
                    </a>
                </div>
            </div>
        </section>
        <footer class="bg-gradient-to-t from-secondary/30 to-background dark:from-primary/20 dark:to-gray-900 border-t border-primary/10 dark:border-white/10">
            <div class="mx-auto max-w-7xl px-8 py-16">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="col-span-2 md:col-span-1">
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">NexLocal</h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Conectando viajeros con la cultura auténtica de Córdoba, Colombia.</p>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 dark:text-white">Explorar</h3>
                        <ul class="mt-4 space-y-2 text-sm">
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Experiencias</a></li>
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Gastronomía</a></li>
                             <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Montería</a></li>
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Cereté</a></li>
                        </ul>
                    </div>
                     <div>
                        <h3 class="font-medium text-gray-900 dark:text-white">Compañía</h3>
                        <ul class="mt-4 space-y-2 text-sm">
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Sobre Nosotros</a></li>
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Conviértete en Guía</a></li>
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Blog</a></li>
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Prensa</a></li>
                        </ul>
                    </div>
                     <div>
                        <h3 class="font-medium text-gray-900 dark:text-white">Soporte</h3>
                        <ul class="mt-4 space-y-2 text-sm">
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Centro de Ayuda</a></li>
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Contáctanos</a></li>
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Términos y Condiciones</a></li>
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Política de Privacidad</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-16 border-t border-primary/10 pt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    &copy; {{ date('Y') }} NexLocal. Todos los derechos reservados. Hecho con ♥ en Córdoba.
                </div>
            </div>
        </footer>
    </div>

    {{-- Script local para controlar reproducción del hero (play/pause e intercambio con poster con transición) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const video = document.getElementById('heroVideo');
            const poster = document.getElementById('heroPoster');
            const btn = document.getElementById('videoToggleBtn');
            const iconPlay = document.getElementById('iconPlay');
            const iconPause = document.getElementById('iconPause');

            if (!video || !poster || !btn) return;

            // Actualiza la UI usando clases de opacidad para una transición suave
            function updateUIPlaying(isPlaying) {
                if (isPlaying) {
                    // Mostrar video (fade in) y ocultar poster (fade out)
                    video.classList.remove('opacity-0', 'pointer-events-none');
                    video.classList.add('opacity-100');
                    poster.classList.remove('opacity-100');
                    poster.classList.add('opacity-0', 'pointer-events-none');

                    iconPlay.classList.add('hidden');
                    iconPause.classList.remove('hidden');
                    btn.setAttribute('aria-pressed', 'false');
                    btn.setAttribute('aria-label', 'Pausar video');
                } else {
                    // Pausar: fade out video y fade in poster
                    video.classList.remove('opacity-100');
                    video.classList.add('opacity-0', 'pointer-events-none');
                    poster.classList.remove('opacity-0');
                    poster.classList.add('opacity-100');

                    iconPlay.classList.remove('hidden');
                    iconPause.classList.add('hidden');
                    btn.setAttribute('aria-pressed', 'true');
                    btn.setAttribute('aria-label', 'Reproducir video');
                }
            }

            // Intento inicial de reproducir (autoplay puede bloqueado en algunos navegadores)
            video.play().then(() => {
                updateUIPlaying(true);
            }).catch(() => {
                // fallback: mostrar poster
                updateUIPlaying(false);
            });

            btn.addEventListener('click', function () {
                // Si el video está visible (no opacity-0), lo pausamos y mostramos poster
                if (!video.classList.contains('opacity-0')) {
                    video.pause();
                    // Reiniciar al inicio al pausar para asegurar que el próximo play empiece desde 0
                    try { video.currentTime = 0; } catch(e) {}
                    updateUIPlaying(false);
                } else {
                    // Reproducir desde el principio
                    try { video.currentTime = 0; } catch(e) {}
                    // Intentar reproducir
                    video.play().then(() => {
                        updateUIPlaying(true);
                    }).catch(() => {
                        // Si falla, mostrar poster
                        updateUIPlaying(false);
                    });
                }
            });

            // Mantener UI sincronizada si el video se pausa/lee por otros medios
            video.addEventListener('pause', () => {
                // Al pausarlo desde controles externos, mostrar poster y reiniciar
                try { video.currentTime = 0; } catch(e) {}
                updateUIPlaying(false);
            });
            video.addEventListener('play', () => updateUIPlaying(true));
        });
    </script>
</x-app-layout>
