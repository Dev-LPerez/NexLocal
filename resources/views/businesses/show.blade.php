<x-app-layout>
    @php
        $primaryColor = $business->theme_colors['primary'] ?? '#7c3aed'; // Default to purple-600
        $banner = $business->banner_image_path ? Storage::url($business->banner_image_path) : 'https://placehold.co/1200x400/e2e8f0/64748b?text=' . urlencode($business->name);
        $avatar = $business->cover_image_path ? Storage::url($business->cover_image_path) : 'https://placehold.co/150x150/ffffff/7c3aed?text=' . substr(urlencode($business->name), 0, 1);
    @endphp

    <style>
        :root {
            --store-primary: {{ $primaryColor }};
        }
        .store-bg-primary { background-color: var(--store-primary); }
        .store-text-primary { color: var(--store-primary); }
        .store-border-primary { border-color: var(--store-primary); }
        .store-hover-bg-primary:hover { background-color: var(--store-primary); filter: brightness(0.9); }
    </style>

    <!-- Alpine.js Store para el Carrito -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('cart', {
                items: [],
                init() {
                    // Cargar carrito de localStorage
                    const saved = localStorage.getItem('cart_{{ $business->id }}');
                    if (saved) {
                        this.items = JSON.parse(saved);
                    }
                },
                add(product) {
                    const existing = this.items.find(i => i.id === product.id);
                    if (existing) {
                        existing.quantity++;
                    } else {
                        this.items.push({ ...product, quantity: 1 });
                    }
                    this.save();
                },
                remove(productId) {
                    this.items = this.items.filter(i => i.id !== productId);
                    this.save();
                },
                updateQuantity(productId, quantity) {
                    const item = this.items.find(i => i.id === productId);
                    if (item) {
                        item.quantity = quantity;
                        if (item.quantity <= 0) {
                            this.remove(productId);
                        }
                    }
                    this.save();
                },
                get total() {
                    return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },
                get count() {
                    return this.items.reduce((sum, item) => sum + item.quantity, 0);
                },
                save() {
                    localStorage.setItem('cart_{{ $business->id }}', JSON.stringify(this.items));
                },
                clear() {
                    this.items = [];
                    this.save();
                }
            });
        });
    </script>

    <div x-data="{ isCartOpen: false }">
        <!-- Banner Section -->
        <div class="relative w-full h-64 md:h-80 lg:h-96 bg-gray-200 overflow-hidden">
            <img src="{{ $banner }}" alt="Banner {{ $business->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-10">
            <!-- Info Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 md:p-8 flex flex-col md:flex-row items-center md:items-start gap-6 border border-gray-100 dark:border-gray-700">
                <div class="w-32 h-32 md:w-40 md:h-40 flex-shrink-0 rounded-2xl overflow-hidden shadow-lg border-4 border-white dark:border-gray-800 bg-white">
                    <img src="{{ $avatar }}" alt="{{ $business->name }}" class="w-full h-full object-cover">
                </div>

                <div class="flex-1 text-center md:text-left mt-2 md:mt-0">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $business->name }}</h1>
                            <p class="text-lg text-gray-500 dark:text-gray-400 mt-1 font-medium">{{ ucfirst($business->business_type) }} • {{ $business->category }}</p>
                        </div>

                        <div class="flex gap-3 justify-center md:justify-end">
                            @if(!empty($business->social_links['instagram']))
                                <a href="{{ $business->social_links['instagram'] }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:store-bg-primary hover:text-white transition-colors shadow-sm">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                </a>
                            @endif
                            @if(!empty($business->social_links['facebook']))
                                <a href="{{ $business->social_links['facebook'] }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:store-bg-primary hover:text-white transition-colors shadow-sm">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    @if($business->welcome_message)
                        <div class="mt-4 py-3 px-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl inline-block">
                            <p class="text-gray-800 dark:text-gray-200 font-medium italic">"{{ $business->welcome_message }}"</p>
                        </div>
                    @endif

                    <p class="text-gray-600 dark:text-gray-400 mt-4 max-w-3xl leading-relaxed">{{ $business->description }}</p>

                    <div class="flex flex-wrap items-center gap-4 mt-6">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 font-medium">
                            <svg class="w-5 h-5 mr-1.5 store-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $business->address }}
                        </div>
                        @if($business->phone)
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 font-medium">
                                <svg class="w-5 h-5 mr-1.5 store-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                {{ $business->phone }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="mt-8 flex flex-col lg:flex-row gap-8">

                <!-- Columna Izquierda (Filtros e Info) -->
                <div class="w-full lg:w-1/4 space-y-6">
                    @if(!empty($business->services))
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4">Servicios</h3>
                            <ul class="space-y-3">
                                @foreach($business->services as $service)
                                    <li class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <svg class="w-5 h-5 mr-2 store-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ ucfirst($service) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(!empty($business->payment_methods))
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4">Medios de Pago</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($business->payment_methods as $method)
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-full border border-gray-200 dark:border-gray-600">
                                        {{ ucfirst($method) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($business->operating_hours) && count($business->operating_hours) > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4">Horario de Atención</h3>
                            <div class="space-y-3">
                                @foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $day)
                                    @if(isset($business->operating_hours[$day]))
                                        <div class="flex justify-between items-center text-sm border-b border-gray-100 dark:border-gray-700 pb-2 last:border-0 last:pb-0">
                                            <span class="text-gray-600 dark:text-gray-400 capitalize">{{ $day }}</span>
                                            @if(isset($business->operating_hours[$day]['is_open']) && $business->operating_hours[$day]['is_open'])
                                                <span class="text-gray-900 dark:text-gray-200 font-medium">
                                                    {{ $business->operating_hours[$day]['open'] ?? '08:00' }} - {{ $business->operating_hours[$day]['close'] ?? '18:00' }}
                                                </span>
                                            @else
                                                <span class="text-red-500 dark:text-red-400 font-medium text-xs uppercase px-2 py-1 bg-red-50 dark:bg-red-900/10 rounded-md">Cerrado</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Columna Derecha (Productos) -->
                <div class="w-full lg:w-3/4">
                    <div class="flex justify-between items-end mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Nuestro Catálogo</h2>

                        <!-- Floating Cart Button -->
                        <button @click="isCartOpen = true" class="fixed sm:relative bottom-6 right-6 sm:bottom-auto sm:right-auto z-40 bg-white dark:bg-gray-800 store-text-primary border-2 store-border-primary shadow-xl sm:shadow-sm rounded-full sm:rounded-xl px-4 py-3 flex items-center font-bold hover:bg-gray-50 transition-transform hover:scale-105">
                            <svg class="w-6 h-6 sm:w-5 sm:h-5 mr-0 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="hidden sm:inline">Ver Carrito</span>
                            <span x-show="$store.cart.count > 0" class="absolute -top-2 -right-2 sm:static sm:ml-2 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center" x-text="$store.cart.count"></span>
                        </button>
                    </div>

                    @if($business->products->isEmpty())
                        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <h3 class="mt-4 text-lg font-bold text-gray-900 dark:text-white">Aún no hay productos</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">Este negocio está preparando su catálogo. ¡Vuelve pronto!</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($business->products as $product)
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col hover:shadow-md transition-shadow group relative">
                                    <div class="h-48 relative overflow-hidden bg-gray-100 dark:bg-gray-900">
                                        @if($product->image_path)
                                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 {{ !$product->is_available ? 'grayscale opacity-70' : '' }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 {{ !$product->is_available ? 'grayscale opacity-70' : '' }}">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L28 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                        @if(!$product->is_available)
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-10">
                                                <span class="text-white font-bold px-4 py-2 bg-red-600 rounded-lg transform -rotate-12 shadow-lg border-2 border-white">AGOTADO</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-5 flex-1 flex flex-col">
                                        <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-1 {{ !$product->is_available ? 'text-gray-400 dark:text-gray-500' : '' }}">{{ $product->name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 flex-1">{{ $product->description }}</p>
                                        <div class="flex items-center justify-between mt-auto">
                                            <span class="text-xl font-black text-gray-900 dark:text-white {{ !$product->is_available ? 'text-gray-400 dark:text-gray-600' : '' }}">$ {{ number_format($product->price, 0, ',', '.') }}</span>

                                            @if($product->is_available)
                                                <button @click="$store.cart.add({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $product->image_path ? Storage::url($product->image_path) : '' }}' })"
                                                        class="w-10 h-10 rounded-full store-bg-primary text-white flex items-center justify-center hover:opacity-90 transition shadow-sm" title="Añadir al carrito">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                </button>
                                            @else
                                                <button disabled class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-700 text-gray-500 flex items-center justify-center cursor-not-allowed shadow-sm" title="No disponible">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Seccion de Reseñas -->
                    <div class="mt-12 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Reseñas de Clientes</h2>
                            <div class="flex items-center bg-gray-50 dark:bg-gray-700/50 px-4 py-2 rounded-full">
                                <span class="text-xl font-bold text-gray-900 dark:text-white mr-2">{{ $business->reviews->count() > 0 ? number_format($business->reviews->avg('rating'), 1) : 'Nuevo' }}</span>
                                <div class="flex text-yellow-400">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ $business->reviews->count() }} valoraciones)</span>
                            </div>
                        </div>

                        @if(Auth::check() && Auth::user()->role === 'tourist' && Auth::user()->orders()->where('local_business_id', $business->id)->where('status', 'delivered')->exists())
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6 mb-8 border border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Escribe tu reseña</h3>
                                <form action="{{ route('business-reviews.store', $business->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Calificación</label>
                                        <div class="flex items-center space-x-2 rating-stars" x-data="{ rating: {{ old('rating', 5) }}, hoverRating: 0 }">
                                            <input type="hidden" name="rating" x-model="rating">
                                            <template x-for="i in 5">
                                                <button type="button" @click="rating = i" @mouseenter="hoverRating = i" @mouseleave="hoverRating = 0" class="focus:outline-none">
                                                    <svg class="w-8 h-8 transition-colors duration-150" :class="{'text-yellow-400': (hoverRating ? hoverRating >= i : rating >= i), 'text-gray-300 dark:text-gray-600': !(hoverRating ? hoverRating >= i : rating >= i)}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Comentario</label>
                                        <textarea id="comment" name="comment" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="¿Cómo fue tu experiencia con los productos de {{ $business->name }}?"></textarea>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition-colors">Publicar Reseña</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <div class="space-y-6">
                            @forelse($business->reviews as $review)
                                <div class="border-b border-gray-100 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <img src="{{ $review->user->profile_photo_path ? Storage::url($review->user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($review->user->name) }}" alt="{{ $review->user->name }}" class="w-10 h-10 rounded-full mr-3 border border-gray-200 dark:border-gray-600">
                                            <div>
                                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $review->user->name }}</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endfor
                                        </div>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-gray-600 dark:text-gray-300 ml-13">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-500 dark:text-gray-400">Todavía no hay reseñas para este negocio.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offcanvas Cart Menu -->
        <div x-show="isCartOpen" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div x-show="isCartOpen" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="isCartOpen = false"></div>
                <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                    <div x-show="isCartOpen" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-md">
                        <div class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-xl overflow-y-scroll">
                            <div class="flex-1 py-6 overflow-y-auto px-4 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white" id="slide-over-title">Tu Pedido</h2>
                                    <div class="ml-3 h-7 flex items-center">
                                        <button type="button" class="-m-2 p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" @click="isCartOpen = false">
                                            <span class="sr-only">Cerrar panel</span>
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <div class="flow-root">
                                        <ul role="list" class="-my-6 divide-y divide-gray-200 dark:divide-gray-700">
                                            <template x-for="item in $store.cart.items" :key="item.id">
                                                <li class="py-6 flex">
                                                    <div class="flex-shrink-0 w-20 h-20 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
                                                        <img :src="item.image || 'https://placehold.co/100x100/e2e8f0/64748b?text=Foto'" :alt="item.name" class="w-full h-full object-center object-cover">
                                                    </div>
                                                    <div class="ml-4 flex-1 flex flex-col">
                                                        <div>
                                                            <div class="flex justify-between text-base font-bold text-gray-900 dark:text-white">
                                                                <h3 x-text="item.name"></h3>
                                                                <p class="ml-4" x-text="'$ ' + new Intl.NumberFormat('es-CO').format(item.price * item.quantity)"></p>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 flex items-end justify-between text-sm">
                                                            <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg">
                                                                <button @click="$store.cart.updateQuantity(item.id, item.quantity - 1)" class="px-2 py-1 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-l-lg">-</button>
                                                                <span class="px-4 py-1 text-gray-900 dark:text-white font-medium" x-text="item.quantity"></span>
                                                                <button @click="$store.cart.updateQuantity(item.id, item.quantity + 1)" class="px-2 py-1 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-r-lg">+</button>
                                                            </div>
                                                            <div class="flex">
                                                                <button type="button" @click="$store.cart.remove(item.id)" class="font-medium text-red-600 hover:text-red-500">Eliminar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </template>
                                            <li x-show="$store.cart.items.length === 0" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                                Tu carrito está vacío.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 py-6 px-4 sm:px-6 bg-gray-50 dark:bg-gray-900/50">
                                <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white mb-4">
                                    <p>Total</p>
                                    <p x-text="'$ ' + new Intl.NumberFormat('es-CO').format($store.cart.total)"></p>
                                </div>

                                <!-- Checkout Button -->
                                <template x-if="$store.cart.items.length > 0">
                                    <form action="{{ route('checkout.store', $business->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cart" :value="JSON.stringify($store.cart.items)">
                                        <button type="submit" class="w-full flex items-center justify-center px-6 py-4 border border-transparent rounded-xl shadow-sm text-base font-bold text-white store-bg-primary store-hover-bg-primary transition">
                                            Confirmar Pedido
                                        </button>
                                    </form>
                                </template>
                                <div class="mt-4 flex justify-center text-sm text-center text-gray-500 dark:text-gray-400">
                                    <p>
                                        o <button type="button" class="store-text-primary font-medium hover:underline" @click="isCartOpen = false">seguir comprando<span aria-hidden="true"> &rarr;</span></button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
