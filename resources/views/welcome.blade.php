<x-app-layout>
    <div class="bg-background dark:bg-gray-900 text-gray-800 dark:text-gray-200">
        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-24 pb-12 sm:pt-32 sm:pb-16 text-center">
            <div class="absolute inset-0 bg-gradient-to-b from-secondary/30 via-background to-background dark:from-primary/30 dark:via-gray-900 dark:to-gray-900"></div>
            <div class="absolute inset-0 opacity-40" style="background-image: url('data:image/svg+xml,<svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><g fill="%23c084fc" fill-opacity="0.1" fill-rule="evenodd"><circle cx="5" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="25" cy="5" r="1"/><circle cx="35" cy="5" r="1"/><circle cx="5" cy="15" r="1"/><circle cx="15" cy="15" r="1"/><circle cx="25" cy="15" r="1"/><circle cx="35" cy="15" r="1"/><circle cx="5" cy="25" r="1"/><circle cx="15" cy="25" r="1"/><circle cx="25" cy="25" r="1"/><circle cx="35" cy="25" r="1"/><circle cx="5" cy="35" r="1"/><circle cx="15" cy="35" r="1"/><circle cx="25" cy="35" r="1"/><circle cx="35" cy="35" r="1"/></g></svg>');"></div>
            
            <div class="relative z-10 mx-auto max-w-4xl px-4">
                <div class="inline-flex animate-pulse items-center gap-2 rounded-full border border-primary/30 bg-secondary/20 px-3 py-1 text-sm font-medium text-primary dark:text-accent">
                    ✨ Descubre la magia de Córdoba
                </div>
                <h1 class="mt-6 text-4xl font-medium tracking-tight sm:text-6xl bg-gradient-to-r from-primary via-accent to-primary bg-clip-text text-transparent">
                    Vive Experiencias Únicas en el Corazón del Sinú
                </h1>
                <p class="mt-6 mx-auto max-w-2xl text-lg text-gray-600 dark:text-gray-300">
                    Conecta con guías locales, explora la cultura, la gastronomía y los paisajes que hacen de nuestra región un lugar inolvidable.
                </p>

                <!-- Buscador -->
                <div class="mt-10 mx-auto max-w-3xl">
                    <div class="rounded-custom border border-primary/20 bg-white/80 dark:bg-gray-800/80 p-4 shadow-primary-lg backdrop-blur-sm">
                        <form class="grid sm:grid-cols-2 gap-4">
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                <input type="text" placeholder="¿A dónde quieres ir?" class="w-full rounded-custom border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 pl-10 h-12 focus:border-primary focus:ring-primary">
                            </div>
                            <div class="relative flex gap-4">
                                <div class="relative w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                                    <input type="text" placeholder="¿Cuándo?" class="w-full rounded-custom border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 pl-10 h-12 focus:border-primary focus:ring-primary">
                                </div>
                                <button type="submit" class="h-12 w-12 flex-shrink-0 rounded-custom bg-primary text-white shadow-primary transition-all hover:bg-primary/90 hover:shadow-primary-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tags -->
                <div class="mt-8 flex flex-wrap justify-center gap-2 sm:gap-3">
                    <button class="rounded-full border border-primary/20 bg-white/80 px-4 py-1.5 text-sm text-primary transition-colors hover:bg-secondary dark:bg-gray-800/80 dark:hover:bg-primary/20">Río Sinú</button>
                    <button class="rounded-full border border-primary/20 bg-white/80 px-4 py-1.5 text-sm text-primary transition-colors hover:bg-secondary dark:bg-gray-800/80 dark:hover:bg-primary/20">Malecón</button>
                    <button class="rounded-full border border-primary/20 bg-white/80 px-4 py-1.5 text-sm text-primary transition-colors hover:bg-secondary dark:bg-gray-800/80 dark:hover:bg-primary/20">Plaza Cultural</button>
                    <button class="rounded-full border border-primary/20 bg-white/80 px-4 py-1.5 text-sm text-primary transition-colors hover:bg-secondary dark:bg-gray-800/80 dark:hover:bg-primary/20">Gastronomía</button>
                    <button class="rounded-full border border-primary/20 bg-white/80 px-4 py-1.5 text-sm text-primary transition-colors hover:bg-secondary dark:bg-gray-800/80 dark:hover:bg-primary/20">Naturaleza</button>
                </div>
            </div>
            <div class="absolute bottom-0 h-20 w-full bg-gradient-to-t from-background dark:from-gray-900 to-transparent"></div>
        </section>

        <!-- Sección de Gastronomía -->
        <section class="py-16 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-medium tracking-tight text-center sm:text-4xl bg-gradient-to-r from-primary via-accent to-primary bg-clip-text text-transparent">Delicias que Cuentan Historias</h2>
                <p class="mt-4 mx-auto max-w-2xl text-center text-lg text-gray-600 dark:text-gray-300">Descubre los sabores auténticos de Montería y Cereté en los lugares preferidos por los locales.</p>
                <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($restaurants as $restaurant)
                        <x-restaurant-card :restaurant="$restaurant"/>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Sección de Experiencias -->
        <section class="py-16 sm:py-24 bg-secondary/20 dark:bg-primary/10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-medium tracking-tight text-center sm:text-4xl bg-gradient-to-r from-primary via-accent to-primary bg-clip-text text-transparent">Aventuras Inolvidables te Esperan</h2>
                 <p class="mt-4 mx-auto max-w-2xl text-center text-lg text-gray-600 dark:text-gray-300">Desde paseos por el río hasta clases de porro, vive la cultura sinuana de la mano de expertos.</p>
                <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                     @forelse ($experiences as $experience)
                        <x-experience-card :experience="$experience" />
                    @empty
                        <div class="col-span-3 text-center py-12 text-gray-500">
                            <p>Pronto habrá nuevas experiencias disponibles. ¡Vuelve a visitarnos!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- CTA Section -->
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
        
        <!-- Footer -->
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
</x-app-layout>