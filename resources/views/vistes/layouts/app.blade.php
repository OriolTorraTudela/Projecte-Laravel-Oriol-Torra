<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Token CSRF per a les peticions AJAX (likes) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>InstaLaravel — {{ $title ?? 'Inici' }}</title>

    {{-- Fonts de Google --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite compila Tailwind CSS i el JS de l'aplicació --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- ─── NAVBAR ─────────────────────────────────────────────────── --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14">

                {{-- Logo / Nom de l'aplicació --}}
                <a href="{{ route('home') }}"
                   class="text-xl font-bold text-indigo-600 tracking-tight hover:text-indigo-700">
                    📸 InstaLaravel
                </a>

                {{-- Menú de navegació --}}
                <div class="flex items-center space-x-4">
                    @auth
                        {{-- Pujar imatge --}}
                        <a href="{{ route('images.create') }}"
                           class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">
                            ＋ Pujar
                        </a>

                        {{-- Menú d'usuari amb avatar --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="flex items-center space-x-2 focus:outline-none group">
                                {{-- Avatar de l'usuari al navbar --}}
                                <img src="{{ Auth::user()->avatarUrl() }}"
                                     alt="Avatar de {{ Auth::user()->name }}"
                                     class="w-8 h-8 rounded-full object-cover border-2 border-indigo-200 group-hover:border-indigo-400 transition-colors">
                                <span class="text-sm font-medium text-gray-700 hidden sm:block">
                                    {{ Auth::user()->displayName() }}
                                </span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Dropdown del menú --}}
                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                                <a href="{{ route('profile.edit') }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    👤 El meu perfil
                                </a>
                                <hr class="my-1 border-gray-100">
                                {{-- Formulari de logout --}}
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        🚪 Tancar sessió
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Botons per a usuaris no autenticats --}}
                        <a href="{{ route('login') }}"
                           class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">
                            Iniciar sessió
                        </a>
                        <a href="{{ route('register') }}"
                           class="bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                            Registrar-se
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ─── CONTINGUT PRINCIPAL ────────────────────────────────────── --}}
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Missatges d'èxit (flash messages) --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center space-x-2"
                 x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 4000)">
                <span>✅</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Missatges d'error generals --}}
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <span class="text-sm font-medium">❌ {{ session('error') }}</span>
            </div>
        @endif

        {{-- Contingut de cada pàgina --}}
        @yield('content')
    </main>

    {{-- ─── FOOTER ─────────────────────────────────────────────────── --}}
    <footer class="text-center py-8 text-xs text-gray-400 border-t border-gray-100 mt-12">
        InstaLaravel — DAW M613 B3 · Laravel 12
    </footer>

    {{-- Alpine.js per al dropdown del navbar --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
