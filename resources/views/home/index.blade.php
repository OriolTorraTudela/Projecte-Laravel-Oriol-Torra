@extends('layouts.app')

@section('content')
{{-- ─── CAPÇALERA DE LA PÀGINA PRINCIPAL ──────────────────────────── --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Totes les imatges</h1>
    <p class="text-sm text-gray-500 mt-1">
        {{ $images->total() }} imatges · pàgina {{ $images->currentPage() }} de {{ $images->lastPage() }}
    </p>
</div>

@if($images->isEmpty())
    {{-- Estat buit: no hi ha imatges --}}
    <div class="text-center py-20 text-gray-400">
        <div class="text-6xl mb-4">📷</div>
        <p class="text-lg font-medium">Encara no hi ha imatges</p>
        @auth
            <a href="{{ route('images.create') }}"
               class="mt-4 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                Puja la primera imatge
            </a>
        @endauth
    </div>
@else
    {{-- ─── GRAELLA D'IMATGES (3 columnes) ────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($images as $image)
            <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">

                {{-- Imatge (clicable per anar al detall) --}}
                <a href="{{ route('images.show', $image) }}" class="block aspect-square overflow-hidden">
                    <img src="{{ $image->imageUrl() }}"
                         alt="{{ $image->description ?? 'Imatge de ' . $image->user->displayName() }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </a>

                {{-- Informació de la targeta --}}
                <div class="p-4">
                    {{-- Autor de la imatge amb avatar --}}
                    <div class="flex items-center space-x-2 mb-3">
                        <img src="{{ $image->user->avatarUrl() }}"
                             alt="Avatar de {{ $image->user->name }}"
                             class="w-7 h-7 rounded-full object-cover border border-gray-200">
                        <span class="text-sm font-medium text-gray-700">
                            {{ $image->user->displayName() }}
                        </span>
                        {{-- Temps relatiu des de la publicació --}}
                        <span class="text-xs text-gray-400 ml-auto">
                            {{ $image->created_at->diffForHumans() }}
                        </span>
                    </div>

                    {{-- Descripció (truncada a 2 línies) --}}
                    @if($image->description)
                        <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                            {{ $image->description }}
                        </p>
                    @endif

                    {{-- Estadístiques: likes i comentaris --}}
                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                        <span class="flex items-center space-x-1">
                            <span>❤️</span>
                            <span>{{ $image->likes->count() }}</span>
                        </span>
                        <span class="flex items-center space-x-1">
                            <span>💬</span>
                            <span>{{ $image->comments->count() }}</span>
                        </span>

                        {{-- Botó per veure el detall --}}
                        <a href="{{ route('images.show', $image) }}"
                           class="ml-auto text-indigo-600 font-medium hover:underline">
                            Veure →
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    {{-- ─── PAGINACIÓ ───────────────────────────────────────────────── --}}
    {{-- Laravel genera automàticament els botons de paginació amb Tailwind --}}
    <div class="mt-10 flex justify-center">
        {{ $images->links() }}
    </div>
@endif
@endsection
