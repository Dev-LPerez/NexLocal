@props(['experience'])

{{-- Enlace a la página de detalle de la experiencia --}}
<a href="{{ route('experiences.show', $experience) }}" class="group flex flex-col h-full overflow-hidden rounded-custom bg-white dark:bg-gray-800 shadow-md transition-all duration-300 hover:shadow-primary-lg hover:-translate-y-1">
    <div class="relative">
        {{-- Imagen --}}
        <img class="h-56 w-full object-cover transition-transform duration-300 group-hover:scale-105"
             src="{{ $experience->image_path ? Storage::url($experience->image_path) : 'https://placehold.co/600x400/e9d5ff/8b5cf6?text=NexLocal' }}"
             alt="{{ $experience->title }}"
             onerror="this.onerror=null; this.src='https://placehold.co/600x400/e9d5ff/8b5cf6?text=Imagen+No+Disponible';">

        {{-- Badge de Categoría --}}
        @if($experience->category)
        <div class="absolute top-3 left-3">
            <span class="rounded-full bg-secondary/80 dark:bg-primary/50 px-2.5 py-1 text-xs font-medium text-primary-dark dark:text-secondary backdrop-blur-sm shadow">
                {{ $experience->category }}
            </span>
        </div>
        @endif

        {{-- Precio en la esquina superior derecha --}}
        <div class="absolute top-3 right-3">
            <span class="rounded-full bg-black/50 px-2.5 py-1 text-sm font-semibold text-white backdrop-blur-sm shadow">
                ${{ number_format($experience->price, 0, ',', '.') }} COP
            </span>
        </div>
    </div>

    {{-- Contenido de texto --}}
    <div class="p-4 flex flex-col flex-grow">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white truncate group-hover:text-primary dark:group-hover:text-secondary">
            {{ $experience->title }}
        </h3>

        {{-- Ubicación --}}
        <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5 shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <span>{{ $experience->location }}</span>
        </div>

        {{-- Duración --}}
        <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <span>{{ $experience->duration }}</span>
        </div>

        {{-- Sección "Incluye" (solo si existe y es array) --}}
        @if(!empty($experience->includes) && is_array($experience->includes))
            <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-3">
                <h4 class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-2">Incluye</h4>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(array_slice($experience->includes, 0, 3) as $item)
                        <span class="rounded-full bg-secondary/50 dark:bg-primary/30 px-2 py-0.5 text-xs font-medium text-primary dark:text-secondary">
                            {{ \Illuminate\Support\Str::limit($item, 20) }}
                        </span>
                    @endforeach
                    @if(count($experience->includes) > 3)
                        <span class="rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-xs font-medium text-gray-500 dark:text-gray-400">
                            +{{ count($experience->includes) - 3 }} más
                        </span>
                    @endif
                </div>
            </div>
        @endif

        {{-- Nombre del Guía (empujado al final con mt-auto) --}}
        <div class="mt-auto pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center gap-2 mt-4">
            {{-- <img src="{{ $experience->user->profile_photo_url ?? 'https://placehold.co/40x40' }}" alt="{{ $experience->user->name }}" class="h-6 w-6 rounded-full object-cover"> --}}
            <span class="text-xs text-gray-500 dark:text-gray-400">Ofrecido por: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $experience->user->name ?? 'Guía NexLocal' }}</span></span>
        </div>
    </div>
</a>
