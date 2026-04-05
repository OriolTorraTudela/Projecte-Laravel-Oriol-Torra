@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Configuració del compte</h1>
        <p class="text-sm text-gray-500 mt-1">Gestiona la teva informació personal i avatar.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ── AVATAR ──────────────────────────────────────────────── --}}
            <div class="mb-6 flex flex-col items-center pb-6 border-b border-gray-100">
                {{-- Avatar actual (previsualitza la selecció nova) --}}
                <img id="avatar-preview"
                     src="{{ $user->avatarUrl() }}"
                     alt="Avatar de {{ $user->name }}"
                     class="w-24 h-24 rounded-full object-cover border-4 border-indigo-100 shadow-sm mb-3">

                {{-- Botó per canviar l'avatar --}}
                <label for="avatar"
                       class="cursor-pointer text-sm font-medium text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-4 py-1.5 rounded-full transition-colors">
                    🖼️ Canviar avatar
                    <input id="avatar" name="avatar" type="file"
                           accept="image/*" class="hidden"
                           onchange="previewAvatar(this)">
                </label>
                <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP — màx. 2MB</p>

                @error('avatar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── DADES PERSONALS ─────────────────────────────────────── --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                {{-- Nom --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input id="name" name="name" type="text"
                           value="{{ old('name', $user->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cognom --}}
                <div>
                    <label for="surname" class="block text-sm font-medium text-gray-700 mb-1">Cognom</label>
                    <input id="surname" name="surname" type="text"
                           value="{{ old('surname', $user->surname) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400">
                    @error('surname')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nick --}}
            <div class="mb-4">
                <label for="nick" class="block text-sm font-medium text-gray-700 mb-1">
                    Nick <span class="text-gray-400">(nom d'usuari únic)</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm">@</span>
                    <input id="nick" name="nick" type="text"
                           value="{{ old('nick', $user->nick) }}"
                           class="w-full border border-gray-300 rounded-lg pl-7 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400">
                </div>
                @error('nick')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Correu electrònic <span class="text-red-500">*</span>
                </label>
                <input id="email" name="email" type="email"
                       value="{{ old('email', $user->email) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400"
                       required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Telèfon --}}
            <div class="mb-5 pb-5 border-b border-gray-100">
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Telèfon</label>
                <input id="phone_number" name="phone_number" type="tel"
                       value="{{ old('phone_number', $user->phone_number) }}"
                       placeholder="6XXXXXXXX"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400">
                @error('phone_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── CANVI DE CONTRASENYA (opcional) ─────────────────────── --}}
            <div class="mb-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    Canviar contrasenya <span class="text-gray-400 font-normal">(opcional)</span>
                </h3>

                <div class="mb-3">
                    <label for="password" class="block text-sm text-gray-600 mb-1">Nova contrasenya</label>
                    <input id="password" name="password" type="password"
                           autocomplete="new-password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm text-gray-600 mb-1">Confirma la nova contrasenya</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                           autocomplete="new-password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400">
                </div>
            </div>

            {{-- Botó de desar --}}
            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                💾 Desar canvis
            </button>
        </form>
    </div>
</div>

<script>
    /**
     * Mostra una previsualització de l'avatar seleccionat
     * abans d'enviar el formulari.
     *
     * @param {HTMLInputElement} input - L'input de tipus file
     */
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
