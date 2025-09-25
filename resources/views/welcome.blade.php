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

        {{-- Experience Card 1 --}}
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <img src="https://via.placeholder.com/400x300.png/E7DBEF/49225B?text=Café" alt="Experiencia de café" class="w-full h-48 object-cover">
            <div class="p-6">
                <h4 class="font-bold text-xl mb-2 text-primary">Tour de Café en el Eje Cafetero</h4>
                <p class="text-gray-700 text-base">
                    Aprende sobre el proceso del café, desde la semilla hasta la taza, con un agricultor local.
                </p>
                <div class="mt-4">
                    <span class="inline-block bg-light rounded-full px-3 py-1 text-sm font-semibold text-primary-dark mr-2 mb-2">#Café</span>
                    <span class="inline-block bg-light rounded-full px-3 py-1 text-sm font-semibold text-primary-dark mr-2 mb-2">#Cultura</span>
                </div>
            </div>
        </div>

        {{-- Experience Card 2 --}}
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <img src="https://via.placeholder.com/400x300.png/E7DBEF/49225B?text=Graffiti" alt="Tour de Graffiti" class="w-full h-48 object-cover">
            <div class="p-6">
                <h4 class="font-bold text-xl mb-2 text-primary">Graffiti Tour en la Comuna 13</h4>
                <p class="text-gray-700 text-base">
                    Descubre la historia de resiliencia y arte urbano en uno de los barrios más vibrantes de Medellín.
                </p>
                <div class="mt-4">
                    <span class="inline-block bg-light rounded-full px-3 py-1 text-sm font-semibold text-primary-dark mr-2 mb-2">#Arte</span>
                    <span class="inline-block bg-light rounded-full px-3 py-1 text-sm font-semibold text-primary-dark mr-2 mb-2">#Medellín</span>
                </div>
            </div>
        </div>

        {{-- Experience Card 3 --}}
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <img src="https://via.placeholder.com/400x300.png/E7DBEF/49225B?text=Playa" alt="Playa del Tayrona" class="w-full h-48 object-cover">
            <div class="p-6">
                <h4 class="font-bold text-xl mb-2 text-primary">Senderismo en el Parque Tayrona</h4>
                <p class="text-gray-700 text-base">
                    Explora playas vírgenes y selva exuberante con un guía indígena de la Sierra Nevada.
                </p>
                <div class="mt-4">
                    <span class="inline-block bg-light rounded-full px-3 py-1 text-sm font-semibold text-primary-dark mr-2 mb-2">#Naturaleza</span>
                    <span class="inline-block bg-light rounded-full px-3 py-1 text-sm font-semibold text-primary-dark mr-2 mb-2">#Aventura</span>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection