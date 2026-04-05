<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar-se — InstaLaravel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-3xl font-bold text-indigo-600">📸 InstaLaravel</a>
            <p class="text-gray-500 text-sm mt-2">Crea el teu compte i comparteix les teves imatges</p>
        </div>

        {{-- Formulari de registre --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Crear compte</h2>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                {{-- Nom i Cognom en dos columnes --}}
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input id="name" name="name" type="text"
                               value="{{ old('name') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                               required autofocus>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="surname" class="block text-sm font-medium text-gray-700 mb-1">Cognom</label>
                        <input id="surname" name="surname" type="text"
                               value="{{ old('surname') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        @error('surname')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Nick --}}
                <div class="mb-4">
                    <label for="nick" class="block text-sm font-medium text-gray-700 mb-1">Nick (nom d'usuari)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm">@</span>
                        <input id="nick" name="nick" type="text"
                               value="{{ old('nick') }}"
                               class="w-full border border-gray-300 rounded-lg pl-7 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
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
                           value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                           required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Telèfon --}}
                <div class="mb-4">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Telèfon</label>
                    <input id="phone_number" name="phone_number" type="tel"
                           value="{{ old('phone_number') }}"
                           placeholder="6XXXXXXXX"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @error('phone_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contrasenya --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Contrasenya <span class="text-red-500">*</span>
                    </label>
                    <input id="password" name="password" type="password"
                           autocomplete="new-password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                           required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirmar contrasenya --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Confirmar contrasenya <span class="text-red-500">*</span>
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                           autocomplete="new-password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                           required>
                </div>

                {{-- Botó de registre --}}
                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                    Crear compte
                </button>
            </form>

            {{-- Enllaç cap al login --}}
            <p class="text-center text-sm text-gray-500 mt-5">
                Ja tens compte?
                <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Inicia sessió</a>
            </p>
        </div>
    </div>
</body>
</html>
