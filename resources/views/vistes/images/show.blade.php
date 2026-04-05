@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- ─── CAPÇALERA AMB AUTOR I ACCIONS ─────────────────────────── --}}
    <div class="flex items-center justify-between mb-4">
        {{-- Avatar i nom de l'autor --}}
        <div class="flex items-center space-x-3">
            <img src="{{ $image->user->avatarUrl() }}"
                 alt="Avatar de {{ $image->user->name }}"
                 class="w-10 h-10 rounded-full object-cover border-2 border-indigo-200">
            <div>
                <p class="font-semibold text-gray-800">{{ $image->user->displayName() }}</p>
                <p class="text-xs text-gray-400">{{ $image->created_at->diffForHumans() }}</p>
            </div>
        </div>

        {{-- Botons d'editar/eliminar (only owner) --}}
        @auth
            @if(Auth::id() === $image->user_id)
                <div class="flex items-center space-x-2">
                    <a href="{{ route('images.edit', $image) }}"
                       class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg transition-colors">
                        ✏️ Editar
                    </a>
                    {{-- Formulari d'eliminació amb confirmació --}}
                    <form action="{{ route('images.destroy', $image) }}" method="POST"
                          onsubmit="return confirm('Segur que vols eliminar aquesta imatge? Aquesta acció no es pot desfer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-sm bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg transition-colors">
                            🗑️ Eliminar
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>

    {{-- ─── IMATGE PRINCIPAL ───────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-4">
        <img src="{{ $image->imageUrl() }}"
             alt="{{ $image->description ?? 'Imatge' }}"
             class="w-full object-contain max-h-[600px] bg-gray-900">
    </div>

    {{-- ─── DESCRIPCIÓ I LIKES ──────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-4">

        {{-- Botó de LIKE reactiu (AJAX) --}}
        @auth
        <div class="flex items-center space-x-3 mb-4 pb-4 border-b border-gray-100">
            {{-- Botó de like — actualitzat via JavaScript sense recarregar la pàgina --}}
            <button id="like-btn"
                    data-image-id="{{ $image->id }}"
                    data-liked="{{ $userHasLiked ? 'true' : 'false' }}"
                    data-url="{{ route('likes.toggle', $image) }}"
                    onclick="toggleLike(this)"
                    class="flex items-center space-x-2 px-4 py-2 rounded-full border-2 transition-all duration-200
                           {{ $userHasLiked
                              ? 'border-red-400 bg-red-50 text-red-600'
                              : 'border-gray-300 bg-white text-gray-500 hover:border-red-300' }}">
                {{-- Icona de cor (plena si liked, buida si no) --}}
                <span id="like-icon" class="text-xl">{{ $userHasLiked ? '❤️' : '🤍' }}</span>
                <span id="like-count" class="font-semibold text-sm">{{ $image->likesCount() }}</span>
                <span class="text-sm">m'agrada</span>
            </button>

            {{-- Nombre de comentaris --}}
            <span class="text-sm text-gray-500">
                💬 {{ $image->comments->count() }} comentari(s)
            </span>
        </div>
        @else
        {{-- Usuari no autenticat: mostra el comptador sense botó interactiu --}}
        <div class="flex items-center space-x-3 mb-4 pb-4 border-b border-gray-100">
            <span class="text-gray-500">❤️ {{ $image->likesCount() }} m'agrada</span>
            <span class="text-gray-500">💬 {{ $image->comments->count() }} comentari(s)</span>
        </div>
        @endauth

        {{-- Descripció de la imatge --}}
        @if($image->description)
            <p class="text-gray-700 leading-relaxed">{{ $image->description }}</p>
        @else
            <p class="text-gray-400 italic text-sm">Sense descripció.</p>
        @endif
    </div>

    {{-- ─── SECCIÓ DE COMENTARIS ───────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-semibold text-gray-800 mb-4">
            Comentaris ({{ $image->comments->count() }})
        </h2>

        {{-- Llistat de comentaris (ordenats de més antic a més nou) --}}
        @forelse($image->comments as $comment)
            <div class="flex space-x-3 mb-4 pb-4 border-b border-gray-50 last:border-0 last:mb-0 last:pb-0">
                {{-- Avatar del comentarista --}}
                <img src="{{ $comment->user->avatarUrl() }}"
                     alt="{{ $comment->user->name }}"
                     class="w-8 h-8 rounded-full object-cover border border-gray-200 flex-shrink-0 mt-0.5">

                <div class="flex-1 min-w-0">
                    <div class="flex items-baseline justify-between flex-wrap gap-1">
                        <div class="flex items-baseline space-x-2">
                            {{-- Nick del comentarista --}}
                            <span class="text-sm font-semibold text-gray-800">
                                {{ $comment->user->displayName() }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Botons d'editar/eliminar (only owner del comentari) --}}
                        @auth
                            @if(Auth::id() === $comment->user_id)
                                <div class="flex items-center space-x-1 ml-auto">
                                    <a href="{{ route('comments.edit', $comment) }}"
                                       class="text-xs text-indigo-500 hover:text-indigo-700 px-2 py-0.5 rounded hover:bg-indigo-50 transition-colors">
                                        Editar
                                    </a>
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                          onsubmit="return confirm('Eliminar aquest comentari?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-xs text-red-500 hover:text-red-700 px-2 py-0.5 rounded hover:bg-red-50 transition-colors">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>

                    {{-- Contingut del comentari --}}
                    <p class="text-sm text-gray-700 mt-1 break-words">{{ $comment->content }}</p>
                </div>
            </div>
        @empty
            <p class="text-gray-400 text-sm italic">Encara no hi ha comentaris. Sigues el primer!</p>
        @endforelse

        {{-- ─── FORMULARI PER AFEGIR COMENTARI ────────────────────── --}}
        @auth
            <form action="{{ route('comments.store', $image) }}" method="POST" class="mt-6 pt-4 border-t border-gray-100">
                @csrf
                <div class="flex space-x-3">
                    {{-- Avatar de l'usuari autenticat --}}
                    <img src="{{ Auth::user()->avatarUrl() }}"
                         alt="{{ Auth::user()->name }}"
                         class="w-8 h-8 rounded-full object-cover border border-gray-200 flex-shrink-0 mt-1">
                    <div class="flex-1">
                        {{-- Camp de text del comentari --}}
                        <textarea name="content"
                                  rows="2"
                                  placeholder="Afegeix un comentari..."
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 resize-none"
                                  maxlength="500">{{ old('content') }}</textarea>

                        {{-- Errors de validació --}}
                        @error('content')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        {{-- Botó d'enviament --}}
                        <div class="flex justify-end mt-2">
                            <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                                Enviar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            {{-- Missatge per a usuaris no autenticats --}}
            <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500">
                    <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Inicia sessió</a>
                    per afegir un comentari.
                </p>
            </div>
        @endauth
    </div>

    {{-- Botó per tornar a la pàgina principal --}}
    <div class="mt-6 text-center">
        <a href="{{ route('home') }}"
           class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">
            ← Tornar a totes les imatges
        </a>
    </div>
</div>

{{-- ─── JAVASCRIPT PER AL LIKE REACTIU ─────────────────────────────── --}}
{{--
    Quan l'usuari clica el botó de like:
    1. Fa una petició POST via fetch() a /images/{id}/like
    2. Rep la resposta JSON: { liked: bool, likesCount: int }
    3. Actualitza la UI sense recarregar la pàgina
--}}
<script>
    /**
     * Commuta el like d'una imatge de forma reactiva (sense recarregar la pàgina).
     *
     * @param {HTMLElement} btn - El botó de like clicat
     */
    async function toggleLike(btn) {
        const imageId  = btn.dataset.imageId;
        const url      = btn.dataset.url;
        const icon     = document.getElementById('like-icon');
        const count    = document.getElementById('like-count');

        // Deshabilita el botó mentre es processa la petició (evita doble click)
        btn.disabled = true;

        try {
            // Petició AJAX al servidor
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // Token CSRF necessari per a Laravel
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            });

            // Comprova si la resposta és correcta
            if (!response.ok) throw new Error('Error al servidor');

            // Processa la resposta JSON
            const data = await response.json();

            // ── Actualitza la UI ──────────────────────────────────────
            // Actualitza el comptador de likes
            count.textContent = data.likesCount;

            // Actualitza la icona del cor
            icon.textContent = data.liked ? '❤️' : '🤍';

            // Actualitza l'estat del botó (classes CSS)
            btn.dataset.liked = data.liked ? 'true' : 'false';

            if (data.liked) {
                // Estat: ha donat like
                btn.classList.remove('border-gray-300', 'bg-white', 'text-gray-500', 'hover:border-red-300');
                btn.classList.add('border-red-400', 'bg-red-50', 'text-red-600');
            } else {
                // Estat: ha tret el like
                btn.classList.remove('border-red-400', 'bg-red-50', 'text-red-600');
                btn.classList.add('border-gray-300', 'bg-white', 'text-gray-500', 'hover:border-red-300');
            }

        } catch (error) {
            // En cas d'error, informa l'usuari
            console.error('Error al fer like:', error);
            alert('Hi ha hagut un error. Torna-ho a intentar.');
        } finally {
            // Rehabilita el botó sempre, tant si hi ha error com si no
            btn.disabled = false;
        }
    }
</script>
@endsection
