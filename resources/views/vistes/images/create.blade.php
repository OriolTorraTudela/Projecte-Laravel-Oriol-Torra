@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">

    {{-- Capçalera --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pujar nova imatge</h1>
        <p class="text-sm text-gray-500 mt-1">Comparteix una nova fotografia amb la comunitat.</p>
    </div>

    {{-- Formulari de creació d'imatge --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Camp: Imatge --}}
            <div class="mb-5">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                    Imatge <span class="text-red-500">*</span>
                </label>

                {{-- Previsualització de la imatge seleccionada --}}
                <div id="preview-container" class="hidden mb-3">
                    <img id="image-preview" src="#" alt="Previsualització"
                         class="max-h-64 w-full object-contain rounded-lg border border-gray-200 bg-gray-50">
                </div>

                {{-- Zona de drag & drop per seleccionar la imatge --}}
                <label for="image"
                       class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-indigo-400 hover:bg-indigo-50 transition-colors">
                    <div class="flex flex-col items-center justify-center text-gray-400">
                        <span class="text-3xl mb-2">📁</span>
                        <p class="text-sm"><span class="text-indigo-600 font-medium">Tria un fitxer</span> o arrossega aquí</p>
                        <p class="text-xs mt-1">PNG, JPG, GIF, WEBP — màx. 5MB</p>
                    </div>
                    <input id="image" name="image" type="file"
                           accept="image/*" class="hidden"
                           onchange="previewImage(this)">
                </label>

                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Camp: Descripció --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripció <span class="text-gray-400">(opcional)</span>
                </label>
                <textarea id="description" name="description"
                          rows="3"
                          placeholder="Descriu la teva imatge..."
                          maxlength="1000"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 resize-none">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botons d'acció --}}
            <div class="flex items-center space-x-3">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2.5 rounded-lg transition-colors">
                    📤 Pujar imatge
                </button>
                <a href="{{ route('home') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    Cancel·lar
                </a>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript per a la previsualització de la imatge --}}
<script>
    /**
     * Mostra una previsualització de la imatge seleccionada
     * abans que l'usuari enviï el formulari.
     *
     * @param {HTMLInputElement} input - L'input de tipus file
     */
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview   = document.getElementById('image-preview');
                const container = document.getElementById('preview-container');
                preview.src     = e.target.result;
                container.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
