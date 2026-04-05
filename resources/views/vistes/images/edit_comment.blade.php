@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar comentari</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        {{-- Referència visual a la imatge comentada --}}
        <div class="flex items-center space-x-3 mb-5 pb-5 border-b border-gray-100">
            <img src="{{ $comment->image->imageUrl() }}"
                 alt="Imatge comentada"
                 class="w-16 h-16 object-cover rounded-lg border border-gray-200">
            <div>
                <p class="text-xs text-gray-400">Comentari a la imatge de</p>
                <p class="text-sm font-medium text-gray-700">{{ $comment->image->user->displayName() }}</p>
            </div>
        </div>

        {{-- Formulari d'edició del comentari --}}
        <form action="{{ route('comments.update', $comment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Comentari
                </label>
                <textarea id="content" name="content"
                          rows="4"
                          maxlength="500"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 resize-none">{{ old('content', $comment->content) }}</textarea>

                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botons d'acció --}}
            <div class="flex items-center space-x-3">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2.5 rounded-lg transition-colors">
                    💾 Desar canvis
                </button>
                <a href="{{ route('images.show', $comment->image_id) }}"
                   class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    Cancel·lar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
