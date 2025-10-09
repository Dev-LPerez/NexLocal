@props(['restaurant'])

<div class="group relative overflow-hidden rounded-custom bg-white dark:bg-gray-800 shadow-lg transition-all duration-300 hover:shadow-primary-lg hover:-translate-y-1">
    <img class="h-56 w-full object-cover transition-transform duration-300 group-hover:scale-105" src="{{ $restaurant['image'] }}" alt="{{ $restaurant['name'] }}">
    
    <!-- Badges Superiores -->
    <div class="absolute top-3 left-3">
        <span class="rounded-full bg-secondary/80 px-2 py-1 text-xs font-medium text-primary-dark backdrop-blur-sm">{{ $restaurant['category'] }}</span>
    </div>
    <div class="absolute top-3 right-3 flex items-center gap-1 rounded-full bg-black/40 px-2 py-1 text-xs font-semibold text-white backdrop-blur-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-400"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.77 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
        <span>{{ $restaurant['rating'] }}</span>
    </div>

    <div class="p-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $restaurant['name'] }}</h3>
        
        <!-- Iconos de Información -->
        <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5 shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <span>{{ $restaurant['location'] }}</span>
        </div>
        <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5 shrink-0"><path d="M12 2v20"></path><path d="M5 12h14"></path></svg>
            <span>{{ $restaurant['price_range'] }}</span>
        </div>
        <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <span>{{ $restaurant['hours'] }}</span>
        </div>

        <!-- Especialidades -->
        <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-3">
             <h4 class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-2">Especialidades</h4>
             <div class="flex flex-wrap gap-2">
                @foreach($restaurant['specialties'] as $specialty)
                    <span class="rounded-full bg-secondary/50 px-2 py-1 text-xs font-medium text-primary">{{ $specialty }}</span>
                @endforeach
             </div>
        </div>
        
    </div>
</div>