<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle de Estoque - Confirmar Senha</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.9s ease-out forwards; }
    </style>
</head>
<body class="antialiased">

    <div class="relative flex items-center justify-center min-h-screen bg-gradient-to-br from-indigo-700 via-indigo-800 to-purple-900">

        <!-- Overlay de contraste -->
        <div class="absolute inset-0 bg-black/40"></div>

        <!-- Caixa central -->
        <div class="relative w-full max-w-md px-8 py-10 bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl fade-in text-white">

            <!-- Ícone -->
            <div class="mx-auto mb-8 w-20 h-20 flex items-center justify-center rounded-full bg-white/10 text-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 11c0-1.657-1.343-3-3-3S6 9.343 6 11v1H5a2 2 0 00-2 2v2h14v-2a2 2 0 00-2-2h-1v-1c0-1.657-1.343-3-3-3z" />
                </svg>
            </div>

            <!-- Título -->
            <h1 class="text-2xl font-bold text-center">Confirme sua Senha</h1>
            <p class="mt-3 text-center text-indigo-100 text-sm">
                Esta é uma área segura. Digite sua senha antes de continuar.
            </p>

            <!-- Formulário -->
            <form method="POST" action="{{ route('password.confirm') }}" class="mt-6">
                @csrf

                <!-- Campo Senha -->
                <div>
                    <label for="password" class="block text-sm font-medium mb-2">Senha</label>
                    <input id="password"
                           class="block w-full px-4 py-3 rounded-lg border border-transparent focus:ring-2 focus:ring-indigo-400 text-gray-800"
                           type="password"
                           name="password"
                           required
                           autocomplete="current-password">
                    @error('password')
                        <div class="mt-2 text-sm text-red-300">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botão -->
                <div class="mt-6">
                    <button type="submit"
                        class="w-full px-6 py-3 rounded-lg bg-indigo-500 text-white font-semibold shadow-lg hover:bg-indigo-400 transition">
                        Confirmar
                    </button>
                </div>
            </form>

            <!-- Rodapé -->
            <footer class="mt-8 text-center text-xs text-indigo-200">
                Desenvolvido com Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                (PHP v{{ PHP_VERSION }})
            </footer>
        </div>
    </div>

</body>
</html>
