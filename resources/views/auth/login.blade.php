<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sessió — InstaLaravel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4">

    <div class="w-full max-w-sm">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-3xl font-bold text-indigo-600">📸 InstaLaravel</a>
            <p class="text-gray-500 text-sm mt-2">Entra al teu compte</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Iniciar sessió</h2>

            {{-- Missatge d'error de credencials --}}
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correu electrònic
                    </label>
                    <input id="email" name="email" type="email"
                           value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400"
                           required autofocus autocomplete="username">
                </div>

                {{-- Contrasenya --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Contrasenya
                    </label>
                    <input id="password" name="password" type="password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400"
                           required autocomplete="current-password">
                </div>

                {{-- Remember me --}}
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center space-x-2 text-sm text-gray-600">
                        <input name="remember" type="checkbox"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-400">
                        <span>Recorda'm</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-indigo-600 hover:underline">
                            Contrasenya oblidada?
                        </a>
                    @endif
                </div>

                {{-- Botó de login --}}
                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                    Iniciar sessió
                </button>
            </form>

            {{-- Compte de prova --}}
            <div class="mt-4 p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                <p class="text-xs text-indigo-700 font-medium mb-1">👤 Compte de prova:</p>
                <p class="text-xs text-indigo-600">Email: <code class="bg-white px-1 rounded">test@example.com</code></p>
                <p class="text-xs text-indigo-600">Password: <code class="bg-white px-1 rounded">password</code></p>
            </div>

            {{-- Enllaç al registre --}}
            <p class="text-center text-sm text-gray-500 mt-5">
                No tens compte?
                <a href="{{ route('register') }}" class="text-indigo-600 font-medium hover:underline">Registra't</a>
            </p>
        </div>
    </div>
</body>
</html>
