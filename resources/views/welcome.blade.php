@extends('layouts.app')

@section('content')

{{-- Hero Section --}}
<section class="bg-light">
    <div class="container mx-auto px-6 py-20 text-center">
        <h2 class="text-4xl font-bold mb-2 text-primary-dark">
            Descubre la Colombia Auténtica
        </h2>
        <h3 class="text-2xl mb-8 text-secondary">
            Conecta con guías locales y vive experiencias inolvidables.
        </h3>
        <button class="bg-primary text-white font-bold py-2 px-4 rounded-full hover:bg-primary-dark transition duration-300">
            Explorar Experiencias
        </button>
    </div>
</section>

{{-- Experiencias Grid --}}
<section class="container mx-auto px-6 py-12">
    <h3 class="text-3xl font-bold text-center text-primary-dark mb-8">Experiencias Populares</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse ($experiences as $experience)
            {{-- Tarjeta de Experiencia Dinámica --}}
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x300.png/E7DBEF/49225B?text={{ urlencode($experience->location) }}" alt="Imagen de {{ $experience->title }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h4 class="font-bold text-xl mb-2 text-primary">{{ $experience->title }}</h4>
                    <p class="text-gray-700 text-base">
                        {{ Str::limit($experience->description, 100) }} {{-- Limita la descripción a 100 caracteres --}}
                    </p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="font-bold text-lg text-primary-dark">${{ number_format($experience->price, 0, ',', '.') }} COP</span>
                            <a href="#" class="bg-secondary text-white font-bold text-sm py-2 px-3 rounded-full hover:bg-primary-dark transition duration-300">
                            Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        @empty
            {{-- Mensaje si no hay experiencias --}}
            <div class="col-span-3 text-center py-12">
                <p class="text-xl text-gray-500">Aún no hay experiencias disponibles. ¡Sé el primero en crear una!</p>
            </div>
        @endforelse

    </div>
</section>

@endsection