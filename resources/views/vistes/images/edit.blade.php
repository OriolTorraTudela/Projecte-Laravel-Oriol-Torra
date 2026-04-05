@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar imatge</h1>
        <p class="text-sm text-gray-500 mt-1">Modifica la descripció o reemplaça la imatge.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        {{-- Formulari d'edició — usa PUT via method spoofing --}}
        <form action="{{ route('images.update', $image) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Imatge actual --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Imatge actual</label>
                <img src="{{ $image->imageUrl() }}"
                     alt="Imatge actual"
                     id="image-preview"
                     class="w-full max-h-48 object-contain rounded-lg border border-gray-200 bg-gray-50 mb-3">

                {{-- Camp per pujar nova imatge (opcional en edició) --}}
                <label for="image" class="block text-sm text-gray-600 mb-1">
                    Nova imatge <span class="text-gray-400">(opcional — si no en tries cap, es manté l'actual)</span>
                </label>
                <input id="image" name="image" type="file"
                       accept="image/*"
                       class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm file:font-medium hover:file:bg-indigo-100"
                       onchange="previewImage(this)">

                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descripció --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripció</label>
                <textarea id="description" name="description"
                          rows="3"
                          maxlength="1000"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 resize-none">{{ old('description', $image->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botons d'acció --}}
            <div class="flex items-center space-x-3">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2.5 rounded-lg transition-colors">
                    💾 Desar canvis
                </button>
                <a href="{{ route('images.show', $image) }}"
                   class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    Cancel·lar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    /**
     * Actualitza la previsualització de la imatge quan l'usuari
     * selecciona una nova imatge per reemplaçar l'actual.
     */
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
