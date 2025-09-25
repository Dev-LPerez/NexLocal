@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-12">
    <h2 class="text-3xl font-bold text-center text-primary-dark mb-8">Crea una Nueva Experiencia</h2>

    {{-- Aquí irían los mensajes de error de validación --}}

    <form action="{{ route('experiences.store') }}" method="POST" class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        @csrf
        <div class="mb-4">
            <label for="title" class="block text-primary-dark font-bold mb-2">Título de la Experiencia</label>
            <input type="text" name="title" id="title" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-primary-dark font-bold mb-2">Descripción Detallada</label>
            <textarea name="description" id="description" rows="5" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="location" class="block text-primary-dark font-bold mb-2">Ubicación (Ciudad, Región)</label>
                <input type="text" name="location" id="location" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
            </div>
            <div>
                <label for="price" class="block text-primary-dark font-bold mb-2">Precio (COP)</label>
                <input type="number" name="price" id="price" step="0.01" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
            </div>
            <div>
                <label for="duration" class="block text-primary-dark font-bold mb-2">Duración (ej. 3 horas)</label>
                <input type="text" name="duration" id="duration" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-primary text-white font-bold py-2 px-6 rounded-full hover:bg-primary-dark transition duration-300">
                Guardar Experiencia
            </button>
        </div>
    </form>
</div>
@endsection